<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Message\Response\RestaurateurResponse;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Restaurateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestaurateurController
 * @package Log210\APIBundle\Controller
 * @Route("/restaurateurs")
 */
class RestaurateurController extends BaseController {
    /**
     * @Route("", name="restaurateur_api_create_restaurateur")
     * @Method("POST")
     */
    public function createRestaurateurAction(Request $request) {
        $restaurateurRequest = $this->fromJson($request->getContent(), 'Log210');
    }

    /**
     * @param $id the id of the restaurateur
     * @return Response response
     *
     * @Route("/{id}", name="restaurateur_api_get_restaurateur")
     * @Method("GET")
     */
    public function getRestaurateurAction($id) {
        $restaurateurEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

        if (is_null($restaurateurEntity)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $restaurateur = $this->toRestaurateurResponse($restaurateurEntity);
        return $this->jsonResponse(new Response($this->toJson($restaurateur)));
    }

    /**
     * @param $id the id of the restaurateur
     * @return Response response
     *
     * @Route("/{id}/restaurants", name="restaurateur_api_get_restaurateur_restaurants")
     * @Method("GET")
     */
    public function getRestaurateurRestaurantsAction($id) {

    }

    /**
     * @param Restaurateur $restaurateurEntity
     * @return RestaurateurResponse restaurateurResponse
     */
    private function toRestaurateurResponse(Restaurateur $restaurateurEntity) {
        $restaurateurResponse = new RestaurateurResponse();
        $restaurateurResponse->setId($restaurateurEntity->getId());
        $restaurateurResponse->setName($restaurateurEntity->getName());
        $restaurateurResponse->setDescription($restaurateurEntity->getDescription());
        $restaurateurResponse->setRestaurants_href($this->get('router')->generate(
            'restaurateur_api_get_restaurateur_restaurants', array('id' => $restaurateurEntity->getId())));
        $restaurateurResponse->setSelf_href($this->get('router')->generate(
            'restaurateur_api_get_restaurateur', array('id' => $restaurateurEntity->getId())));
        return $restaurateurResponse;
    }
}