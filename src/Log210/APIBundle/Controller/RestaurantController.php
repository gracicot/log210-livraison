<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Mapper\MenuMapper;
use Log210\APIBundle\Mapper\PlatMapper;
use Log210\APIBundle\Mapper\RestaurantMapper;
use Log210\CommonBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RestaurantController
 * @package Log210\LivraisonBundle\Controller
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
        $restaurantRequest = $this->fromJson($request->getContent(),
            'Log210\APIBundle\Message\Request\RestaurantRequest');

        $restaurantEntity = RestaurantMapper::toRestaurant($restaurantRequest);

        $this->getEntityManager()->persist($restaurantEntity);
        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_CREATED, array('Location' => $this->generateUrl(
            'restaurant_api_get', array('id' => $restaurantEntity->getId()), true)));
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
        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurantResponse = RestaurantMapper::toRestaurantResponse($restaurantEntity);

        return new Response($this->toJson($restaurantResponse), Response::HTTP_OK, array(
            "Content-Type" => "application/json"));
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
        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurantRequest = $this->fromJson($request->getContent(),
            'Log210\APIBundle\Message\Request\RestaurantRequest');

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
        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $this->getEntityManager()->remove($restaurantEntity);
        $this->getEntityManager()->flush();
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

        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $restaurateurEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurateur')
            ->find($requestBody["restaurateur-id"]);
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
        $restaurant = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
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
        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
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
        $menuRequest = $this->fromJson($request->getContent(), 'Log210\APIBundle\Message\Request\MenuRequest');

        $restaurantEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
        if (is_null($restaurantEntity))
            return new Response('', Response::HTTP_NOT_FOUND);

        $menuEntity = MenuMapper::toMenu($menuRequest);
        $menuEntity->setRestaurant($restaurantEntity);

        $this->getEntityManager()->persist($menuEntity);
        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_CREATED, array("Location" => $this->generateUrl(
            'restaurant_api_get_menu', array("restaurant_id" => $id, "menu_id" => $menuEntity->getId()))));
    }

    /**
     * @param int $restaurant_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus", name="restaurant_api_get_all_menus")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllMenuAction($restaurant_id) {
        $menuEntities = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')
            ->find($restaurant_id)->getMenus();

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
        $menuEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Menu')->find($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

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
        $menuEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Menu')->find($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

        $menuRequest = $this->fromJson($request->getContent(), 'Log210\APIBundle\Message\Request\MenuRequest');

        MenuMapper::toMenu($menuRequest, $menuEntity);

        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
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
        $menuEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Menu')->find($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

        $this->getEntityManager()->remove($menuEntity);
        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
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
        $menuEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Menu')->find($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

        $platRequest = $this->fromJson($request->getContent(), 'Log210\APIBundle\Message\Request\PlatRequest');

        $platEntity = PlatMapper::toPlat($platRequest);
        $platEntity->setMenu($menuEntity);

        $this->getEntityManager()->persist($platEntity);
        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_CREATED, array("Location" => $this->generateUrl(
            "restaurant_api_get_plat", array('restaurant_id' => $restaurant_id, 'menu_id' => $menu_id,
            'plat_id' => $platEntity->getId()))));
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
        $menuEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Menu')->find($menu_id);
        if (is_null($menuEntity) || $menuEntity->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

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
        $platEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Plat')->find($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id || $platEntity->getMenu()
                ->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

        $platResponse = PlatMapper::toPlatResponse($platEntity);

        return $this->jsonResponse(new Response($this->toJson($platResponse)));
    }

    /**
     * @param int $restaurant_id
     * @param int $menu_id
     * @param int $plat_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{restaurant_id}/menus/{menu_id}/plats/{plat_id}", name="restaurant_api_update_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("PUT")
     */
    public function updatePlatAction($restaurant_id, $menu_id, $plat_id, Request $request) {
        $platEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Plat')->find($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id || $platEntity->getMenu()
                ->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

        $platRequest = $this->fromJson($request->getContent(), 'Log210\APIBundle\Message\Request\PlatRequest');

        PlatMapper::toPlat($platRequest, $platEntity);

        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
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
        $platEntity = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Plat')->find($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id || $platEntity->getMenu()
                ->getRestaurant()->getId() != $restaurant_id)
            return new Response('', Response::HTTP_NOT_FOUND);

        $this->getEntityManager()->remove($platEntity);
        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
