<?php

namespace ProjectBundle\Controller;

use ProjectBundle\Controller\DefaultController;
use ProjectBundle\Entity\Sound;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ProjectBundle\Entity\Playlist;
use ProjectBundle\Model\AddPlaylistType;
use ProjectBundle\Model\AddSoundType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Route("/playlists/{id}/sounds/add", requirements={"id" = "\d+"}, name="add_sound_to_playlist")
     * @return Response
     */
    public function addSoundAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sound = new Sound();
        $form = $this->createForm(AddSoundType::class, $sound);
        $playlist = $em->getRepository('ProjectBundle:Playlist')->find($id);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {

                // save the proposition
                $em = $this->getDoctrine()->getManager();
                $playlist->addSound($sound);
                $em->persist($sound);
                $em->flush();

                // add a flash message
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Your playlist has been saved!');

                return $this->redirect($this->generateUrl('playlists_list'));
            }
        }

        return $this->render('ProjectBundle:Sounds:add.html.twig', ["form" => $form->createView()]);

    }

    /**
     * @Route("/playlists/delete/{id}", requirements={"id" = "\d+"}, name="delete_playlist")
     * @return Response
     */

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');
        $delete = $repository->find($id);
        $em->remove($delete);
        $em->flush();
        return $this->redirect($this->generateUrl('playlists_list'));
    }

    /**
     * @Route("/playlists/edit/{id}", requirements={"id" = "\d+"}, name="edit_playlist")
     * @return Response
     */

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $playlist = $em->getRepository('ProjectBundle:Playlist')->find($id);
        $form = $this->createForm(AddPlaylistType::class, $playlist);
        if (!$playlist) {
            throw $this->createNotFoundException(
                'No playlist found for id '.$id
            );
        }else{
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
        }
        return $this->render('ProjectBundle:Playlists:add.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/playlists/{id}/sounds", requirements={"id" = "\d+"}, name="playlist_sounds")
     * @return Response
     */
    public function playlistSoundAction($id,Request $request)
    {

        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist')->find($id);

        $sounds = $repository->getSounds();

        return $this->render('ProjectBundle:Sounds:sounds.html.twig', ["sounds" => $sounds]);

    }


}