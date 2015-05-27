<?php

namespace Log210\APIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestaurateurController
 * @package Log210\APIBundle\Controller
 */
class RestaurateurController {

    /**
     * @return Response the restaurateurs in json format
     *
     */
    public function getRestaurateursAction()
    {
        return $this->getRepositoryForClass('Log210LivraisonBundle:Restaurateurs')->findAll();
    }

    /**
     * @param $id int the id of the restaurateur
     * @return Response the restaurateur in json format
     *
     * @Route("/restaurateurs/{id}", name="api_get_restaurateur")
     * @Method("GET")
     */
    public function getRestaurateurAction($id) {
        return $this->jsonResponse(new Response($this->toJson($this
            ->getRepositoryForClass('Log210LivraisonBundle:Restaurateur')->findAll())));
    }
}