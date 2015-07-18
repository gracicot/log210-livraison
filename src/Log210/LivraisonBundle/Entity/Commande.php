<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Commande
 * @package Log210\LivraisonBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="commande")
 */
class Commande {

    /**
     * @var int $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $dateHeure
     *
     * @ORM\Column(type="datetime")
     */
    private $dateHeure;

    /**
     * @var string $adresse
     *
     * @ORM\Column(type="string")
     */
    private $adresse;

    /**
     * @var string $etat
     *
     * @ORM\Column(type="string")
     */
    private $etat;

    /**
     * @var ArrayCollection $commandePlats
     *
     * @ORM\OneToMany(targetEntity="CommandePlat", mappedBy="commande")
     */
    private $commandePlats;

    /**
     * @var Restaurant $restaurant
     *
     * @ORM\ManyToOne(targetEntity="Restaurant")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     */
    private $restaurant;

    /**
     * @var Client $client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var Livreur $livreur
     *
     * @ORM\ManyToOne(targetEntity="Livreur", inversedBy="commandesAcceptees")
     * @ORM\JoinColumn(name="livreur_id", referencedColumnName="id")
     */
    private $livreur;

    public function __construct() {
        $this->commandePlats = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getDateHeure()
    {
        return $this->dateHeure;
    }

    /**
     * @param \DateTime $dateHeure
     */
    public function setDateHeure($dateHeure)
    {
        $this->dateHeure = $dateHeure;
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
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return ArrayCollection
     */
    public function getCommandePlats()
    {
        return $this->commandePlats;
    }

    /**
     * @param ArrayCollection $commandePlats
     */
    public function setCommandePlats($commandePlats)
    {
        $this->commandePlats = $commandePlats;
    }

    /**
     * @param CommandePlat $commandePlat
     */
    public function addCommandePlat($commandePlat)
    {
        $this->commandePlats->add($commandePlat);
    }

    /**
     * @return Restaurant
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * @param Restaurant $restaurant
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return Livreur
     */
    public function getLivreur()
    {
        return $this->livreur;
    }

    /**
     * @param Livreur $livreur
     */
    public function setLivreur($livreur)
    {
        $this->livreur = $livreur;
    }

    public function getTotal()
    {
        $total = 0;
        
        foreach ($this->getCommandePlats() as $commandePlat) {
            $total += $commandePlat->getQuantity() * $commandePlat->getPlat()->getPrix();
        }

        return $total;
    }
}
