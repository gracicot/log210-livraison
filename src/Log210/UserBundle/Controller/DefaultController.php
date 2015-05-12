<?php

namespace Log210\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template("Log210UserBundle:Default:index.html.twig")
     */
    public function indexAction($name)
    {
        return ['name' => $name];
    }
}
