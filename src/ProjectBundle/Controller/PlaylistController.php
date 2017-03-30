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
     * @param $slug_username
     * @return bool|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loadDatas($slug_username){

        if($this->getUser()) {

            $this->datas["actions"] = false;
            $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBySlug($slug_username);

            if ($this->getUser() === $user) {
                $this->datas["actions"] = true;
            }

            $this->datas["title"] = $user->getUsername() . " profile - Add playlist";
            $this->datas["slugUserName"] = $this->getUser()->getSlug();
            $this->datas["user"] = $user;

            $this->followOrNot($user);

            if (!count($user)) {
                return $this->redirect($this->generateUrl('404'));
            }

            $this->datas["listePlaylist"] = $this->getDoctrine()
                ->getRepository('ProjectBundle:Playlist')->findByUser($user);

            return true;

        }

        return $this->redirect($this->generateUrl('home'));

    }

    public function followOrNot($user){

        $this->datas['tofollow'] = 1;

        $follows = $user->getRelationUser();

        if(count($follows)){
            foreach ( $follows as $follow ) {
                if ( $follow == $this->getUser() ) {
                    $this->datas['tofollow'] = 0;
                }
            }
        }

        return true;
    }


    /**
     * @Route("/users/{slug_username}/playlists/add", name="add_playlist")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addPlaylistAction(Request $request,$slug_username)
    {
        $this->loadDatas($slug_username);

        if(!$this->datas["actions"])
            return $this->redirect($this->generateUrl('user-profil', array("slug_username" => $slug_username)));

        $playlist = new Playlist();
        $form = $this->createForm(AddPlaylistType::class, $playlist);

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
                    ->add('success', 'New playlist has been saved!');

                return $this->redirect(
                    $this->generateUrl(
                        'add_sound_to_playlist',
                        array('slug_username' => $slug_username,
                              'playlist_slug' => $playlist->getSlug()
                        )
                    )
                );
            }
        }

        $this->datas["isFirst"] = true;

        $this->loadDatas($slug_username);
        $this->datas["form"] = $form->createView();

        return $this->render('ProjectBundle:Playlists:add.html.twig', ["datas" => $this->datas]);

    }

    /**
     * @Route("/users/{slug_username}/playlists/delete/{id}", requirements={"id" = "\d+"}, name="delete_playlist")
     * @param $id
     * @param $slug_username
     * @return Response
     */

    public function deletePlaylistAction($id, $slug_username)
    {

        $this->loadDatas($slug_username);

        if(!$this->datas["actions"])
            return $this->redirect($this->generateUrl('user-profil', array("slug_username" => $slug_username)));

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('ProjectBundle:Playlist');
        $delete = $repository->find($id);
        $em->remove($delete);
        $em->flush();

        // add a flash message
        $this->get('session')
             ->getFlashBag()
             ->add('success', 'Your playlist has been deleted !');

        return $this->redirect($this->generateUrl('user-profil',array('slug_username'=>$slug_username)));
    }

    /**
     * @Route("/users/{slug_username}/playlists/{playlist_slug}/edit", requirements={"id" = "\d+"}, name="edit_playlist")
     * @param $playlist_slug
     * @param Request $request
     * @param $slug_username
     *
     * @return Response
     */
    public function editPlaylistAction($playlist_slug, Request $request, $slug_username)
    {
        $this->loadDatas($slug_username);

        if(!$this->datas["actions"])
            return $this->redirect($this->generateUrl('user-profil', array("slug_username" => $slug_username)       ));

        $em = $this->getDoctrine()->getManager();
        $playlist = $em->getRepository('ProjectBundle:Playlist')
            ->findOneBy(array("playlist_slug" => $playlist_slug, "slug_username" => $slug_username));

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

        $this->loadDatas($slug_username);

        return $this->render('ProjectBundle:Playlists:add.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/users/{slug_username}/playlists/{playlist_slug}", name="playlist_sounds")
     * @param $slug_username
     * @param $playlist_slug
     * @return Response
     */
    public function playlistSoundAction($slug_username, $playlist_slug)
    {
        $this->loadDatas($slug_username);

        $this->datas["user"] = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBySlug($slug_username);

        $this->datas["playlist"] = $this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findOneBy(array("slug" =>$playlist_slug, "user" => $this->datas["user"]));

        $this->datas["block_add_sound"] = ($this->datas["playlist"]->getName() == "All sounds") ? true : false;

        $this->datas["sounds"] = $this->datas["playlist"]->getSounds();

        $this->datas["listPlaylists"] = $this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findBy(array(
                "user" => $this->getUser(), "isDayli" => false, "isDefault" => false));

        return $this->render('ProjectBundle:Default:playlist.html.twig', ["datas" => $this->datas]);

    }

    /**
     * @Route("/users/{slug_username}/playlists/{playlist_slug}/sound/add", name="add_sound_to_playlist")
     * @param $slug_username
     * @param $playlist_slug
     * @param Request $request
     * @return Response
     */
    public function addSoundAction($slug_username, $playlist_slug, Request $request)
    {
        $this->loadDatas($slug_username);

        if(!$this->datas["actions"])
            return $this->redirect($this->generateUrl('user-profil', array("slug_username" => $slug_username)));

        $em = $this->getDoctrine()->getManager();
        $sound = new Sound();
        $form = $this->createForm(AddSoundType::class, $sound);


        $playlist = $em->getRepository('ProjectBundle:Playlist')
            ->findOneBy(array("slug" => $playlist_slug, "user" => $this->getUser()));

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                // save the proposition

                $soundsList = [];

                $linkSearch = $sound->getLink();
                $listPlaylistsFromUser = $this->getDoctrine()->getRepository('ProjectBundle:Playlist')
                    ->findBy(array("user" => $this->getUser()));

                foreach ($listPlaylistsFromUser as $key => $playlistFound) {
                    $soundsList[$key] = $playlistFound->getSounds();
                }

                $testIfExist = false;

                foreach ($soundsList as $soundKeyList){
                    foreach ($soundKeyList as $soundtest) {
                        if ($soundtest->getLink() == $linkSearch) {
                            $testIfExist = true;
                        }
                    }
                }

                // check if Already Exist
                if($testIfExist){

                    $this->get('session')
                         ->getFlashBag()
                         ->add('warning', 'You already have this sound in your sounds !');

                    return $this->redirect($this->generateUrl("playlist_sounds",
                        array("slug_username" => $slug_username, "playlist_slug" => $playlist_slug)
                        )
                    );

                } else {

                    // sound do not exist, add is possible
                    $playlist->addSound($sound);
                    // add sound to "All sounds" Playlist
                    $allSounds = $this->getDoctrine()->getRepository('ProjectBundle:Playlist')->findOneBy(array('isDefault'=> true, 'user' => $this->getUser()));
                    $allSounds->addSound($sound);

                    $em->persist($sound);
                    $em->flush();

                    // add a flash message
                    $this->get('session')
                        ->getFlashBag()
                        ->add('success', 'Your sound has been saved in this playlist !');

                    return $this->redirectToRoute('playlist_sounds',
                        ['slug_username' => $slug_username, 'playlist_slug' => $playlist_slug]);

                }

            }
        }

        $this->loadDatas($slug_username);
        $this->datas["form"] = $form->createView();

        return $this->render('ProjectBundle:Sounds:add.html.twig', ["datas" => $this->datas]);

    }

    /**
     * @Route("/playlist/{id_playlist}/sound_to_delete/{id_sound}", requirements={"id_playlist" = "\d+","id_sound"="\d+"}, name="delete_sound_from_playlist")
     * @param $id_playlist
     * @param $id_sound
     *
     * @return Response
     */

    public function deleteSoundFromPlaylistAction($id_playlist, $id_sound)
    {
        $em = $this->getDoctrine()->getManager();

        $playlist = $this->getDoctrine()->getRepository('ProjectBundle:Playlist')->findOneById(intval($id_playlist));
        $soundToRemove = $this->getDoctrine()->getRepository('ProjectBundle:Sound')->findOneById(intval($id_sound));

        if(count($playlist) && count($soundToRemove)) {

            // mettre l'id du son dans la table sound_trashs si pas deja dedans
            // toutes les sons à la poubelle de l'user

            $thisSoundTrashsUser = $this->getUser()->getTrashs();

            foreach ($thisSoundTrashsUser as $soundTrashed) {

                if ($soundTrashed != $soundToRemove) {
                    $soundToRemove->addTrash($this->getUser());
                    break;
                }
            }

            if ( $playlist->getIsDefault() ) {

                $allPlaylistsFromUser = $this->getDoctrine()
                                             ->getRepository( 'ProjectBundle:Playlist' )
                    ->findBy( array( "user" => $this->getUser() ) );

                foreach ( $allPlaylistsFromUser as $playlistFound ) {

                    $listSounds = $playlistFound->getSounds();

                    foreach ( $listSounds as $sound ) {

                        if ( $sound == $soundToRemove ) {

                            $playlistFound->removeSound( $soundToRemove );

                        }

                    }

                }

            } else {

                $playlist->removeSound( $soundToRemove );
            }

            $em->flush();

            // add a flash message
            $this->get('session')
                 ->getFlashBag()
                 ->add('success', 'Sound has been deleted to ' . $playlist->getName());

            return $this->redirect($this->generateUrl('playlist_sounds',
                array('slug_username' => $this->getUser()->getSlug(), 'playlist_slug' => $playlist->getSlug())));

        }

        $this->get('session')
             ->getFlashBag()
             ->add('warning', 'Operation not possible !');

        return $this->redirect($this->generateUrl("stream"));

    }


}