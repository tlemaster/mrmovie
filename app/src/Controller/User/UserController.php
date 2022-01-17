<?php

// src/Controller/User/UserController.php
namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\UserRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController 
{
    /**
     * @Route("/signup", name="user_registration")
     */
    public function registerUser(
        Request $request,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "Your accound was created");

            $token = new UsernamePasswordToken($user, $hashedPassword, 'main');
            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));
        }

        return $this->render('user/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/login", name="user_login")
     */
    public function loginUser(AuthenticationUtils $authenticationUtils): Response 
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController',  
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
