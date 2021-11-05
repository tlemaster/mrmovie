<?php

// src/Entity/MovieList.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Contstraints\UniqueEntity;
use App\Entity\Movie as Movie;
use App\Entity\User as User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieListRepository")
 * @ORM\Table(name="movieList")
 */
class MovieList
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool|null
     * 
     * @ORM\Column(name="suggested", type="boolean", nullable=true)
     */
    protected $suggested;

    /**
     * @var bool|null
     * 
     * @ORM\Column(name="viewed", type="boolean", nullable=true)
     */
    protected $viewed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="movieLists")
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="movieLists")
     */
    private $user;

   
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

     /**
     * @return bool|null
     */
    public function getSuggested(): bool
    {
        return $this->suggested;
    }

    /**
     * @param bool|null $suggested
     *
     * @return bool|null
     */
    public function setSuggested(bool $suggested): bool
    {
        $this->suggested = $suggested;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getViewed(): bool
    {
        return $this->viewed;
    }

    /**
     * @param bool|null $viewed
     *
     * @return bool|null
     */
    public function setViewed(bool $viewed): bool
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMovie(): ?Movie 
    {
        return $this->movie;
    }

    /**
     * @param mixed $movie
     * 
     * @return mixed
     */
    public function setMovie(?Movie $movie): self 
    {
        $this->movie = $movie;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser(): ?User 
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * 
     * @return mixed
     */
    public function setUser(?User $user): self 
    {
        $this->user = $user;
        
        return $this;
    }

}