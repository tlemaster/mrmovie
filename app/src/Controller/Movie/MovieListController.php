<?php

// src/Controller/Movie/MovieListController.php
namespace App\Controller\Movie;

use App\Controller\Api\MdbApiController;
use App\Entity\ApiAttribute;
use App\Entity\MovieList;
use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class MovieListController extends AbstractController
{
    /**
     * @Route("/movie/list/", name="movie_list")
     */
    public function index(ManagerRegistry $doctrine, MdbApiController $mdbApi): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $movieLists = $entityManager->getRepository(MovieList::class)->findBy(['user' => $user]);
        $imdbMovieUrl = $entityManager->getRepository(ApiAttribute::class)->findOneBy(['name' => 'ImdbMovieUrl']);

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
        }
    
        return $this->render('movie/list.html.twig', [
            'user' => $user,
            'movieLists' => $movieLists,
            'imdbMovieUrl' => $imdbMovieUrl,
            'movieSuggestion' => $movieSuggestion
        ]);
    }

    /**
     * @Route("/movie/list/delete/{id}", name="movie_list_delete")
     */
    public function deleteMovieFromList(
        int $id, 
        ManagerRegistry $doctrine
    ): RedirectResponse {
        
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $movie = $entityManager->getRepository(Movie::class)->find($id);

        if (!$movie) {
            return $this->redirectToRoute('movie_list');
        }
            
        if ($movieList = $entityManager->getRepository(MovieList::class)->findOneBy([
            'user' => $user, 
            'movie' => $movie
        ])){
            $entityManager->remove($movieList);
            $entityManager->flush();
            $this->addFlash('success', 'Movie was Deleted');
        };  
    
        return $this->redirectToRoute('movie_list');        
    }

}
