<?php

namespace Log210\LivraisonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Log210\LivraisonBundle\Entity\Restaurant;
use Log210\LivraisonBundle\Entity\Restaurateur;

class DashboardController extends Controller
{
    /**
     * @Route("/")
     * @Template("Log210LivraisonBundle:Dashboard:index.html.twig")
     */
    public function indexAction(Request $request)
    {
    	$restaurant = new Restaurant();
    	$restaurateur = new Restaurateur();
    	$theMessage = "patate";
        return ['message' => $theMessage];
    }
}
