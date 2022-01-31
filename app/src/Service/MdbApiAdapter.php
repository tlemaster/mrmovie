<?php 

// src/Service/MdbApiAdapter.php
namespace App\Service;

use App\Entity\User;
use App\Entity\Movie;
use App\Entity\MovieList;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MdbApiAdapter
{
    protected HttpClientInterface $mdbClient;
    protected ManagerRegistry $doctrine;
    
    public function __construct(
        HttpClientInterface $mdbClient,
        ManagerRegistry $doctrine
    ) {
        $this->mdbClient = $mdbClient;
        $this->doctrine = $doctrine;
    }

    public function getMovie(string $mdbId): array
    {
        $response = $this->mdbClient->request(
            'GET',
            'movie/' . $mdbId             
        );

        $data = $this->processResponse($response);

        if (!$data) {
            return  ['mdb-error' => $response->getStatusCode()];
        }

        $movie = [
            'mdbId' => $data->id,
            'imdbId' => $data->imdb_id,
            'title' => $data->title,
            'posterPath' => $data->poster_path, 
        ];

        return $movie;
    }

    protected function processResponse(object $response): mixed
    {
        if ($response->getStatusCode() != 200) {
            return false;
        }

        return json_decode($response->getContent());
    }
}