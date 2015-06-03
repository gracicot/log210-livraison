<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Mapper\RestaurantMapper;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RestaurantController
 * @package Log210\LivraisonBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/restaurants")
 */
class RestaurantController extends BaseController {

    /**
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="restaurant_api_get_restaurants")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getRestaurantsAction()
    {
        $restaurantEntities = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->findAll();
        $restaurantResponses = array();
        foreach ($restaurantEntities as $restaurant) {
            array_push($restaurantResponses, RestaurantMapper::toRestaurantResponse($restaurant));
        }
        return $this->jsonResponse(new Response($this->toJson($restaurantResponses)));
    }

    /**
     * @param $id int
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurant_api_get_restaurant")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getRestaurantAction($id)
    {
        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);

        if (is_null($restaurantEntity)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $restaurantResponse = RestaurantMapper::toRestaurantResponse($restaurantEntity);

        $response = new Response($this->toJson($restaurantResponse));
        return $this->jsonResponse($response);
    }

    /**
     * @param $request Request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="restaurant_api_create_restaurant")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
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
     * @param $id int
     * @param $request Request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurant_api_update_restaurant")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("PUT")
     */
    public function updateRestaurantAction($id, Request $request)
    {
        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);

        if (is_null($restaurantEntity)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $restaurantRequest = $this->fromJson($request->getContent(), 'Log210\APIBundle\Message\Request\RestaurantRequest');

        if (!is_null($restaurantRequest->getRestaurateur())) {
            $restaurateur = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')->find(
                $restaurantRequest->getRestaurateur());
            if (is_null($restaurateur)) {
                return new Response('{"error":"Restaurateur not found"}', Response::HTTP_BAD_REQUEST);
            }
            $restaurantEntity->setRestaurateur($restaurateur);
        }
        $restaurantEntity->setName($restaurantRequest->getName());
        $restaurantEntity->setDescription($restaurantRequest->getDescription());
        $restaurantEntity->setAddress($restaurantRequest->getAddress());
        $restaurantEntity->setPhone($restaurantRequest->getPhone());

        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id int
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurant_api_delete_restaurant")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deleteRestaurantAction($id)
    {
        $restaurant = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);

        if (is_null($restaurant)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->getEntityManager()->remove($restaurant);
        $this->getEntityManager()->flush();
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id int
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurateur", name="restaurant_api_get_restaurant_restaurateur")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getRestaurantRestaurateurAction($id) {
        $restaurant = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
        if (is_null($restaurant) || is_null($restaurant->getRestaurateur())) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        return $this->forward('Log210APIBundle:Restaurateur:getRestaurateur', array('id' => $restaurant
            ->getRestaurateur()->getId()));
    }

    /**
     * @param $id int
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurateur", name="restaurant_api_delete_restaurant_restaurateur_link")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deleteRestaurantRestaurateurLinkAction($id) {
        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
        if (is_null($restaurantEntity)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $restaurantEntity->setRestaurateur(null);
        $this->getEntityManager()->flush();
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}