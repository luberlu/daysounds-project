<?php

namespace ProjectUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

    protected $datas = [];

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

            return $this->redirect($this->generateUrl('list_followers',
                array('slug_username'=> $this->getUser()->getSlug()
                )
            ));

        }

        return $this->redirect("home");

    }

    /**
     * @Route("/users/{slug_username}/followers", name="list_followers")
     * @param $slug_username
     */

    public function listFollowers($slug_username){


        if($this->getUser()) {

            $this->datas["actions"] = false;

            $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBySlug($slug_username);

            if ($this->getUser() === $user) {
                $this->datas["actions"] = true;
            }

            $this->datas["title"] = $user->getUsername() . " - follows";
            $this->datas["slugUserName"] = $this->getUser()->getSlug();
            $this->datas["user"] = $user;

            $this->datas["list_follows"] = $user->getRelationUser();

            if (!count($user)) {
                return $this->redirect($this->generateUrl('404'));
            }

            $this->datas["listePlaylist"] = $this->getDoctrine()
                                                 ->getRepository('ProjectBundle:Playlist')->findByUser($user);

            return $this->render('ProjectBundle:Default:followers.html.twig',
                ["datas" => $this->datas]);
        }
        else return $this->redirect($this->generateUrl('home'));

    }


}
