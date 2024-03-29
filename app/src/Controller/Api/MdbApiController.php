<?php

// src/controller/Api/MdbApiController.php
namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Movie;
use App\Entity\MovieList;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\MdbApiAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class MdbApiController extends AbstractController
{
    protected HttpClientInterface $mdbClient;
    protected ManagerRegistry $doctrine;
    
    public function __construct(
        HttpClientInterface $mdbClient,
        ManagerRegistry $doctrine,
        MdbApiAdapter $adapter
    ) {
        $this->mdbClient = $mdbClient;
        $this->doctrine = $doctrine;
        $this->adapter = $adapter;
    }

    protected function processResponse(object $response): ?object 
    {
        if ($response->getStatusCode() != 200) {
            return null;
        }

        return json_decode($response->getContent());
    }
    
    /**
     * @Route("/api/movie/get", name="mdb_api_movie_get")
     */
    public function getMovie(string $mdbId): JsonResponse 
    {         
        $data = $this->adapter->getMovie($mdbId);

        if (array_key_exists('mdb-error', $data)) {
            return $this->json($data['mdb-error']);
        };

        return $this->json($data);
    }
    
    /**
     * @Route("/api/movie/search", name="mdb_api_movie_search", methods={"GET"})
     */
    public function searchMovie(Request $request): JsonResponse 
    {
        if (!$request->query->get('searchTerm')) {
            return $this->json('Error - search term is invalid');
        }
        
        $searchTerm = $request->query->get('searchTerm');
        
        $data = $this->adapter->searchMovie($searchTerm);
        
        if (array_key_exists('mdb-error',$data)) {
           return $this->json($data['mdb-error']);
        }
        
        return $this->json($data);
    }


    /**
     * @Route("/api/movie/suggest", name="mdb_api_movie_suggest")
     */
    public function suggestMovie(int $userId): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        
        if (!$user) {
            return $this->json('Error - couldnt find user');
        }

        $movieSuggestion = $this->adapter->discoverMovie($user); 

        return $this->json($movieSuggestion);
    }


     /**
     * @Route("/api/test", name="mdb_api_test")
     */
    public function mdbiTest(): JsonResponse 
    {
        $data = $this->adapter->getConfig();
        
        if (property_exists($data, 'mdbError')) {
           return $this->json($data->mdbError);
        }
        
        return $this->json($data);
    }
}
