<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-06-08
 * Time: 6:56 PM
 */

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Request\PlatRequest;
use Log210\APIBundle\Message\Response\Link;
use Log210\APIBundle\Message\Response\PlatResponse;
use Log210\LivraisonBundle\Entity\Plat;

class PlatMapper {

    /**
     * @param PlatRequest $platRequest
     * @param Plat $plat
     * @return Plat
     */
    public static function toPlat(PlatRequest $platRequest, Plat $plat = null) {
        if (is_null($plat))
            $plat = new Plat();
        $plat->setName($platRequest->getName());
        $plat->setDescription($platRequest->getDescription());
        $plat->setPrix($platRequest->getPrix());
        return $plat;
    }

    /**
     * @param Plat $platEntity
     * @return PlatResponse
     */
    public static function toPlatResponse(Plat $platEntity) {
        $platResponse = new PlatResponse();
        $platResponse->setId($platEntity->getId());
        $platResponse->setName($platEntity->getName());
        $platResponse->setDescription($platEntity->getDescription());
        $platResponse->setPrix($platEntity->getPrix());
        $links = array();
        array_push($links, new Link('menu', '/api/menus/' . $platEntity->getMenu()->getId()));
        array_push($links, new Link('self', '/api/menus/' . $platEntity->getMenu()->getId() . '/plats/' .
            $platEntity->getId()));
        $platResponse->setLinks($links);
        return $platResponse;
    }

}
