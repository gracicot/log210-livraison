<?php

namespace Log210\APIBundle\Mapper;


use Log210\LivraisonBundle\Entity\Commande;

class CommandeMapper
{
    /**
     * @param Commande $commandeEntity
     * @return array
     */
    public static function convertCommandeEntityToCommandeResponse($commandeEntity)
    {
        $commandeResponse = array(
            "id" => $commandeEntity->getId(),
            "adresse" => $commandeEntity->getAdresse(),
            "client_id" => $commandeEntity->getClient()->getId(),
            "date_heure" => $commandeEntity->getDateHeure()->format('Y-m-d H:i'),
            "restaurant_id" => $commandeEntity->getRestaurant()->getId(),
            "commande_plats" => array(),
            "links" => array(
                array(
                    "rel" => "self",
                    "href" => "/api/commandes/" . $commandeEntity->getId()
                ),
                array(
                    "rel" => "restaurant",
                    "href" => "/api/restaurants/" . $commandeEntity->getRestaurant()->getId()
                ),
                array(
                    "rel" => "client",
                    "href" => "/api/profiles/" . $commandeEntity->getClient()->getId()
                )
            )
        );
        foreach ($commandeEntity->getCommandePlats() as $commandePlatEntity) {
            $commandePlatResponse = array(
                "plat_id" => $commandePlatEntity->getPlat()->getId(),
                "quantity" => $commandePlatEntity->getQuantity()
            );
            array_push($commandeResponse["commande_plats"], $commandePlatResponse);
        }
        return $commandeResponse;
    }
}
