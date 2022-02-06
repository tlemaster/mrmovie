<?php

// src/Controller/Movie/MovieController.php
namespace App\Controller\Movie;

use App\Entity\ApiAttribute;
use App\Entity\Movie;
use App\Entity\MovieList;
use App\Service\MdbApiAdapter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
   /**
    * @Route("/movie/suggest/", name="movie_suggest")
    */
    public function suggest(
        MdbApiAdapter $adapter, 
        ManagerRegistry $doctrine
    ): Response {

        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $mdbImageUrl = $entityManager->getRepository(ApiAttribute::class)
            ->findOneBy(['name' => 'mdbImageUrl']);
        $imdbMovieUrl = $entityManager->getRepository(ApiAttribute::class)
            ->findOneBy(['name' => 'ImdbMovieUrl']);

        $candidateMdbId = $adapter->discoverMovie($user);
        $movieSuggestion = $entityManager->getRepository(Movie::class)->findOneBy(['mdbId' => $candidateMdbId]);

        if (!$movieSuggestion) {
            $mdbMovie = $adapter->getMovie($candidateMdbId);
                
            $movieSuggestion = new Movie();
            $movieSuggestion->setMdbId($mdbMovie['mdbId'])
                ->setImdb($mdbMovie['imdbId'])
                ->setTitle($mdbMovie['title'])
                ->setPosterPath($mdbMovie['posterPath']);

            $entityManager->persist($movieSuggestion);
            $entityManager->flush();
        }

        return $this->render('components/movieSuggest.html.twig', [
            'movieSuggestion' => $movieSuggestion,
            'mdbImageUrl' => $mdbImageUrl,
            'imdbMovieUrl' => $imdbMovieUrl
        ]);
    }

    /**
     * @Route("/movie/search", name="movie_search")
     */
    public function search(): Response 
    {
        $user = $this->getUser();
        return $this->render('components/movieSearch.html.twig', [
            'user' => $user 
        ]);
    }
}
