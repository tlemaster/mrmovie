<?php

// src/Controller/SignUpController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * SignUpController.
 *
 * @author Todd LeMaster <lemaster.todd@gmail.com>
 */
class SignUpController extends AbstractController 
{
    public function display(): Response
    {
        return $this->render('app/signup.html.twig');
    }
}
