<?php

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Request\MenuRequest;
use Log210\LivraisonBundle\Entity\Menu;

class MenuMapper {

    /**
     * @param MenuRequest $menuRequest
     * @param Menu $menuEntity
     * @return Menu
     */
    public static function toMenu(MenuRequest $menuRequest, Menu $menuEntity = null) {
        if (is_null($menuEntity))
            $menuEntity = new Menu();
        $menuEntity->setName($menuRequest->getName());
        return $menuEntity;
    }
}
