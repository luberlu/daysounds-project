<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    // datas to push to view
    private $datas = [];

    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(){

        if($this->getUser()){
            return $this->redirect($this->generateUrl('stream'));
        }
        return $this->render('ProjectBundle:Default:index.html.twig');
    }

    /**
     * @Route("/stream", name="stream")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function streamAction()
    {
        if($this->getUser()) {
            $user = $this->getUser();
            $this->datas["slugUserName"] = $user->getSlug();

            // Block New Users
            $this->datas["newUsers"] = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findNewUsers();
            $this->datas["title"] = "Homepage";
            // List latest Playlists

            $userPlaylist = $userPlaylist = $this->getDoctrine()
                ->getRepository('ProjectBundle:Playlist')->findBy(array("user" => $user, "isDayli" => true));
            $this->datas["listePlaylist"] = $userPlaylist;

            return $this->render('ProjectBundle:Default:index.html.twig', array("datas" => $this->datas));
        }

        else return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @Route("/users/{slug_username}", name="user-profil")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderDayliAction($slug_username)
    {
        $this->datas["actions"] = false;

        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBySlug($slug_username);

        if($this->getUser() === $user){
            $this->datas["actions"] = true;
        }
        
        $this->datas["title"] = $user->getUsername() . " profile";
        $this->datas["slugUserName"] = $this->getUser()->getSlug();

        if(!count($user)){
            return $this->redirect($this->generateUrl('404'));
        }

        $this->datas["listePlaylist"] = $this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findByUser($user);

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
