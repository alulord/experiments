<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontpageController extends Controller
{

    public function index()
    {
        return $this->render('frontpage/index.html.twig');
    }
}