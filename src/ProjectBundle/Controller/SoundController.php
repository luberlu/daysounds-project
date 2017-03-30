<?php

namespace ProjectBundle\Controller;

use ProjectBundle\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ProjectBundle\Entity\Playlist;
use ProjectBundle\Entity\Sound;
use ProjectBundle\Model\AddSoundType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class SoundController extends DefaultController {

    /**
     * @Route("/playlist/{playlist_id}/add/{sound_id}/{isDayli}", name="add_sound_to_this_playlist", requirements={"sound_id" = "\d+"})
     * @param $playlist_id
     * @param $sound_id
     * @param $isDayli
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addSoundPlaylist($playlist_id, $sound_id, $isDayli){

        if($this->getUser()) {
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            $playlist = $this->getDoctrine()->getRepository("ProjectBundle:Playlist")->findOneBy(array("id" =>$playlist_id, "user" =>$user));
            $playlist_default = $this->getDoctrine()->getRepository("ProjectBundle:Playlist")
                ->findOneBy(array("user" => $user, "isDefault" => true));
            $sound = $this->getDoctrine()->getRepository("ProjectBundle:Sound")->findOneById($sound_id);

            $list_playlists_for_this_sound = $sound->getPlaylists();

            foreach($list_playlists_for_this_sound as $playlistFound){

                if($playlist->getId() == $playlistFound->getId()){

                    $this->get('session')->getFlashBag()->set('warning',
                        'Sound already in playlist');

                    return $this->redirect($this->generateUrl("playlist_sounds",
                        array("slug_username" => $user->getSlug(), "playlist_slug" => $playlist->getSlug())
                        )
                    );

                }
            }

            $playlist->addSound($sound);

            $alreadyInPlaylistDefault = false;

            // check is default playlist as already the sound to add
            foreach($playlist_default->getSounds() as $soundAlready){
                if($soundAlready == $sound){
                    $alreadyInPlaylistDefault = true;
                }
            }

            if(!$alreadyInPlaylistDefault){

                // Check if it come to dayli sounds
                if($isDayli == true){
                    // find Dayliplaylist from user
                    $dayliPlaylistFromUser = $this->getDoctrine()->getRepository("ProjectBundle:Playlist")
                        ->findOneBy(array("user" => $this->getUser(), "isDayli" => true));

                    $dayliPlaylistFromUser->removeSound($sound);

                }

                $playlist_default->addSound($sound);
                $em->persist($playlist_default);
            }

            $em->persist($playlist);
            $em->flush();

            return $this->redirect($this->generateUrl("playlist_sounds",
                array("slug_username" => $user->getSlug(), "playlist_slug" => $playlist->getSlug())
                )
            );
        }

        return $this->redirect($this->generateUrl("home"));

    }

}

