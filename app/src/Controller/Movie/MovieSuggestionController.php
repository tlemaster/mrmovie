<?php

// src/Controller/Movie/MovieSuggestionController.php
namespace App\Controller\Movie;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieSuggestionController extends AbstractController
{
    /**
     * @Route("/movie-suggestion", name="movie_suggestion")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('movie_suggestion/movieSuggest.html.twig', [
            'user' => $user
        ]);
    }
}
