<?php

namespace Livraison\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template("LivraisonUserBundle:Default:index.html.twig")
     */
    public function indexAction($name)
    {
        return ['name' => $name];
    }
}
