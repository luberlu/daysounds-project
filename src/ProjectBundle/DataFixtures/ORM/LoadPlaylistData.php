<?php

namespace ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ProjectBundle\Entity\Playlist;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadPlaylistData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $numberProfiles = 20;
        $numberPlaylist = 6;

        $i = 1;

        // for all users saved before
        while($i < $numberProfiles) {
            $userRef = $this->getReference('user-'.$i);

            $i_Playlist = 1;

            while($i_Playlist < $numberPlaylist) {

                $playlist = new Playlist();

                if ($i_Playlist == 1) {
                    $playlist->setName("All sounds");
                    $playlist->setIsDefault(true);
                    $playlist->setIsDayli(false);

                } elseif ($i_Playlist == 2) {
                    $playlist->setName("Dayli playlist");
                    $playlist->setIsDefault(false);
                    $playlist->setIsDayli(true);
                } else {
                    $playlist->setName("Playlist " . $i_Playlist);
                    $playlist->setIsDefault(false);
                    $playlist->setIsDayli(false);
                }

                $playlist->setDateAdd(new \DateTime());
                $playlist->setUser($userRef);
                $playlist->setPosition($i);

                $manager->persist($playlist);

                $this->addReference('playlist-'.$i.'-'.$i_Playlist, $playlist);
                $i_Playlist++;

            }


            $i++;

        }

        $manager->flush();
    }

    // order of fixtures
    public function getOrder()
    {
        return 4;
    }
}