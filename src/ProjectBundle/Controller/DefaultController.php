<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    // datas to push to view
    private $datas = [];

    /**
     * @Route("/stream", name="home")
     */
    public function indexAction()
    {
        // Block New Users
        $this->datas["newUsers"] = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findNewUsers();
        $this->datas["title"] = "Homepage";

        // List latest Playlists
        $this->datas["latestPlaylists"] = $this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findLatestPlaylists();

        foreach($this->datas["latestPlaylists"] as $playlist){
            //var_dump($playlist);
        }

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
     * @Route("/users/{slug_username}/daysounds", name="day_sounds")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function renderProfilAction($slug_username)
    {

        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBySlug($slug_username);
        $this->datas["title"] = $user->getUsername() . " profile";


        if(!count($user)){
            return $this->redirect($this->generateUrl('404'));
        }

        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');
        $listePlaylist = $repository->findByUser($user);

        return $this->render('ProjectBundle:Default:daysoundPlaylist.html.twig');

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
