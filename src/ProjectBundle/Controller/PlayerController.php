<?php

namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Player;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class PlayerController extends DefaultController
{

    // Cette méthode n'est accessible que pour nous, pour remplir notre base

    /**
     * @Route("/create-players")
     */
    public function addPlayersAction(Request $request)
    {
        $players = array(
            array("soundcloud", "soundcloud.com"),
            array("youtube", "youtube.com")
        );

        if ($request->getMethod() == 'GET') {
            $em = $this->getDoctrine()->getManager();

            // now if already exist
            $actualPlayers = $this->getDoctrine()->getRepository("ProjectBundle:Player")->findAll();

            if(count($actualPlayers) == 0) {

                foreach ( $players as $player ) {

                    $playerInst = new Player();
                    $playerInst->setName( $player[0] );
                    $playerInst->setLinkExample( $player[1] );

                    $em->persist( $playerInst );
                }

                $em->flush();

                $response = new Response(
                    'Push des players fait avec succés !',
                    Response::HTTP_OK,
                    array( 'content-type' => 'text/html' )
                );

                $response->prepare( $request );
                $response->send();

            }

        }
    }

    // test Input

    /**
     * @Route("/test-link-sound")
     */
    public function testLinkAddSoundAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $variable = $request->request->get('link');

            $em = $this->getDoctrine()->getManager();
            $players = $em->getRepository('ProjectBundle:Player');

            $result = $players->listAndTestLinkPlayers($variable);

            return new JsonResponse($result);
        }

        return false;
    }

}