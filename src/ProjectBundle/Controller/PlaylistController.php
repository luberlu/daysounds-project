<?php

namespace ProjectBundle\Controller;

use ProjectBundle\Controller\DefaultController;
use ProjectBundle\Entity\Sound;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ProjectBundle\Entity\Playlist;
use ProjectUserBundle\Entity\User;
use ProjectBundle\Model\AddPlaylistType;
use ProjectBundle\Model\AddSoundType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaylistController extends DefaultController
{
    private $datas = [];
    /**
     * @Route("/users/{slug_username}/playlists/add", name="add_playlist")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addPlaylistAction(Request $request,$slug_username)
    {
        $playlist = new Playlist();
        $form = $this->createForm(AddPlaylistType::class, $playlist);
        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findBySlug($slug_username);

        if(!count($user)){
            return $this->redirect($this->generateUrl('404'));
        }

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userSet = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBy(array('slug'=>$slug_username));
                // save the proposition
                $em = $this->getDoctrine()->getManager();
                $playlist->setIsDayli(false);
                $playlist->setDateAdd(new \DateTime());
                $playlist->setUser($userSet);
                $playlist->setIsDefault(false);

                $em->persist($playlist);
                $em->flush();

                // add a flash message
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Your playlist has been saved!');

                return $this->redirect($this->generateUrl('user-profil',array('slug_username'=>$slug_username)));
            }
        }

        return $this->render('ProjectBundle:Playlists:add.html.twig', ["form" => $form->createView()]);

    }

    /**
     * @Route("/users/{slug_username}/playlists/delete/{id}", requirements={"id" = "\d+"}, name="delete_playlist")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function deletePlaylistAction($id,$slug_username)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');
        $delete = $repository->find($id);
        $em->remove($delete);
        $em->flush();
        return $this->redirect($this->generateUrl('user-profil',array('slug_username'=>$slug_username)));
    }

    /**
     * @Route("/users/{slug_username}/playlists/edit/{id}", requirements={"id" = "\d+"}, name="edit_playlist")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */


    public function editPlaylistAction($id, Request $request, $slug_username)
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

                    return $this->redirect($this->generateUrl('user-profil',array('slug_username'=>$slug_username)));
                }
            }
        }
        return $this->render('ProjectBundle:Playlists:add.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/profil/playlists/{id}/sounds", requirements={"id" = "\d+"}, name="playlist_sounds")
     * @return Response
     */
    public function playlistSoundAction($id)
    {

        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist')->find($id);

        $sounds = $repository->getSounds();
        $repository2 = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');

        $listePlaylist = $repository2->findAll();

        return $this->render('ProjectBundle:Default:playlists.html.twig', ["listePlaylist"=>$listePlaylist,"sounds" => $sounds,"idPlaylist"=>$id]);

    }

    /**
     * @Route("/profil/playlists/{id}/sound/add", requirements={"id" = "\d+"}, name="add_sound_to_playlist")
     * @return Response
     */
    public function addSoundAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sound = new Sound();
        $form = $this->createForm(AddSoundType::class, $sound);
        $playlist = $em->getRepository('ProjectBundle:Playlist')->find($id);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                // save the proposition
                $soundTofind=$sound->getLink();
                $soundIsFind=$this->getDoctrine()->getRepository('ProjectBundle:Sound')->findBy(array('link'=>$soundTofind));
                $soundToReplace = $this->getDoctrine()->getRepository('ProjectBundle:Sound')->findoneBy(array('link'=>$soundTofind));

                if(count($soundIsFind)){

                    //sound Exist
                    $em = $this->getDoctrine()->getManager();

                    if($playlist->getSounds($soundIsFind)){

                        $this->get('session')
                            ->getFlashBag()
                            ->add('success', 'You already have this sound in this playlist!');

                    } else {

                        $playlist->addSound($soundToReplace);
                        var_dump($soundToReplace->getId());
                        $em->flush();
                    }

                }
                else{
                    //sound do not exist
                    $em = $this->getDoctrine()->getManager();
                    $playlist->addSound($sound);
                    $em->persist($sound);
                    $em->flush();
                }
                // add a flash message
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Your playlist has been saved!');

                return $this->redirectToRoute('playlist_sounds', ['id' => $id]);

            }
        }

        return $this->render('ProjectBundle:Sounds:add.html.twig', ["form" => $form->createView()]);

    }

    /**
     * @Route("/profil/playlists/{id}/sound/{id2}/delete", requirements={"id" = "\d+","id2"="\d+"}, name="delete_sound_from_playlist")
     * @return Response
     */

    public function deleteSoundFromPlaylistAction($id,$id2)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist')->find($id);
        $soundToRemove = $this->getDoctrine()->getRepository('ProjectBundle:Sound')->find($id2);
        $repository->removeSound($soundToRemove);
        $em->flush();
        return $this->redirect($this->generateUrl('playlist_sounds', array('id' => $id)));
    }


}