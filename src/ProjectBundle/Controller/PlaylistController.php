<?php

namespace ProjectBundle\Controller;

use ProjectBundle\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ProjectBundle\Entity\Playlist;
use ProjectBundle\Model\AddPlaylistType;
use Symfony\Component\HttpFoundation\Request;

class PlaylistController extends DefaultController
{

    /**
     * @Route("/playlists/add")
     */
    public function addAction(Request $request)
    {

        $playlist = new Playlist();
        $form = $this->createForm(AddPlaylistType::class, $playlist);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {

                // save the proposition
                $em = $this->getDoctrine()->getManager();
                $em->persist($playlist);
                $em->flush();

                // add a flash message
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Your playlist has been saved!');

                return $this->redirect($this->generateUrl('playlists_list'));
            }
        }

        return $this->render('ProjectBundle:Playlists:add.html.twig', ["form" => $form->createView()]);

    }

    public function addDefaultPlaylist($id_user){

        $em = $this->getDoctrine()->getManager();

        $playlist = new Playlist();
        $playlist->setName("Default playlist");
        $playlist->setPosition(1);
        $playlist->setUser($id_user);

        $em->persist($playlist);
        $em->flush();

    }

}