<?
// src/Controller/UserRegistrationController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserRegistrationController extends AbstractController 
{
    /**
     * @Route("/signup", name="user_registration")
     */
    public function display()
    {
        return $this->render('user/registration.html.twig');
    }
}
