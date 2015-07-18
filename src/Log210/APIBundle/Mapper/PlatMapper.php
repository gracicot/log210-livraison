<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-06-08
 * Time: 6:56 PM
 */

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Request\PlatRequest;
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
}
