<?php

namespace ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ProjectBundle\Entity\Sound;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadSoundData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // help structure for playlist refs
        // $this->addReference('playlist-'.$i.'-'.$i_Playlist, $playlist);

        // default values
        $numberSounds = 8;
        $numberPlaylist = 6;
        $numberProfiles = 20;

        // incrementations values
        $i_Users = 1;

        // for all users saved before
        while ($i_Users < $numberProfiles) {

            $i_Playlist = 1;

            while ($i_Playlist < $numberPlaylist) {

                $i_Sounds = 1;

                while ($i_Sounds < $numberSounds) {

                    $sound = new Sound();
                    $playlistRef = $this->getReference('playlist-'.$i_Users.'-'.$i_Playlist);

                    $playerRef = $this->getReference("youtube");
                    $genreRef = $this->getReference("Rock");

                    $sound->addPlaylist($playlistRef);
                    $playlistRef->addSound($sound);

                    $sound->addGenre($genreRef);

                    $sound->setName("soundname ". $i_Sounds);
                    $sound->setArtiste("Artist " . ($i_Sounds + 5));
                    $sound->setLink("https://www.youtube.com/embed/Bj8425Ma6F8");
                    $sound->setPlayers($playerRef);

                    $manager->persist($sound);
                    $i_Sounds++;

                }

                $manager->flush();
                $i_Playlist++;

            }

            $i_Users++;
        }

    }

    // order of fixtures
        public function getOrder()
    {
        return 5;
    }

}