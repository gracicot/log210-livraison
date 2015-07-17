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

        return $this->createCreatedResponse($this->generateUrl('menu_api_get', array("id" => $menuEntity->getId())));
    }

    /**
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="menu_api_get_all")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAllAction() {
        $menuEntities = $this->getEntityManager()->getRepository('Log210LivraisonBundle:Menu')->findAll();

        $menuResponses = array();
        foreach ($menuEntities as $menuEntity)
            array_push($menuResponses, MenuMapper::toMenuResponse($menuEntity));

        return $this->jsonResponse(new Response($this->toJson($menuResponses)));
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
            return $this->createNotFoundResponse();

        $menuResponse = MenuMapper::toMenuResponse($menuEntity);

        return $this->jsonResponse(new Response($this->toJson($menuResponse)));
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
            return $this->createNotFoundResponse();

        $menuRequest = $this->convertMenuRequest($request->getContent());

        MenuMapper::toMenu($menuRequest, $menuEntity);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

        foreach ($menuEntity->getPlats() as $platEntity)
            $this->removeEntity($platEntity);
        $this->removeEntity($menuEntity);

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

        $restaurantEntity = $this->getRestaurantById($requestBody["restaurant-id"]);
        if (is_null($restaurantEntity)) {
            return new Response('{"error": "Restaurant not found"}', Response::HTTP_BAD_REQUEST, array(
                "Content-Type" => "application/json"));
        }

        $menuEntity->setRestaurant($restaurantEntity);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

        return $this->forward('Log210APIBundle:Restaurant:get', array('id' => $menuEntity->getRestaurant()->getId()));
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
            $this->createNotFoundResponse();

        $menuEntity->setRestaurant(null);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

        $platRequest = $this->convertPlatRequest($request->getContent());

        $platEntity = PlatMapper::toPlat($platRequest);
        $platEntity->setMenu($menuEntity);

        $this->persistEntity($platEntity);

        return $this->createCreatedResponse($this->generateUrl("menu_api_get_plat", array('menu_id' => $id,
            'plat_id' => $platEntity->getId())));
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
            return $this->createNotFoundResponse();

        $platResponses = array();
        foreach ($menuEntity->getPlats() as $platEntity)
            array_push($platResponses, PlatMapper::toPlatResponse($platEntity));

        return $this->jsonResponse(new Response($this->toJson($platResponses)));
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
            return $this->createNotFoundResponse();

        $platResponse = PlatMapper::toPlatResponse($platEntity);

        return $this->jsonResponse(new Response($this->toJson($platResponse)));
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
            return $this->createNotFoundResponse();

        $platRequest = $this->convertPlatRequest($request->getContent());

        PlatMapper::toPlat($platRequest, $platEntity);

        $this->getEntityManager()->flush();

        return $this->createNoContentResponse();
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
            return $this->createNotFoundResponse();

        $this->removeEntity($platEntity);

        return $this->createNoContentResponse();
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
