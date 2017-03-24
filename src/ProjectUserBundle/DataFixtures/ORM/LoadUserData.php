<?php

namespace ProjectUserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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
            //$user->setPassword('3NCRYPT3D-V3R51ON');
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_ADMIN'));
            $user->setUpdatedAt(new \DateTime());

            // Update the user
            $userManager->updateUser($user, true);

            $i++;

        }

    }
}