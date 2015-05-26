<?php

namespace Log210\LivraisonBundle\Controller;

use Log210\LivraisonBundle\Entity\Restaurant;
use Log210\LivraisonBundle\ApiResponse\RestaurantResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Log210\CommonBundle\Controller\BaseController as Controller;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package Log210\LivraisonBundle\Controller
 *
 * @Route("/api")
 */
class ApiController extends Controller
{
    protected function getRepositoryForClass($class)
    {
        return $this->getDoctrine()->getRepository($class);
    }

    /**
     * @return Response the restaurants in json format
     *
     * @Route("/restaurants", name="restaurant_api_get_restaurants")
     * @Method("GET")
     */
    public function getRestaurantsAction()
    {
        $restaurantEntities = $this->getRepositoryForClass('Log210LivraisonBundle:Restaurant')->findAll();
        $restaurantResponses = array();
        foreach ($restaurantEntities as $restaurant) {
            array_push($restaurantResponses, $this->toRestaurantResponse($restaurant));
        }
        $response = $this->jsonResponse(new Response($this->toJson($restaurantResponses)));
        echo '<pre>';
        var_dump($this->fromJson($restaurantResponses[0], 'Log210\LivraisonBundle\ApiResponse\RestaurantResponse'));
        return $response;
    }

    /**
     * @param $id int the id of the restaurant
     * @return Response the restaurant in json format
     *
     * @Route("/restaurants/{id}", name="restaurant_api_get_restaurant")
     * @Method("GET")
     */
    public function getRestaurantAction($id)
    {
        $restaurantService = $this->get("livraisonBundle.restaurantService");

        $restaurant = $restaurantService->getRestaurantById($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $this->toJson($restaurant);

        $response = new Response($jsonContent);
        return $this->jsonResponse($response);
    }

    /**
     * @param Request $request the request
     * @return Response the response
     *
     * @Route("/restaurants", name="restaurant_api_create_restaurant")
     * @Method("POST")
     */
    public function createRestaurantAction(Request $request)
    {
        $restaurantService = $this->get("livraisonBundle.restaurantService");

        $restaurant = $this->fromJson($request->getContent(), 'Log210\LivraisonBundle\Entity\Restaurant');

        $restaurant = $restaurantService->createRestaurant($restaurant);

        $response = new Response('', Response::HTTP_CREATED);
        $response->headers->set('Location', $this->generateUrl('get_restaurant', array(
            'id' => $restaurant->getId()), true));
        return $response;
    }

    /**
     * @param int $id the id of the restaurant
     * @param Request $request the request object
     * @return Response response object
     *
     * @Route("/restaurants/{id}", name="restaurant_api_update_restaurant")
     * @Method("PUT")
     */
    public function updateRestaurantAction($id, Request $request)
    {
        $restaurant = $this->get("livraisonBundle.restaurantService")->getRestaurantById($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $restaurant = $this->fromJson($request->getContent(), 'Log210\LivraisonBundle\Entity\Restaurant');

        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $restaurantService->updateRestaurant($id, $restaurant);
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id int the id of the restaurant
     * @return Response response object
     *
     * @Route("/restaurants/{id}", name="restaurant_api_delete_restaurant")
     * @Method("DELETE")
     */
    public function deleteRestaurantAction($id)
    {
        $restaurant = $this->get("livraisonBundle.restaurantService")->getRestaurantById($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $restaurantService->deleteRestaurant($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }

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

    /**
     * @param Restaurant $restaurantEntity the entity to convert
     * @return RestaurantResponse the restaurant response object
     */
    private function toRestaurantResponse(Restaurant $restaurantEntity) {
        $restaurantResponse = new RestaurantResponse();
        $restaurantResponse->setId($restaurantEntity->getId());
        $restaurantResponse->setName($restaurantEntity->getName());
        $restaurantResponse->setDescription($restaurantEntity->getDescription());
        $restaurantResponse->setAddress($restaurantEntity->getAddress());
        $restaurantResponse->setPhone($restaurantEntity->getPhone());
        $restaurantResponse->setRestaurateur_href($this->get('router')->generate('api_get_restaurateur', array('id' =>
            $restaurantEntity->getId())));
        return $restaurantResponse;
    }

}