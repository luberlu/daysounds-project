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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(){

        if($this->getUser()){
            return $this->redirect($this->generateUrl('stream'));
        }
        return $this->render('ProjectBundle:Default:index.html.twig');
    }

    /**
     * @Route("/stream", name="stream")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function streamAction()
    {
        if($this->getUser()) {
            $user = $this->getUser();
            $this->datas["slugUserName"] = $user->getSlug();

            // Block New Users
            $this->datas["newUsers"] = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findNewUsers();
            $this->datas["title"] = "Homepage";

            // Dayliplaylist with cron example
            $this->cronToDayliPlaylist();
            $dayliPlaylist = $this->getDoctrine()
                ->getRepository('ProjectBundle:Playlist')->findOneBy(array("user" => $user, "isDayli" => true));
            $this->datas["dayliSounds"] = $dayliPlaylist->getSounds();

            $this->datas["listPlaylists"] = $this->getDoctrine()
                ->getRepository('ProjectBundle:Playlist')->findBy(array(
                    "user" => $this->getUser(), "isDayli" => false, "isDefault" => false));

            // Latest Playlists added
            $this->datas['latestPlaylists'] = $this->getDoctrine()
                ->getRepository('ProjectBundle:Playlist')->findLatestPlaylists();

            return $this->render('ProjectBundle:Default:index.html.twig', array("datas" => $this->datas));
        }

        else return $this->redirect($this->generateUrl('home'));
    }

    public function followOrNot($user){

        $this->datas['tofollow'] = true;
        $follows = $user->getRelationUser();

        if(count($follows)){
            foreach ( $follows as $follow ) {
                if ( $follow == $this->getUser() ) {
                    $this->datas['tofollow'] = false;
                }
            }
        }

        return true;
    }

    /**
     * @Route("/users/{slug_username}", name="user-profil")
     * @param $slug_username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderDayliAction($slug_username)
    {
        if($this->getUser()) {

            $this->datas["actions"] = false;

            $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findOneBySlug($slug_username);

            if ($this->getUser() === $user) {
                $this->datas["actions"] = true;
            }

            $this->datas["title"] = $user->getUsername() . " profile";
            $this->datas["slugUserName"] = $this->getUser()->getSlug();
            $this->datas["user"] = $user;

            $this->followOrNot($user);

            if (!count($user)) {
                return $this->redirect($this->generateUrl('404'));
            }

            $this->datas["listePlaylist"] = $this->getDoctrine()
                ->getRepository('ProjectBundle:Playlist')->findByUser($user);

            return $this->render('ProjectBundle:Default:profil.html.twig',
                ["datas" => $this->datas]);
        }
        else return $this->redirect($this->generateUrl('home'));
    }


    // Afficher le profil d'un utilisateur avec toutes ses playlists

    /**
     * @Route("/users", name="users")
     */

    public function renderUersAction()
    {

        $user = $this->getDoctrine()->getRepository('ProjectUserBundle:User')->findall();
        $this->datas["title"] = "daysounds users";
        $this->datas["listeUsers"] =$user;

        return $this->render('ProjectBundle:Default:users.html.twig', array("datas" => $this->datas));

    }
    
    /**
     * @Route("/page-not-found", name="404")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function render404Action()
    {
        return $this->render('ProjectBundle:Default:404.html.twig');
    }

    /*private function findSoundToAdd(){

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $maximum = 10;

        $query = $em->createQueryBuilder('p')
                    ->innerJoin ('p.description', 'pi')
                    ->innerJoin('p.categories', 'pc')
                    ->andWhere('pc.tag = :cat')
                    ->andWhere('pi.langue = :lang')
                    ->setParameters(array('lang'=> $lang,'cat'=> $cat, "random" => rand(0, $maximum)));

        $resultQuery = $query->getResult();

        $query = $em->createQuery(
            'SELECT p
            FROM ProjectBundle:Sounds p
            WHERE p.id <= :random
            WHERE p.user = :user
            ORDER BY p.id ASC'
        )->setParameter('rand' => rand(0, $max));

        $products = $query->getResult();

        return false;
    }*/

    // retrieve 1 sound per follow to dayliPlaylist
    private function cronToDayliPlaylist(){

        //$soundsPerFollows = [];
        $em = $this->getDoctrine()->getManager();

        $his_dayli_sound_playlist = $this->getDoctrine()->getRepository("ProjectBundle:Playlist")
            ->findOneBy(array("user" => $this->getUser(), "isDayli" => true));

        $follows = $this->getUser()->getRelationUserOf();

        foreach($follows as $follow){

            $playlist_all_sounds = $this->getDoctrine()->getRepository("ProjectBundle:Playlist")
                ->findOneBy(array("user" => $follow, "isDefault" => true));

            $one_sound = $playlist_all_sounds->getSounds()->first();
            $alreadyInPlaylistDefault = false;

            $soundToAddToPlaylist = $this->findSoundToAdd();
            // check is default playlist as already the sound to add
            foreach($soundsPlaylist as $soundAlready){
                if($soundAlready == $one_sound){
                    $alreadyInPlaylistDefault = true;
                }
            }

            if(!$alreadyInPlaylistDefault){
                $his_dayli_sound_playlist->addSound($one_sound);
                $em->persist($his_dayli_sound_playlist);
            }

        }

        $em->flush();

    }


}
