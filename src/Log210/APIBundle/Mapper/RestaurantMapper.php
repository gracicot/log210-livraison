<?php
/**
 * Created by PhpStorm.
 * User: Tomasz
 * Date: 28/05/2015
 * Time: 1:12 AM
 */

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Response\RestaurantResponse;
use Log210\LivraisonBundle\Entity\Restaurant;

class RestaurantMapper {
    /**
     * @param Restaurant $restaurantEntity the entity to convert
     * @return RestaurantResponse the restaurant response object
     */
    public static function toRestaurantResponse(Restaurant $restaurantEntity) {
        $restaurantResponse = new RestaurantResponse();
        $restaurantResponse->setId($restaurantEntity->getId());
        $restaurantResponse->setName($restaurantEntity->getName());
        $restaurantResponse->setDescription($restaurantEntity->getDescription());
        $restaurantResponse->setAddress($restaurantEntity->getAddress());
        $restaurantResponse->setPhone($restaurantEntity->getPhone());
        $restaurantResponse->setRestaurateur_href('/api/restaurants/' . $restaurantEntity->getId() . '/restaurateur');
        $restaurantResponse->setSelf_href('/api/restaurants/' . $restaurantEntity->getId());
        return $restaurantResponse;
    }
}