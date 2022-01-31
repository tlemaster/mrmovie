<?php

// src/Controller/Movie/MovieListController.php
namespace App\Controller\Movie;

use App\Controller\Api\MdbApiController;
use App\Entity\ApiAttribute;
use App\Entity\MovieList;
use App\Entity\Movie;
use App\Entity\User;
use \DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieListController extends AbstractController
{
    protected ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;   
    }

    /**
     * @Route("/movie/list/", name="movie_list")
     */
    public function index(MdbApiController $mdbApi): Response
    {
        $user = $this->getUser();
        $entityManager = $this->doctrine->getManager();
        $movieLists = $entityManager->getRepository(MovieList::class)->findBy(['user' => $user]);
        $imdbMovieUrl = $entityManager->getRepository(ApiAttribute::class)->findOneBy(['name' => 'ImdbMovieUrl']);
        $mdbImageUrl = $entityManager->getRepository(ApiAttribute::class)->findOneBy(['name' => 'mdbImageUrl']);

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
    
        return $this->render('movie/list.html.twig', [
            'user' => $user,
            'movieLists' => $movieLists,
            'imdbMovieUrl' => $imdbMovieUrl,
            'mdbImageUrl' => $mdbImageUrl,
            'movieSuggestion' => $movieSuggestion
        ]);
    }

    /**
     * @Route("/movie/list/delete/{id}", name="movie_list_delete")
     */
    public function deleteMovieFromList(int $id): Response
    {    
        $user = $this->getUser();
        $entityManager = $this->doctrine->getManager();
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


    /**
     * @Route("/movie/list/add/{id}", name="movie_list_add")
     */
    public function add(int $id): Response
    {
        $user = $this->getUser();
        $this->addMovieToList($id, $user);

        return $this->redirectToRoute('movie_list');
    }

     /**
     * @Route("/movie/list/skip/{id}", name="movie_list_skip")
     */
    public function skip(int $id): Response
    {
        $user = $this->getUser();
        $this->addMovieToList($id, $user, $skip = true);

        return $this->redirectToRoute('movie_list');
    }


    protected function addMovieToList(int $id, User $user, $skip = false): void
    {
        $entityManager = $this->doctrine->getManager();
        $movie = $entityManager->getRepository(Movie::class)->find($id);

        if (!$movie) {
            return;
        }

        $movieList = $entityManager->getRepository(MovieList::class)
            ->findOneBy([
                'user' => $user, 
                'movie' => $movie
            ]);
        
        if ($movieList) {
            return;
        }

        $date = New DateTime();
        $movieList = new MovieList();
        $movieList->setMovie($movie)
            ->setUser($user)
            ->setLastDateSuggested($date);

        if (!$skip) {
            $movieList->setViewed(true);
        }
        
        $entityManager->persist($movieList);
        $entityManager->flush();

        return;
    }

}
