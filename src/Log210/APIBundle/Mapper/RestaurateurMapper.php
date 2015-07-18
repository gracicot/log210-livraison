<?php
/**
 * Created by PhpStorm.
 * User: Tomasz
 * Date: 28/05/2015
 * Time: 1:17 AM
 */

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Request\RestaurateurRequest;
use Log210\LivraisonBundle\Entity\Restaurateur;

class RestaurateurMapper {
    /**
     * @param RestaurateurRequest $restaurateurRequest
     * @param Restaurateur $restaurateurEntity
     * @return Restaurateur
     */
    public static function toRestaurateur(RestaurateurRequest $restaurateurRequest, Restaurateur $restaurateurEntity = null) {
        if (is_null($restaurateurEntity))
            $restaurateurEntity = new Restaurateur();
        $restaurateurEntity->setUsername($restaurateurRequest->getUsername());
        $restaurateurEntity->setPlainPassword($restaurateurRequest->getPassword());
        $restaurateurEntity->setEmail($restaurateurRequest->getEmail());
        $restaurateurEntity->setName($restaurateurRequest->getName());
        $restaurateurEntity->setDescription($restaurateurRequest->getDescription());
        $restaurateurEntity->setEnabled(true);
        return $restaurateurEntity;
    }
}
