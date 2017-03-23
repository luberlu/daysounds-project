<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    // datas to push to view
    private $datas = [];



    /**
     * @Route("/users/{slug_username}/stream", name="home")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($slug_username)
    {
        // Block New Users
        $this->datas["newUsers"] = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findNewUsers();
        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBySlug($slug_username);
        $this->datas["title"] = "Homepage";
        // List latest Playlists

        $userPlaylist=$userPlaylist=$this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findBy(array("user" => $user, "isDayli" => true));
        $this->datas["listePlaylist"] = $userPlaylist;

        return $this->render('ProjectBundle:Default:index.html.twig', array("datas" => $this->datas));
    }

    /**
     * @Route("/users/{slug_username}", name="user-profil")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderDayliAction($slug_username)
    {
        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBySlug($slug_username);
        $this->datas["title"] = $user->getUsername() . " profile";

        if(!count($user)){
            return $this->redirect($this->generateUrl('404'));
        }

        $this->datas["listePlaylist"] = $this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findByUser($user);

        $this->datas["slugUserName"]=$slug_username;

        return $this->render('ProjectBundle:Default:profil.html.twig',
            ["datas" => $this->datas]);
    }

    // Afficher le profil d'un utilisateur avec toutes ses playlists

    /**
     * @Route("/users", name="users")
     */

    public function renderUersAction()
    {

        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findall();
        $this->datas["title"] = "daysounds users";
        $this->datas["listeUsers"] =$user;

        return $this->render('ProjectBundle:Default:users.html.twig', array("datas" => $this->datas));

    }


    /**
     * @Route("/page-not-found", name="404")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function render404Action()
    {
        return $this->render('ProjectBundle:Default:404.html.twig');
    }

}
