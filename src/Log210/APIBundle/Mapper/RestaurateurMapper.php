<?php
/**
 * Created by PhpStorm.
 * User: Tomasz
 * Date: 28/05/2015
 * Time: 1:17 AM
 */

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Request\RestaurateurRequest;
use Log210\APIBundle\Message\Response\Link;
use Log210\APIBundle\Message\Response\RestaurateurResponse;
use Log210\LivraisonBundle\Entity\Restaurateur;

class RestaurateurMapper {

    /**
     * @param $restaurateurEntity Restaurateur
     * @return RestaurateurResponse
     */
    public static function toRestaurateurResponse(Restaurateur $restaurateurEntity) {
        $restaurateurResponse = new RestaurateurResponse();
        $restaurateurResponse->setId($restaurateurEntity->getId());
        $restaurateurResponse->setName($restaurateurEntity->getName());
        $restaurateurResponse->setDescription($restaurateurEntity->getDescription());
        $links = array();
        array_push($links, new Link('restaurants', '/api/restaurateurs/' . $restaurateurEntity->getId() .
            '/restaurants'));
        array_push($links, new Link('self', '/api/restaurateurs/' . $restaurateurEntity->getId()));
        $restaurateurResponse->setLinks($links);
        return $restaurateurResponse;
    }

    /**
     * @param $restaurateurRequest RestaurateurRequest
     * @return Restaurateur
     */
    public static function toRestaurateur(RestaurateurRequest $restaurateurRequest) {
        $restaurateurEntity = new Restaurateur();
        $restaurateurEntity->setName($restaurateurRequest->getName());
        $restaurateurEntity->setDescription($restaurateurRequest->getDescription());
        return $restaurateurEntity;
    }
}
