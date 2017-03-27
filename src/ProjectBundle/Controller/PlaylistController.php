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

            if (!count($user)) {
                return $this->redirect($this->generateUrl('404'));
            }

            $this->datas["listePlaylist"] = $this->getDoctrine()
                ->getRepository('ProjectBundle:Playlist')->findByUser($user);

            return true;

        }

        return $this->redirect($this->generateUrl('home'));

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
                    ->add('success', 'Your playlist has been saved!');

                //return $this->redirect($this->generateUrl('user-profil',array('slug_username'=>$slug_username)));
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

        $this->loadDatas($slug_username);
        $this->datas["form"] = $form->createView();

        return $this->render('ProjectBundle:Playlists:add.html.twig', ["datas" => $this->datas]);

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

        $this->datas["playlist"] = $this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findOneBySlug($playlist_slug);

        $this->datas["sounds"] = $this->datas["playlist"]->getSounds();

        $this->datas["listPlaylists"] = $this->getDoctrine()
            ->getRepository('ProjectBundle:Playlist')->findByUser($this->datas["user"]);

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


        $playlist = $em->getRepository('ProjectBundle:Playlist')->findOneBySlug($playlist_slug);

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


                    // add sound to "All sounds" Playlist
                    $allSounds = $this->getDoctrine()->getRepository('ProjectBundle:Playlist')->findOneBy(array('isDefault'=> true));
                    $allSounds->addSound($sound);


                    $em->persist($sound);
                    $em->flush();
                }
                // add a flash message
                $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'Your playlist has been saved!');
                
                return $this->redirectToRoute('playlist_sounds',
                    ['slug_username' => $slug_username, 'playlist_slug' => $playlist_slug]);

            }
        }

        $this->loadDatas($slug_username);
        $this->datas["form"] = $form->createView();

        return $this->render('ProjectBundle:Sounds:add.html.twig', ["datas" => $this->datas]);

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