<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Mapper\RestaurantMapper;
use Log210\APIBundle\Mapper\RestaurateurMapper;
use Log210\APIBundle\Message\Request\RestaurateurRequest;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Restaurateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
        $restaurateurRequest = $this->convertRestaurateurRequest($request->getContent());

        $restaurateurEntity = RestaurateurMapper::toRestaurateur($restaurateurRequest);

        $this->persistEntity($restaurateurEntity);

        return $this->createCreatedResponse($this->generateUrl('restaurateur_api_get', array('id' => $restaurateurEntity
            ->getId()), true));
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
        $restaurateurEntity = $this->getRestaurateurById($id);

        if (is_null($restaurateurEntity))
            return $this->createNotFoundResponse();

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
        $restaurateurEntity = $this->getRestaurateurById($id);

        if (is_null($restaurateurEntity))
            return $this->createNotFoundResponse();

        $restaurateurRequest = $this->convertRestaurateurRequest($request->getContent());

        RestaurateurMapper::toRestaurateur($restaurateurRequest, $restaurateurEntity);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurateur_api_delete")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deleteAction($id) {
        $restaurateurEntity = $this->getRestaurateurById($id);

        if (is_null($restaurateurEntity))
            return $this->createNotFoundResponse();

        foreach ($restaurateurEntity->getRestaurants() as $restaurantEntity)
            $restaurantEntity->setRestaurateur(null);

        $this->removeEntity($restaurateurEntity);

        return $this->createNoContentResponse();
    }

    /**
     * @param $id int
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurants", name="restaurateur_api_get_all_restaurants")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllRestaurantsAction($id) {
        $restaurateurEntity = $this->getRestaurateurById($id);

        if (is_null($restaurateurEntity))
            return $this->createNotFoundResponse();

        $restaurantResponses = array();
        foreach ($restaurateurEntity->getRestaurants() as $restaurantEntity)
            array_push($restaurantResponses, RestaurantMapper::toRestaurantResponse($restaurantEntity));

        return $this->jsonResponse(new Response($this->toJson($restaurantResponses)));
    }

    /**
     * @param int $id
     * @return Restaurateur
     */
    private function getRestaurateurById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);
    }

    /**
     * @param object $entity
     */
    private function persistEntity($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param object $entity
     */
    private function removeEntity($entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $newResourceLocation
     * @return Response
     */
    private function createCreatedResponse($newResourceLocation)
    {
        return new Response('', Response::HTTP_CREATED, array('Location' => $newResourceLocation));
    }

    /**
     * @return Response
     */
    private function createNoContentResponse()
    {
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @return Response
     */
    private function createNotFoundResponse()
    {
        return new Response('', Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $json
     * @return RestaurateurRequest
     */
    private function convertRestaurateurRequest($json)
    {
        return $this->fromJson($json,
            'Log210\APIBundle\Message\Request\RestaurateurRequest');
    }
}
