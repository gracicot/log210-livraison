<?php

namespace Log210\APIBundle\Controller;

use Log210\APIBundle\Mapper\MenuMapper;
use Log210\APIBundle\Mapper\PlatMapper;
use Log210\APIBundle\Message\Request\MenuRequest;
use Log210\APIBundle\Message\Request\PlatRequest;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Menu;
use Log210\LivraisonBundle\Entity\Plat;
use Log210\LivraisonBundle\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MenuController
 * @package Log210\LivraisonBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/menus")
 */
class MenuController extends BaseController {

    /**
     * @param Request $request
     * @return Response
     *
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="menu_api_create")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createAction(Request $request) {
        $menuRequest = $this->convertMenuRequest($request->getContent());

        $menuEntity = MenuMapper::toMenu($menuRequest);

        $this->persistEntity($menuEntity);

        $response = new Response("", Response::HTTP_CREATED, [
            "Location" => $this->generateUrl('menu_api_get', [
                "id" => $menuEntity->getId()
            ]),
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Menu:menu.json.twig", [
            "menu" => $menuEntity
        ], $response);
    }

    /**
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="menu_api_get_all")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllAction() {
        $menuEntities = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Menu')->findAll();

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Menu:menus.json.twig", [
            "menus" => $menuEntities
        ], $response);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="menu_api_get")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAction($id) {
        $menuEntity = $this->getMenuById($id);
        if (is_null($menuEntity))
            return new Response("", Response::HTTP_NOT_FOUND);

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Menu:menu.json.twig", [
            "menu" => $menuEntity
        ], $response);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="menu_api_update")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("PUT")
     */
    public function updateMenuAction($id, Request $request) {
        $menuEntity = $this->getMenuById($id);
        if (is_null($menuEntity))
            return new Response("", Response::HTTP_NOT_FOUND);

        $menuRequest = $this->convertMenuRequest($request->getContent());

        MenuMapper::toMenu($menuRequest, $menuEntity);

        $this->getEntityManager()->flush();

        return new Response("", Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="menu_api_delete")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deleteMenuAction($id) {
        $menuEntity = $this->getMenuById($id);
        if (is_null($menuEntity))
            return new Response("", Response::HTTP_NOT_FOUND);

        foreach ($menuEntity->getPlats() as $platEntity)
            $this->removeEntity($platEntity);
        $this->removeEntity($menuEntity);

        return new Response("", Response::HTTP_NO_CONTENT);
    }
    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurant", name="menu_api_link_restaurant")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function linkRestaurantAction($id, Request $request) {
        $requestBody = json_decode($request->getContent(), true);

        $menuEntity = $this->getMenuById($id);
        if (is_null($menuEntity))
            return new Response("", Response::HTTP_NOT_FOUND);

        $restaurantEntity = $this->getRestaurantById($requestBody["restaurant-id"]);
        if (is_null($restaurantEntity)) {
            return new Response('{"error": "Restaurant not found"}', Response::HTTP_BAD_REQUEST, [
                "Content-Type" => "application/json"
            ]);
        }

        $menuEntity->setRestaurant($restaurantEntity);

        $this->getEntityManager()->flush();

        return new Response("", Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurant", name="menu_api_get_restaurant")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getRestaurantAction($id) {
        $menuEntity = $this->getMenuById($id);
        if (is_null($menuEntity) || is_null($menuEntity->getRestaurant()))
            return new Response("", Response::HTTP_NOT_FOUND);

        return $this->forward("Log210APIBundle:Restaurant:get", [
            "id" => $menuEntity->getRestaurant()->getId()
        ]);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/restaurant", name="menu_api_unlink_restaurant")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function unlinkRestaurantAction($id) {
        $menuEntity = $this->getMenuById($id);
        if (is_null($menuEntity))
            return new Response("", Response::HTTP_NOT_FOUND);

        $menuEntity->setRestaurant(null);

        $this->getEntityManager()->flush();

        return new Response("", Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/plats", name="menu_api_create_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createPlatAction($id, Request $request) {
        $menuEntity = $this->getMenuById($id);
        if (is_null($menuEntity))
            return new Response("", Response::HTTP_NOT_FOUND);

        $platRequest = $this->convertPlatRequest($request->getContent());

        $platEntity = PlatMapper::toPlat($platRequest);
        $platEntity->setMenu($menuEntity);

        $this->persistEntity($platEntity);

        $response = new Response("", Response::HTTP_CREATED, [
            "Location" => $this->generateUrl("menu_api_get_plat", [
                "menu_id" => $id,
                "plat_id" => $platEntity->getId()
            ]),
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Plat:plat.json.twig", [
            "plat" => $platEntity
        ], $response);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}/plats", name="menu_api_get_all_plats")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllPlatsAction($id) {
        $menuEntity = $this->getMenuById($id);
        if (is_null($menuEntity))
            return new Response("", Response::HTTP_NOT_FOUND);

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Plat:plats.json.twig", [
            "plats" => $menuEntity->getPlats()
        ], $response);
    }

    /**
     * @param int $menu_id
     * @param int $plat_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{menu_id}/plats/{plat_id}", name="menu_api_get_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getPlatAction($menu_id, $plat_id) {
        $platEntity = $this->getPlatById($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id)
            return new Response("", Response::HTTP_NOT_FOUND);

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Plat:plat.json.twig", [
            "plat" => $platEntity
        ], $response);
    }

    /**
     * @param int $menu_id
     * @param int $plat_id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{menu_id}/plats/{plat_id}", name="menu_api_update_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("PUT")
     */
    public function updatePlatAction($menu_id, $plat_id, Request $request) {
        $platEntity = $this->getPlatById($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id)
            return new Response("", Response::HTTP_NOT_FOUND);

        $platRequest = $this->convertPlatRequest($request->getContent());

        PlatMapper::toPlat($platRequest, $platEntity);

        $this->getEntityManager()->flush();

        return new Response("", Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $menu_id
     * @param int $plat_id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{menu_id}/plats/{plat_id}", name="menu_api_delete_plat")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deletePlatAction($menu_id, $plat_id) {
        $platEntity = $this->getPlatById($plat_id);
        if (is_null($platEntity) || $platEntity->getMenu()->getId() != $menu_id)
            return new Response("", Response::HTTP_NOT_FOUND);

        $this->removeEntity($platEntity);

        return new Response("", Response::HTTP_NO_CONTENT);
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
     * @param int $id
     * @return Restaurant
     */
    private function getRestaurantById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
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
