<?php

// src/Entity/Movie.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Contstraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\Table(name="movie")
 */
class Movie 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=108)
     */
    protected $mdbId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $posterPath;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MovieList", mappedBy="movie")
     */
    private $movieLists;

    /**
     * class constructor
     */
    public function __construct()
    {
        $this->movieLists = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMdbId(): string
    {
        return $this->mdbId;
    }

    /**
     * @param string $mdbId
     *
     * @return string
     */
    public function setMdbId(string $mdbId): string
    {
        $this->mdbId = $mdbId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return string
     */
    public function setTitle(string $title): string
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getPosterPath(): string
    {
        return $this->posterPath;
    }

    /**
     * @param string $posterPath
     *
     * @return string
     */
    public function setPosterPath(string $posterPath): string
    {
        $this->posterPath = $posterPath;

        return $this;
    }

    /**
     * return Collection|MovieList[]
     */
    public function getMovieLists(): Collection
    {
        return $this->movieLists;
    }

}