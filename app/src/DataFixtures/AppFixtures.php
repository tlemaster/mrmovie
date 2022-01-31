<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\MovieList;
use App\Entity\User;
use App\Entity\ApiAttribute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $movies = $this->buildMovies($manager);
        $manager->flush();

        $this->buildUsers($manager);
        $manager->flush();
        
        $this->buildApiAttributes($manager);
        $manager->flush();
        
    }

    public function buildMovies(ObjectManager $manager): void 
    {
        $titles = ['Spider-Man: No Way Home', 'Dune', 'Eternals', 'The Suicide Squad'];
        $mDbIds = ['634649','438631', '524434', '436969'];
        $imDbIds = ['tt10872600', 'tt1160419', 'tt9032400', 'tt6334354'];
        $posterPaths = ['1g0dhYtq4irTY1GPXvft6k4YLjm.jpg', 'd5NXSklXo0qyIYkgV94XAgMIckC.jpg', 'b6qUu00iIIkXX13szFy7d0CyNcg.jpg', 'kb4s0ML0iVZlG6wAKbbs9NAm6X.jpg'];

        for ($c = 0; $c < count($mDbIds); $c++) {
            $movie = new Movie();
            $movie->setTitle($titles[$c])
                ->setMdbId($mDbIds[$c])
                ->setImdb($imDbIds[$c])
                ->setPosterPath($posterPaths[$c]);
            
            $manager->persist($movie);
        
            $this->movies = [$movie];
        }
    }

    public function buildUsers(ObjectManager $manager): void
    {
        $emails = ['test@testmail.com', 'test2@testmail.com'];
        $passwords = ['Password!', 'Password2!'];

        for ($c = 0; $c < count($emails); $c++) {
            $user = new User();
            $user->setEmail($emails[$c]);
            $password = $this->hasher->hashPassword($user, $passwords[$c]);
            $user->setPassword($password);
            $manager->persist($user);

            $this->buildMovieList($user, $manager);
        }
    }

    public function buildMovieList(User $user, ObjectManager $manager): void 
    {
        $movies = $manager->getRepository(Movie::class)->findAll();
        
        foreach ($movies as $movie) {
            $movieList = new MovieList();
            $movieList->setMovie($movie)
                ->setUser($user)
                ->setViewed(true);
            
                $manager->persist($movieList);
        }
    }

    public function buildApiAttributes(ObjectManager $manager): void 
    {
        $apiAttribute = new ApiAttribute();
        $apiAttribute->setName('ImdbMovieUrl')
            ->setValue('https://www.imdb.com/title/')
            ->setApi('none');
        $manager->persist($apiAttribute);
    }
}
