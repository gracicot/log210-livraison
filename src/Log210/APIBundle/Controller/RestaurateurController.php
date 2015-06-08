<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Mapper\RestaurantMapper;
use Log210\APIBundle\Mapper\RestaurateurMapper;
use Log210\CommonBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RestaurateurController
 * @package Log210\APIBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/restaurateurs")
 */
class RestaurateurController extends BaseController {

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="restaurateur_api_create")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createAction(Request $request) {
        $restaurateurRequest = $this->fromJson($request->getContent(),
            'Log210\APIBundle\Message\Request\RestaurateurRequest');

        $restaurateurEntity = RestaurateurMapper::toRestaurateur($restaurateurRequest);
        $this->getEntityManager()->persist($restaurateurEntity);
        $this->getEntityManager()->flush();

        $response = new Response('', Response::HTTP_CREATED);
        $response->headers->set('Location', $this->generateUrl('restaurateur_api_get', array('id' => $restaurateurEntity
            ->getId()), true));
        return $response;
    }

    /**
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="restaurateur_api_get_all")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllAction() {
        $restaurateursEntities = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')
            ->findAll();

        $restaurateursResponses = array();
        foreach ($restaurateursEntities as $restaurateurEntity)
            array_push($restaurateursResponses, RestaurateurMapper::toRestaurateurResponse($restaurateurEntity));

        return $this->jsonResponse(new Response($this->toJson($restaurateursResponses)));
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurateur_api_get")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAction($id) {
        $restaurateurEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

        if (is_null($restaurateurEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurateur = RestaurateurMapper::toRestaurateurResponse($restaurateurEntity);
        return $this->jsonResponse(new Response($this->toJson($restaurateur)));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurateur_api_update")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("PUT")
     */
    public function updateAction($id, Request $request) {
        $restaurateurEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

        if (is_null($restaurateurEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurateurRequest = $this->fromJson($request->getContent(),
            'Log210\APIBundle\Message\Request\RestaurateurRequest');

        $restaurateurEntity->setName($restaurateurRequest->getName());
        $restaurateurEntity->setDescription($restaurateurRequest->getDescription());

        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurateur_api_delete")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deleteAction($id) {
        $restaurateurEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

        if (is_null($restaurateurEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        foreach ($restaurateurEntity->getRestaurants() as $restaurantEntity) {
            $restaurantEntity->setRestaurateur(null);
        }

        $this->getEntityManager()->remove($restaurateurEntity);
        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id int
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurants", name="restaurateur_api_get_all_restaurants")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllRestaurantsAction($id) {
        $restaurateurEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

        if (is_null($restaurateurEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurantResponses = array();
        foreach ($restaurateurEntity->getRestaurants() as $restaurantEntity)
            array_push($restaurantResponses, RestaurantMapper::toRestaurantResponse($restaurantEntity));

        return new Response($this->toJson($restaurantResponses), Response::HTTP_OK, array(
            "Content-Type" => "application/json"));
    }
}
