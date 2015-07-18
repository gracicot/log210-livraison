<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Mapper\RestaurateurMapper;
use Log210\APIBundle\Message\Request\RestaurateurRequest;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Restaurateur;
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
        $restaurateurRequest = $this->convertRestaurateurRequest($request->getContent());

        $restaurateurEntity = RestaurateurMapper::toRestaurateur($restaurateurRequest);

        $this->persistEntity($restaurateurEntity);

        $response = new Response("", Response::HTTP_CREATED, [
            "Location" => $this->generateUrl("restaurateur_api_get", [
                "id" => $restaurateurEntity->getId()
            ], true),
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Restaurateur:restaurateur.json.twig", [
            "restaurateur" => $restaurateurEntity
        ], $response);
    }

    /**
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="restaurateur_api_get_all")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllAction() {
        $restaurateurEntities = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')
            ->findAll();

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Restaurateur:restaurateurs.json.twig", [
            "restaurateurs" => $restaurateurEntities
        ], $response);
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
            return new Response("", Response::HTTP_NOT_FOUND);

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Restaurateur:restaurateur.json.twig", [
            "restaurateur" => $restaurateurEntity
        ], $response);
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
            return new Response("", Response::HTTP_NOT_FOUND);

        $restaurateurRequest = $this->convertRestaurateurRequest($request->getContent());

        RestaurateurMapper::toRestaurateur($restaurateurRequest, $restaurateurEntity);

        $this->getEntityManager()->flush();

        return new Response("", Response::HTTP_NO_CONTENT);
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
            return new Response("", Response::HTTP_NOT_FOUND);

        foreach ($restaurateurEntity->getRestaurants() as $restaurantEntity)
            $restaurantEntity->setRestaurateur(null);

        $this->removeEntity($restaurateurEntity);

        return new Response("", Response::HTTP_NO_CONTENT);
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
            return new Response("", Response::HTTP_NOT_FOUND);

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Restaurant:restaurants.json.twig", [
            "restaurants" => $restaurateurEntity->getRestaurants()
        ], $response);
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
     * @param $json
     * @return RestaurateurRequest
     */
    private function convertRestaurateurRequest($json)
    {
        return $this->fromJson($json,
            'Log210\APIBundle\Message\Request\RestaurateurRequest');
    }
}
