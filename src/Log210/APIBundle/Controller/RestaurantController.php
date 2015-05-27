<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Message\Request\RestaurantRequest;
use Log210\LivraisonBundle\Entity\Restaurant;
use Log210\APIBundle\Message\Response\RestaurantResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Log210\CommonBundle\Controller\BaseController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestaurantController
 * @package Log210\LivraisonBundle\Controller
 * @Route("/restaurants")
 */
class RestaurantController extends Controller
{
    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('Log210LivraisonBundle:Restaurant');
    }

    /**
     * @return Response the restaurants in json format
     *
     * @Route("", name="restaurant_api_get_restaurants")
     * @Method("GET")
     */
    public function getRestaurantsAction()
    {
        $restaurantEntities = $this->getRepository()->findAll();
        $restaurantResponses = array();
        foreach ($restaurantEntities as $restaurant) {
            array_push($restaurantResponses, $this->toRestaurantResponse($restaurant));
        }
        $response = $this->jsonResponse(new Response($this->toJson($restaurantResponses)));
        return $response;
    }

    /**
     * @param $id int the id of the restaurant
     * @return Response the restaurant in json format
     *
     * @Route("/{id}", name="restaurant_api_get_restaurant")
     * @Method("GET")
     */
    public function getRestaurantAction($id)
    {
        $restaurant = $this->getRepository()->find($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $restaurant = $this->toRestaurantResponse($restaurant);

        $jsonContent = $this->toJson($restaurant);

        $response = new Response($jsonContent);
        return $this->jsonResponse($response);
    }

    /**
     * @param Request $request the request
     * @return Response the response
     *
     * @Route("", name="restaurant_api_create_restaurant")
     * @Method("POST")
     */
    public function createRestaurantAction(Request $request)
    {
        $restaurantRequest = $this->fromJson($request->getContent(), 'Log210\APIBundle\Message\Request\RestaurantRequest');

        $restaurant = new Restaurant();
        if (!is_null($restaurantRequest->getRestaurateur())) {
            $restaurateur = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find(
                $restaurantRequest->getRestaurateur());
            if (is_null($restaurateur)) {
                return new Response('{"error":"Restaurateur not found"}', Response::HTTP_BAD_REQUEST);
            }
            $restaurant->setRestaurateur($restaurateur);
        }
        $restaurant->setName($restaurantRequest->getName());
        $restaurant->setDescription($restaurantRequest->getDescription());
        $restaurant->setAddress($restaurantRequest->getAddress());
        $restaurant->setPhone($restaurantRequest->getPhone());

        $this->getEntityManager()->persist($restaurant);
        $this->getEntityManager()->flush();

        $response = new Response('', Response::HTTP_CREATED);
        $response->headers->set('Location', $this->generateUrl('restaurant_api_get_restaurant', array(
            'id' => $restaurant->getId()), true));
        return $response;
    }

    /**
     * @param int $id the id of the restaurant
     * @param Request $request the request object
     * @return Response response object
     *
     * @Route("/{id}", name="restaurant_api_update_restaurant")
     * @Method("PUT")
     */
    public function updateRestaurantAction($id, Request $request)
    {
        $restaurant = $this->getRepository()->find($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $restaurantRequest = $this->fromJson($request->getContent(), 'Log210\APIBundle\Message\Request\RestaurantRequest');

        if (!is_null($restaurantRequest->getRestaurateur())) {
            $restaurateur = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find(
                $restaurantRequest->getRestaurateur());
            if (is_null($restaurateur)) {
                return new Response('{"error":"Restaurateur not found"}', Response::HTTP_BAD_REQUEST);
            }
            $restaurant->setRestaurateur($restaurateur);
        }
        $restaurant->setName($restaurantRequest->getName());
        $restaurant->setDescription($restaurantRequest->getDescription());
        $restaurant->setAddress($restaurantRequest->getAddress());
        $restaurant->setPhone($restaurantRequest->getPhone());

        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id int the id of the restaurant
     * @return Response response object
     *
     * @Route("/{id}", name="restaurant_api_delete_restaurant")
     * @Method("DELETE")
     */
    public function deleteRestaurantAction($id)
    {
        $restaurant = $this->getRepository()->find($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->getEntityManager()->remove($restaurant);
        $this->getEntityManager()->flush();
        return new Response('', Response::HTTP_NO_CONTENT);
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
        if (!is_null($restaurantEntity->getRestaurateur())) {
            $restaurantResponse->setRestaurateur_href($this->get('router')->generate('api_get_restaurateur',
                array('id' => $restaurantEntity->getRestaurateur()->getId())));
        }
        return $restaurantResponse;
    }

}