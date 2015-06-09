<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Mapper\MenuMapper;
use Log210\APIBundle\Mapper\PlatMapper;
use Log210\APIBundle\Mapper\RestaurantMapper;
use Log210\APIBundle\Message\Request\MenuRequest;
use Log210\APIBundle\Message\Request\PlatRequest;
use Log210\APIBundle\Message\Request\RestaurantRequest;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Menu;
use Log210\LivraisonBundle\Entity\Plat;
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
    public function createAction(Request $request)
    {
        $restaurantRequest = $this->convertRestaurantRequest($request->getContent());

        $restaurantEntity = RestaurantMapper::toRestaurant($restaurantRequest);

        $this->persistEntity($restaurantEntity);

        return $this->createCreatedResponse($this->generateUrl('restaurant_api_get', array('id' => $restaurantEntity
            ->getId()), true));
    }

    /**
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="restaurant_api_get_all")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllAction()
    {
        $restaurantEntities = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->findAll();

        $restaurantResponses = array();
        foreach ($restaurantEntities as $restaurant)
            array_push($restaurantResponses, RestaurantMapper::toRestaurantResponse($restaurant));

        return $this->jsonResponse(new Response($this->toJson($restaurantResponses)));
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
            return $this->createNotFoundResponse();

        $restaurantResponse = RestaurantMapper::toRestaurantResponse($restaurantEntity);

        return $this->jsonResponse(new Response($this->toJson($restaurantResponse)));
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
            return $this->createNotFoundResponse();

        $restaurantRequest = $this->convertRestaurantRequest($request->getContent());

        RestaurantMapper::toRestaurant($restaurantRequest, $restaurantEntity);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

        $this->removeEntity($restaurantEntity);

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

        $restaurateurEntity = $this->getRestaurateurById($requestBody["restaurateur-id"]);
        if (is_null($restaurateurEntity)) {
            return new Response('{"error": "Restaurateur not found"}', Response::HTTP_BAD_REQUEST, array(
                "Content-Type" => "application/json"));
        }

        $restaurantEntity->setRestaurateur($restaurateurEntity);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

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
            return $this->createNotFoundResponse();

        $restaurantEntity->setRestaurateur(null);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

        $menuEntity = MenuMapper::toMenu($menuRequest);
        $menuEntity->setRestaurant($restaurantEntity);

        $this->persistEntity($menuEntity);

        return $this->createCreatedResponse($this->generateUrl('restaurant_api_get_menu', array("restaurant_id" => $id,
            "menu_id" => $menuEntity->getId())));
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

        $menuResponses = array();
        foreach ($menuEntities as $menuEntity)
            array_push($menuResponses, MenuMapper::toMenuResponse($menuEntity));

        return $this->jsonResponse(new Response($this->toJson($menuResponses)));
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
            return $this->createNotFoundResponse();

        $menuResponse = MenuMapper::toMenuResponse($menuEntity);

        return $this->jsonResponse(new Response($this->toJson($menuResponse)));
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}", name="restaurant_api_update_menu")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("PUT")
     */
    public function updateMenuAction($restaurant_id, $menu_id, Request $request) {
        $menuEntity = $this->getMenuById($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return $this->createNotFoundResponse();

        $menuRequest = $this->convertMenuRequest($request->getContent());

        MenuMapper::toMenu($menuRequest, $menuEntity);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}", name="restaurant_api_delete_menu")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deleteMenuAction($restaurant_id, $menu_id) {
        $menuEntity = $this->getMenuById($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return $this->createNotFoundResponse();

        $this->removeEntity($menuEntity);

        return $this->createNoContentResponse();
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}/plats", name="restaurant_api_create_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createPlatAction($restaurant_id, $menu_id, Request $request) {
        $menuEntity = $this->getMenuById($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return $this->createNotFoundResponse();

        $platRequest = $this->convertPlatRequest($request->getContent());

        $platEntity = PlatMapper::toPlat($platRequest);
        $platEntity->setMenu($menuEntity);

        $this->persistEntity($platEntity);

        return $this->createCreatedResponse($this->generateUrl("restaurant_api_get_plat", array('restaurant_id' =>
            $restaurant_id, 'menu_id' => $menu_id, 'plat_id' => $platEntity->getId())));
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}/plats", name="restaurant_api_get_all_plats")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllPlatsAction($restaurant_id, $menu_id) {
        $menuEntity = $this->getMenuById($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return $this->createNotFoundResponse();

        $platResponses = array();
        foreach ($menuEntity->getPlats() as $platEntity)
            array_push($platResponses, PlatMapper::toPlatResponse($platEntity));

        return $this->jsonResponse(new Response($this->toJson($platResponses)));
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @param int $plat_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}/plats/{plat_id}", name="restaurant_api_get_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getPlatAction($restaurant_id, $menu_id, $plat_id) {
        $platEntity = $this->getPlatById($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id || $platEntity->getMenu()
                ->getRestaurant()->getId() != $restaurant_id)
            return $this->createNotFoundResponse();

        $platResponse = PlatMapper::toPlatResponse($platEntity);

        return $this->jsonResponse(new Response($this->toJson($platResponse)));
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @param int $plat_id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}/plats/{plat_id}", name="restaurant_api_update_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("PUT")
     */
    public function updatePlatAction($restaurant_id, $menu_id, $plat_id, Request $request) {
        $platEntity = $this->getPlatById($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id || $platEntity->getMenu()
                ->getRestaurant()->getId() != $restaurant_id)
            return $this->createNotFoundResponse();

        $platRequest = $this->convertPlatRequest($request->getContent());

        PlatMapper::toPlat($platRequest, $platEntity);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @param int $plat_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}/plats/{plat_id}", name="restaurant_api_delete_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deletePlatAction($restaurant_id, $menu_id, $plat_id) {
        $platEntity = $this->getPlatById($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id || $platEntity->getMenu()
                ->getRestaurant()->getId() != $restaurant_id)
            return $this->createNotFoundResponse();

        $this->removeEntity($platEntity);

        return $this->createNoContentResponse();
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
     * @param string $json
     * @return PlatRequest
     */
    private function convertPlatRequest($json)
    {
        return $this->fromJson($json, 'Log210\APIBundle\Message\Request\PlatRequest');
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
     * @param int $id
     * @return Plat
     */
    private function getPlatById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Plat')->find($id);
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
}
