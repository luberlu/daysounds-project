<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('ProjectBundle:Default:index.html.twig');
    }

    /**
     * @Route("/daysound")
     */
    public function daysoundAction()
    {
        return $this->render('ProjectBundle:Default:layout.html.twig');
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function playlistsAction()
    {
        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');

        $listePlaylist = $repository->findAll();

        return $this->render('ProjectBundle:Default:profil.html.twig',  ["listePlaylist" => $listePlaylist]);
    }

    /**
     * @Route("/playlist/{id}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function seePlaylist($id)
    {
        return $this->render('ProjectBundle:Default:profil.html.twig', ["id" => $id]);
    }


    // Afficher le profil d'un utilisateur avec toutes ses playlists

    /**
     * @Route("/users/{id}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function renderProfilAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');

        $listePlaylist = $repository->findByUser($id);

        return $this->render('ProjectBundle:Default:profil.html.twig',
            ["listePlaylist" => $listePlaylist, "id_user" => $id]);
    }

}
