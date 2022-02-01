<?php

// src/Controller/Movie/MovieController.php
namespace App\Controller\Movie;

use App\Controller\Api\MdbApiController;
use App\Entity\ApiAttribute;
use App\Entity\Movie;
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
        MdbApiController $mdbApi, 
        ManagerRegistry $doctrine
    ): Response {

        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $mdbImageUrl = $entityManager->getRepository(ApiAttribute::class)->findOneBy(['name' => 'mdbImageUrl']);
        $imdbMovieUrl = $entityManager->getRepository(ApiAttribute::class)->findOneBy(['name' => 'ImdbMovieUrl']);

        // Todo: change to apiAdapter once suggest is abastracted out of apiController
        $response = $mdbApi->suggestMovie($user->getId());
        $candidateMdbId = json_decode($response->getContent());
        $movieSuggestion = $entityManager->getRepository(Movie::class)->findOneBy(['mdbId' => $candidateMdbId]);

        if (!$movieSuggestion) {
            $response = $mdbApi->getMovie($candidateMdbId);
            $data = json_decode($response->getContent());
            
            $movieSuggestion = new Movie();
            $movieSuggestion->setMdbId($data->mdbId)
                ->setImdb($data->imdbId)
                ->setTitle($data->title)
                ->setPosterPath($data->posterPath);

            $entityManager->persist($movieSuggestion);
            $entityManager->flush();
        }

        return $this->render('components/movieSuggest.html.twig', [
            'movieSuggestion' => $movieSuggestion,
            'mdbImageUrl' => $mdbImageUrl,
            'imdbMovieUrl' => $imdbMovieUrl
        ]);
    }
}
