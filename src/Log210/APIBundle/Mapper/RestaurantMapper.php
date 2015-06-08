<?php
/**
 * Created by PhpStorm.
 * User: Tomasz
 * Date: 28/05/2015
 * Time: 1:12 AM
 */

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Request\RestaurantRequest;
use Log210\APIBundle\Message\Response\Link;
use Log210\APIBundle\Message\Response\RestaurantResponse;
use Log210\LivraisonBundle\Entity\Restaurant;

class RestaurantMapper {

    /**
     * @param RestaurantRequest $restaurantRequest
     * @param Restaurant $restaurant
     * @return Restaurant
     */
    public static function toRestaurant(RestaurantRequest $restaurantRequest, Restaurant $restaurant = null) {
        if (is_null($restaurant))
            $restaurant = new Restaurant();
        $restaurant->setName($restaurantRequest->getName());
        $restaurant->setDescription($restaurantRequest->getDescription());
        $restaurant->setAddress($restaurantRequest->getAddress());
        $restaurant->setPhone($restaurantRequest->getPhone());
        return $restaurant;
    }

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
        $links = array();
        array_push($links, new Link('restaurateur', '/api/restaurants/' . $restaurantEntity->getId() .
            '/restaurateur'));
        array_push($links, new Link('menus', '/api/restaurants/' . $restaurantEntity->getId() . '/menus'));
        array_push($links, new Link('self', '/api/restaurants/' . $restaurantEntity->getId()));
        $restaurantResponse->setLinks($links);
        return $restaurantResponse;
    }
}
