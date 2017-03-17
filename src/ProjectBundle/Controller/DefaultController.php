<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ProjectBundle:Default:index.html.twig');
    }

    /**
     * @Route("/playlists")
     */
    public function playlistsAction()
    {
        return $this->render('ProjectBundle:Playlists:playlists-list.html.twig');
    }

    /**
     * @Route("/playlist/{id}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function seePlaylist($id)
    {
        return $this->render('ProjectBundle:Playlists:playlists-list.html.twig', ["id" => $id]);
    }
}
