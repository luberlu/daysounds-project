<?php

namespace ProjectUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/user")
     */
    public function indexAction()
    {
        return $this->render('ProjectUserBundle:Default:index.html.twig');
    }


    /**
     * @Route("/follow/{slug_username}", name="add_follow")
     * @param $slug_username
     */
    public function addFollowByTo($slug_username){

        if($this->getUser()){

            $user = $this->getDoctrine()->getRepository("ProjectUserBundle:User")->findOneBySlug($slug_username);
            $em = $this->getDoctrine()->getManager();

            $user->addRelationUser($this->getUser());
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('user-profil',
                array('slug_username'=> $this->getUser()->getSlug()
                )
            ));

        }




    }
}
