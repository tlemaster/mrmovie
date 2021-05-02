<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * HomeController.
 *
 * @author Todd LeMaster <lemaster.todd@gmail.com>
 */
class HomeController extends AbstractController 
{
    public function show(): Response
    {
        return $this->render('app/home.html.twig');
    }
}
