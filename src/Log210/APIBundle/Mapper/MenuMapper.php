<?php

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Request\MenuRequest;
use Log210\APIBundle\Message\Response\Link;
use Log210\APIBundle\Message\Response\MenuResponse;
use Log210\LivraisonBundle\Entity\Menu;

class MenuMapper {

    /**
     * @param MenuRequest $menuRequest
     * @return Menu
     */
    public static function toMenu(MenuRequest $menuRequest, Menu $menuEntity = null) {
        if (is_null($menuEntity))
            $menuEntity = new Menu();
        $menuEntity->setName($menuRequest->getName());
        return $menuEntity;
    }

    /**
     * @param Menu $menuEntity
     * @return MenuResponse
     */
    public static function toMenuResponse(Menu $menuEntity) {
        $menuResponse = new MenuResponse();
        $menuResponse->setId($menuEntity->getId());
        $menuResponse->setName($menuEntity->getName());
        $menuResponse->setWarning(0);
        foreach ($menuEntity->getPlats() as $plat) {
            $desc = $plat->getDescription();
            if (empty($desc)) {
                $menuResponse->setWarning(1);
                break;
            }
        }
        $links = array(
            new Link('plats', '/api/menus/' . $menuEntity->getId() . '/plats'),
            new Link('restaurant', '/api/menus/' . $menuEntity->getId() . '/restaurant'),
            new Link('self', '/api/menus/' . $menuEntity->getId())
        );
        $menuResponse->setLinks($links);
        $platResponses = array();
        foreach ($menuEntity->getPlats() as $platEntity)
            array_push($platResponses, PlatMapper::toPlatResponse($platEntity));

        $menuResponse->setPlats($platResponses);

        return $menuResponse;
    }

}
