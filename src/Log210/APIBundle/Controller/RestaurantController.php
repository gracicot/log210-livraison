<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Mapper\MenuMapper;
use Log210\APIBundle\Mapper\RestaurantMapper;
use Log210\APIBundle\Message\Request\MenuRequest;
use Log210\APIBundle\Message\Request\RestaurantRequest;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Menu;
use Log210\LivraisonBundle\Entity\Restaurant;
use Log210\LivraisonBundle\Entity\Restaurateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RestaurantController
 * @package Log210\APIBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/restaurants")
 */
class RestaurantController extends BaseController {

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="restaurant_api_create")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createAction(Request $request) {
        $restaurantRequest = $this->convertRestaurantRequest($request->getContent());

        $restaurantEntity = RestaurantMapper::toRestaurant($restaurantRequest);

        $this->persistEntity($restaurantEntity);

        $response = new Response('', Response::HTTP_CREATED, [
            "Location" => $this->generateUrl('restaurant_api_get', [
                'id' => $restaurantEntity->getId()
            ], true),
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Restaurant:restaurant.json.twig", [
            "restaurant" => $restaurantEntity
        ], $response);
    }

    /**
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="restaurant_api_get_all")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllAction() {
        $restaurantEntities = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->findAll();

        $response = new Response('', Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Restaurant:restaurants.json.twig", [
            "restaurants" => $restaurantEntities
        ], $response);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurant_api_get")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAction($id)
    {
        $restaurantEntity = $this->getRestaurantById($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $response = new Response('', Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Restaurant:restaurant.json.twig", [
            "restaurant" => $restaurantEntity
        ], $response);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurant_api_update")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("PUT")
     */
    public function updateAction($id, Request $request)
    {
        $restaurantEntity = $this->getRestaurantById($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurantRequest = $this->convertRestaurantRequest($request->getContent());

        RestaurantMapper::toRestaurant($restaurantRequest, $restaurantEntity);

        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="restaurant_api_delete")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deleteAction($id)
    {
        $restaurantEntity = $this->getRestaurantById($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        foreach ($restaurantEntity->getMenus() as $menuEntity)
            $menuEntity->setRestaurant(null);
        $this->removeEntity($restaurantEntity);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurateur", name="restaurant_api_link_restaurateur")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function linkRestaurateurAction($id, Request $request)
    {
        $requestBody = json_decode($request->getContent(), true);

        $restaurantEntity = $this->getRestaurantById($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurateurEntity = $this->getRestaurateurById($requestBody["restaurateur-id"]);
        if (is_null($restaurateurEntity)) {
            return new Response('{"error": "Restaurateur not found"}', Response::HTTP_BAD_REQUEST, array(
                "Content-Type" => "application/json"));
        }

        $restaurantEntity->setRestaurateur($restaurateurEntity);

        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurateur", name="restaurant_api_get_restaurateur")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getRestaurateurAction($id) {
        $restaurant = $this->getRestaurantById($id);
        if (is_null($restaurant) || is_null($restaurant->getRestaurateur()))
            return new Response('', Response::HTTP_NOT_FOUND);

        return $this->forward('Log210APIBundle:Restaurateur:get', array('id' => $restaurant->getRestaurateur()
            ->getId()));
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurateur", name="restaurant_api_unlink_restaurateur")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function unlinkRestaurateurAction($id) {
        $restaurantEntity = $this->getRestaurantById($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurantEntity->setRestaurateur(null);

        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/menus", name="restaurant_api_create_menu")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createMenuAction($id, Request $request) {
        $menuRequest = $this->convertMenuRequest($request->getContent());

        $restaurantEntity = $this->getRestaurantById($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $menuEntity = MenuMapper::toMenu($menuRequest);
        $menuEntity->setRestaurant($restaurantEntity);

        $this->persistEntity($menuEntity);

        $response = new Response('', Response::HTTP_CREATED, [
            "Location" => $this->generateUrl('restaurant_api_get_menu', [
                "restaurant_id" => $id,
                "menu_id" => $menuEntity->getId()
            ]),
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Menu:menu.json.twig", [
            "menu" => $menuEntity
        ], $response);
    }

    /**
     * @param int $restaurant_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus", name="restaurant_api_get_all_menus")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllMenuAction($restaurant_id) {
        $menuEntities = $this->getRestaurantById($restaurant_id)->getMenus();

        $response = new Response('', Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Menu:menus.json.twig", [
            "menus" => $menuEntities
        ], $response);
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}", name="restaurant_api_get_menu")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getMenuAction($restaurant_id, $menu_id) {
        $menuEntity = $this->getMenuById($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

        $response = new Response('', Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Menu:menu.json.twig", [
            "menu" => $menuEntity
        ], $response);
    }

    /**
     * @param int $restaurant_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/commandes", name="restaurant_api_get_commandes")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getCommandesAction($restaurant_id) {
        $commandeEntities = $this->getRestaurantById($restaurant_id)->getCommandes();

        $response = new Response('', Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Commande:commandes.json.twig", [
            "commandes" => $commandeEntities
        ], $response);
    }

    /**
     * @param $json
     * @return RestaurantRequest
     */
    private function convertRestaurantRequest($json)
    {
        return $this->fromJson($json, 'Log210\APIBundle\Message\Request\RestaurantRequest');
    }

    /**
     * @param string $json
     * @return MenuRequest
     */
    private function convertMenuRequest($json)
    {
        return $this->fromJson($json, 'Log210\APIBundle\Message\Request\MenuRequest');
    }

    /**
     * @param int $id
     * @return Restaurant
     */
    private function getRestaurantById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
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
     * @param int $id
     * @return Menu
     */
    private function getMenuById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Menu')->find($id);
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
}
