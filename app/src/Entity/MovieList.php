<?php

namespace App\Entity;

use App\Repository\MovieListRepository;
use App\Entity\Movie as Movie;
use App\Entity\User as User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieListRepository::class)
 */
class MovieList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $viewed;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $lastDateSuggested;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="movieLists")
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="movieLists")
     */
    private $user;

    /**
     * Entity get and set functions
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getViewed(): ?bool
    {
        return $this->viewed;
    }

    public function setViewed(?bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }

    public function getLastDateSuggested(): ?\DateTimeInterface
    {
        return $this->lastDateSuggested;
    }

    public function setLastDateSuggested(?\DateTimeInterface $lastDateSuggested): self
    {
        $this->lastDateSuggested = $lastDateSuggested;

        return $this;
    }
    
    /**
     * Entity relationship functions
     */
    public function getMovie(): ?Movie 
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self 
    {
        $this->movie = $movie;
        
        return $this;
    }

    public function getUser(): ?User 
    {
        return $this->user;
    }

    public function setUser(?User $user): self 
    {
        $this->user = $user;
        
        return $this;
    }
}
