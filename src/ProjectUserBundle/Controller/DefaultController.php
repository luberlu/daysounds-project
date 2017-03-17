<?php

namespace ProjectUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/user")
     */
    public function indexAction()
    {
        return $this->render('ProjectUserBundle:Default:index.html.twig');
    }
}
