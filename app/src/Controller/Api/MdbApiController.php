<?php

// src/controller/Api/MdbApiController.php
namespace App\Controller\Api;

use phpDocumentor\Reflection\Types\Mixed_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MdbApiController extends AbstractController
{
    protected HttpClientInterface $mdbClient;
    
    public function __construct(HttpClientInterface $mdbClient)
    {
        $this->mdbClient = $mdbClient;
    }
    
    /**
     * @Route("/api/movie/search", name="mdb_api_movie_search")
     */
    public function searchMovie(string $searchTerm): mixed
    {
        //Todo: get post and possibly convert

        $response = $this->mdbClient->request(
            'GET',
            'search/movie',
            ['query' =>[
                'query' => $searchTerm   
            ]]
        );
        
        if ($response->getStatusCode() != 200) {
            return $this->render('error/error.html.twig');
        }

        return $this->json($response->getContent());
    }


      /**
     * @Route("/api/movie/suggest", name="mdb_api_movie_suggest")
     */
    public function suggestMovie(): mixed
    {
        $response = $this->mdbClient->request(
            'GET',
            'discover/movie',
        );
        
        if ($response->getStatusCode() != 200) {
            return $this->render('error/error.html.twig');
        }

        return $this->json($response->getContent());
    }


     /**
     * @Route("/api/test", name="mdb_api_test")
     */
    public function mdbiTest(): mixed 
    {
        $response = $this->mdbClient->request('GET','configuration');

        if ($response->getStatusCode() != 200) {
            return $this->json($response->getStatusCode());
        }

        return $this->json($response);
    }
}
