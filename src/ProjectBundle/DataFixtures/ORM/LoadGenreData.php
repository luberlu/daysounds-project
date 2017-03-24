<?php

namespace ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ProjectBundle\Entity\Genre;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadGenreData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $genres = array("Rock", "Pop", "Alternative", "Electro", "Techno", "Funk", "Rap", "Hip-Hop");

        foreach ( $genres as $genre ) {

            $genreInst = new Genre();
            $genreInst->setName( $genre );
            $manager->persist( $genreInst );

            $this->addReference($genre, $genreInst);
        }

        $manager->flush();

    }

    // order of fixtures
    public function getOrder()
    {
        return 3;
    }
}