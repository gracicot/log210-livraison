<?php

namespace Log210\LivraisonBundle\Utils;

use Doctrine\ORM\EntityManager;
use Log210\LivraisonBundle\Entity\Restaurant;

class RestaurantService {

    private $EntityManager;

    function __construct(EntityManager $entityManager) {
        $this->EntityManager = $entityManager;
    }

    public function getAllRestaurants() {
        return $this->EntityManager->getRepository('Log210LivraisonBundle:Restaurant')->findAll();
    }

    public function getRestaurantById($id) {
        return $this->EntityManager->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
    }

    public function createRestaurant(Restaurant $restaurant) {
        $this->EntityManager->persist($restaurant);
        $this->EntityManager->flush();
    }

    public function deleteRestaurant($restaurantId) {
        $restaurantToDelete = $this->getRestaurantById($restaurantId);
        $this->EntityManager->remove($restaurantToDelete);
        $this->EntityManager->flush();
    }

    public function updateRestaurant($id, Restaurant $modifiedRestaurant) {
        $savedRestaurant = $this->getRestaurantById($id);
        $savedRestaurant->setName($modifiedRestaurant->getName());
        $savedRestaurant->setDescription($modifiedRestaurant->getDescription());
        $savedRestaurant->setAdresse($modifiedRestaurant->getAdresse());
        $savedRestaurant->setRestaurateur($modifiedRestaurant->getRestaurateur());
        $this->EntityManager->flush();
    }

}