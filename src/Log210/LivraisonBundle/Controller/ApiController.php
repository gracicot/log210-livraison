<?php

namespace Log210\LivraisonBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ApiController
 * @package Log210\LivraisonBundle\Controller
 *
 * @Route("/api")
 */
class ApiController extends Controller
{

    /**
     * @return Response the restaurants in json format
     *
     * @Route("/restaurants", name="get_restaurants")
     * @Method("GET")
     */
    public function getRestaurantsAction()
    {
        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $restaurants = $restaurantService->getAllRestaurants();

        $jsonContent = $serializer->serialize($restaurants, 'json');

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @param $id int the id of the restaurant
     * @return Response the restaurant in json format
     *
     * @Route("/restaurants/{id}", name="get_restaurant")
     * @Method("GET")
     */
    public function getRestaurantAction($id)
    {
        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $restaurant = $restaurantService->getRestaurantById($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $serializer->serialize($restaurant, 'json');

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @param Request $request the request
     * @return Response the response
     *
     * @Route("/restaurants", name="create_restaurant")
     * @Method("POST")
     */
    public function createRestaurantAction(Request $request) {
        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $restaurant = $serializer->deserialize($request->getContent(), 'Log210\LivraisonBundle\Entity\Restaurant',
            'json');

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
     * @Route("/restaurants/{id}", name="update_restaurant")
     * @Method("PUT")
     */
    public function updateRestaurantAction($id, Request $request)
    {
        $restaurant = $this->get("livraisonBundle.restaurantService")->getRestaurantById($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $restaurant = $serializer->deserialize($request->getContent(), 'Log210\LivraisonBundle\Entity\Restaurant',
            'json');

        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $restaurantService->updateRestaurant($id, $restaurant);
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id int the id of the restaurant
     * @return Response response object
     *
     * @Route("/restaurants/{id}", name="delete_restaurant")
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

}