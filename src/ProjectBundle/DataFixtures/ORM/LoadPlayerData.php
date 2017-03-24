<?php

namespace ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ProjectBundle\Entity\Player;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadPlayerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $players = array(
            array("soundcloud", "soundcloud.com"),
            array("youtube", "youtube.com")
        );

        foreach ( $players as $player ) {

            $playerInst = new Player();
            $playerInst->setName( $player[0] );
            $playerInst->setLinkExample( $player[1] );
            $manager->persist( $playerInst );

            $this->addReference($player[0], $playerInst);
        }

        $manager->flush();

    }

    // order of fixtures
    public function getOrder()
    {
        return 2;
    }
}