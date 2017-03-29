<?php

namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Player;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class PlayerController extends DefaultController
{

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