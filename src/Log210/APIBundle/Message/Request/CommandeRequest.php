<?php

namespace Log210\APIBundle\Message\Request;


class CommandeRequest {

    /**
     * @var \DateTime $date_heure
     */
    private $date_heure;

    /**
     * @var string $adresse
     */
    private $adresse;

    /**
     * @var array $commande_plats
     */
    private $commande_plats;

    /**
     * @var int $restaurant_id
     */
    private $restaurant_id;

    /**
     * @return \DateTime
     */
    public function getDate_heure()
    {
        return $this->date_heure;
    }

    /**
     * @param \DateTime $date_heure
     */
    public function setDate_heure($date_heure)
    {
        $this->date_heure = $date_heure;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return array
     */
    public function getCommande_plats()
    {
        return $this->commande_plats;
    }

    /**
     * @param array $commandePlats
     */
    public function setCommande_plats($commande_plats)
    {
        $this->commande_plats = $commande_plats;
    }

    /**
     * @return int
     */
    public function getRestaurant_id()
    {
        return $this->restaurant_id;
    }

    /**
     * @param int $restaurant_id
     */
    public function setRestaurant_id($restaurant_id)
    {
        $this->restaurant_id = $restaurant_id;
    }
}
