<?php

namespace ProjectUserBundle\Repository;

/**
 * GenreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findNewUsers(){

        $allUsers = $this->findAll();
        $newUsers = $allUsers;

        return $newUsers;
    }
}