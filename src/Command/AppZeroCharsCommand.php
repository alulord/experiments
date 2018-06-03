<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppZeroCharsCommand extends Command
{
    protected static $defaultName = 'app:zero-chars';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::REQUIRED, 'Argument description')
            ->addOption('decode', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($input->getOption('decode')) {
            $text = $this->decode();
        } else {
            $text = $this->encode($arg1);
        }
        $io->writeln(sprintf('Your text: %s', $text));
    }

    /**
     * @param string $arg1
     *
     * @return string
     */
    private function encode($arg1)
    {
        $hiddenMsg = 'hidden message test';
        $text = $this->makeTextInvisible($hiddenMsg);

        file_put_contents('test.log', $text.$arg1);

        return $text.$arg1;
    }

    /**
     * @return string
     */
    private function decode()
    {
        $content = file_get_contents('test.log');

        return $this->makeTextVisible($content);
    }

    private function makeTextVisible($msg)
    {

        $hiddenMsg = preg_replace('/\x{200B}/u', '1', $msg);
        $hiddenMsg = preg_replace('/\x{200C}/u', '0', $hiddenMsg);
        $hiddenMsg = preg_replace('/\x{FEFF}/u', ' ', $hiddenMsg);
        $msg = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $msg);
        $hiddenMsg = str_replace($msg, '', $hiddenMsg);
        $hiddenMsg = $this->ASCIIBinText($hiddenMsg);

        return $hiddenMsg.' '.$msg;
    }

    private function makeTextInvisible($binaryMsg)
    {
        $binaryMsg = $this->textBinASCII(($binaryMsg));
        $invisibleMsg = '';
        for ($i = 0; $i < strlen($binaryMsg); $i++) {
            if ($binaryMsg[$i] === '1') {
                $invisibleMsg .= "\u{200B}";
            } elseif ($binaryMsg[$i] === ' ') {
                $invisibleMsg .= "\u{FEFF}";
            } elseif ($binaryMsg[$i] === '0') {
                $invisibleMsg .= "\u{200C}";
            }
        }

        return $invisibleMsg;
    }

    private function textBinASCII($text)
    {
        $bin = array();
        for ($i = 0; strlen($text) > $i; $i++) {
            $bin[] = decbin(ord($text[$i]));
        }

        return implode(' ', $bin);
    }

    private function ASCIIBinText($bin)
    {
        $text = array();
        $bin = explode(" ", $bin);
        for ($i = 0; count($bin) > $i; $i++) {
            $text[] = chr(bindec($bin[$i]));
        }

        return implode($text);
    }
}
