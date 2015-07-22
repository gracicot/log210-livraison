<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Log210\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="restaurateur")
 * @ORM\Entity(repositoryClass="Log210\LivraisonBundle\EntityRepository\RestaurateurRepository")
 */
class Restaurateur extends User {

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Restaurant", mappedBy="restaurateur")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     **/
    protected $restaurants;

    public function __construct() {
        parent::__construct();
        $this->addRole("ROLE_RESTAURATEUR");
        $this->restaurants = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Restaurateur
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Restaurateur
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add restaurants
     *
     * @param \Log210\LivraisonBundle\Entity\Restaurant $restaurants
     * @return Restaurateur
     */
    public function addRestaurant(Restaurant $restaurant)
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants->add($restaurant);
        }

        if ($restaurant->getRestaurateur() !== $this) {
            $restaurant->setRestaurateur($this);
        }

        return $this;
    }

    /**
     * Remove restaurants
     *
     * @param \Log210\LivraisonBundle\Entity\Restaurant $restaurants
     */
    public function removeRestaurant(Restaurant $restaurant)
    {
        if ($this->restaurants->contains($restaurant)) {
            $this->restaurants->removeElement($restaurant);
        }

        if ($restaurant->getRestaurateur() === $this) {
            $restaurant->setRestaurateur(null);
        }
    }
    /**
     * Set restaurants
     *
     * @param \Log210\LivraisonBundle\Entity\Restaurant $restaurants
     * @return Restaurateur
     */
    public function setRestaurant($restaurants = null)
    {
        foreach ($restaurants as $restaurant) {
            if (!$this->restaurants->contains($restaurant)) {
                $this->addRestaurant($restaurant);
            }
        }

        foreach ($this->restaurants as $restaurant) {
            if (!$restaurants->contains($restaurant)) {
               $this->removeRestaurant($restaurant);
            }
        }
    }

    /**
     * Get restaurants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRestaurants()
    {
        return $this->restaurants;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
