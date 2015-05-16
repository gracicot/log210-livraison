<?php

namespace Log210\LivraisonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    /**
     * @Route("/")
     * @Template("Log210LivraisonBundle:Dashboard:index.html.twig")
     */
    public function indexAction(Request $request)
    {
    	$theMessage = "patate";
        return ['message' => $theMessage];
    }

    /**
     * @Route("/courges")
     * @Template("Log210LivraisonBundle:Dashboard:index.html.twig")
     */
    public function courgeAction(Request $request)
    {
    	$theMessage = "patate";
        return ['message' => $theMessage];
    }
}
