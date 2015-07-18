<?php
/**
 * Created by PhpStorm.
 * User: Tomasz
 * Date: 28/05/2015
 * Time: 1:12 AM
 */

namespace Log210\APIBundle\Mapper;


use Log210\APIBundle\Message\Request\RestaurantRequest;
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
}
