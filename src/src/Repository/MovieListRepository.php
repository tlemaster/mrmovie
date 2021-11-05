<?php

// src/Repository/MovieListRepository.php
namespace App\Repository;

use App\Entity\MovieList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MovieListRepository extends ServiceEntityRepository
{
    /**
     * class constructor
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieList::class);
    }
}