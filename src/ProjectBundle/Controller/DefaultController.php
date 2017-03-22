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
     */
    public function indexAction()
    {
        // Block New Users
        $this->datas["newUsers"] = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findNewUsers();
        $this->datas["title"] = "Homepage";

        return $this->render('ProjectBundle:Default:index.html.twig', array("datas" => $this->datas));
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
     * @Route("/users/{slug_username}", name="profilUser")
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

        $this->datas["listePlaylist"] = $this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findByUser($user);

        return $this->render('ProjectBundle:Default:profil.html.twig',
            ["datas" => $this->datas]);
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
