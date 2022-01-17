<?php

// src/Entity/Movie.php
namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=108, nullable=true)
     */
    private $mdbId;

    /**
     * @ORM\Column(type="string", length=108, nullable=true)
     */
    private $imdbId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $posterPath;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MovieList", mappedBy="movie")
     */
    private $movieLists;

    /**
     *  Entity get and set functions 
    */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getMdbId(): ?string
    {
        return $this->mdbId;
    }

    public function setMdbId(?string $mdbId): self
    {
        $this->mdbId = $mdbId;

        return $this;
    }

    public function getImdbId(): ?string
    {
        return $this->imdbId;
    }

    public function setImdb(?string $imdbId): self
    {
        $this->imdbId = $imdbId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }

    public function setPosterPath(?string $posterPath): self
    {
        $this->posterPath = $posterPath;

        return $this;
    }

    /**
     * MovieList relationship
     */
    public function getMovieLists(): Collection
    {
      return $this->movieLists;
    }
}
