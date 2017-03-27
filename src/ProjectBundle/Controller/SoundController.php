<?php

namespace ProjectBundle\Controller;

use ProjectBundle\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ProjectBundle\Entity\Playlist;
use ProjectBundle\Entity\Sound;
use ProjectBundle\Model\AddSoundType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class SoundController extends DefaultController {

    /**
     * @Route("/playlist/{playlist_id}/add/{sound_id}", name="add_sound_to_this_playlist", requirements={"sound_id" = "\d+"})
     * @param $playlist_id
     * @param $sound_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addSoundPlaylist($playlist_id, $sound_id){

        if($this->getUser()) {
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            $playlist = $this->getDoctrine()->getRepository("ProjectBundle:Playlist")->findOneById($playlist_id);
            $sound = $this->getDoctrine()->getRepository("ProjectBundle:Sound")->findOneById($sound_id);
            $playlist->addSound($sound);
            $em->persist($playlist);
            $em->flush();

            $this->redirect($this->generateUrl("playlist_sounds",
                array("slug_username" => $user, "playlist_slug" => $playlist->getSlug())
            )
            );
        }

        return $this->redirect($this->generateUrl("home"));

    }

}

