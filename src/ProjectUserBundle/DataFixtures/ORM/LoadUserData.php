<?php

namespace ProjectUserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ProjectUserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $numberProfiles = 20; $i = 1;

        while($i < $numberProfiles) {
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->createUser();

            // Create our user and set details
            $user = $userManager->createUser();
            $user->setUsername("username ". $i);
            $user->setEmail('email'.$i.'@domain.com');
            $user->setPlainPassword('root');
            $user->setImageName("58cee7404d975.jpg");
            $user->setEnabled(true);

            if($i == 1){
                $user->setRoles(array('ROLE_ADMIN'));
            } else {
                $user->setRoles(array('ROLE_USER'));
            }

            $user->setUpdatedAt(new \DateTime());

            // Update the user
            $userManager->updateUser($user, true);
            $this->addReference('user-'.$i, $user);

            $i++;

        }

    }

    // order of fixtures
    public function getOrder()
    {
        return 1;
    }
}