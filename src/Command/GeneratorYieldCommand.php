<?php

namespace App\Command;

use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GeneratorYieldCommand extends Command
{
    protected static $defaultName = 'generator:yield';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

//        $this->echoLogger($input, $output);
        $fileName = $input->getArgument('arg1');
        $linesGenerator = $this->fileReadGenerator($fileName);
        $io = new SymfonyStyle($input, $output);
        foreach ($linesGenerator as $line) {
//            $io->writeln($line);
            $io->note($linesGenerator->key());
            $io->note('One line '.memory_get_peak_usage(true) / 1024 / 1024);
        }
        $fileRead = $this->fileRead($fileName);
//        $io->writeln($fileRead);
        $io->note('Full file '.memory_get_peak_usage(true) / 1024 / 1024);
    }

    private function fileReadGenerator($fileName)
    {
        if (!$fileHandle = fopen($fileName, 'r')) {
            return;
        }
        while (false !== $line = fgets($fileHandle)) {
            yield $line;
        }

        fclose($fileHandle);
    }

    private function fileRead($fileName)
    {
        return file_get_contents($fileName);
    }

    private function echoGenerator()
    {
        while (true) {
            try {
                echo 'Log: '.yield.PHP_EOL;
            } catch (RuntimeCommandException $exception) {
                return 'Generator exited';
            }
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    private function echoLogger(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        /** @var \Generator $echoGenerator */
        $echoGenerator = $this->echoGenerator();
        while (true) {
            $logEntry = $io->ask('What do you want to log?');
            if ($logEntry === 'exit') {
                $echoGenerator->throw(new RuntimeCommandException('Generator exited'));
                $io->success($echoGenerator->getReturn());
                exit;
            }
            $echoGenerator->send($logEntry);
        }
        $echoGenerator->send($input->getArgument('arg1'));
    }
}
