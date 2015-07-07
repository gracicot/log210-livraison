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
class Restaurateur
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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

    /**
     * @ORM\OneToOne(targetEntity="\Log210\UserBundle\Entity\User", inversedBy="restaurateur")
     * @ORM\JoinColumn(name="restaurateur_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
    public function addRestaurant(Restaurant $restaurants)
    {
        $this->restaurants->add($restaurants);

        return $this;
    }

    /**
     * Remove restaurants
     *
     * @param \Log210\LivraisonBundle\Entity\Restaurant $restaurants
     */
    public function removeRestaurant(Restaurant $restaurants)
    {
        $this->restaurants->removeElement($restaurants);
    }
    /**
     * Set restaurants
     *
     * @param \Log210\LivraisonBundle\Entity\Restaurant $restaurants
     * @return Restaurateur
     */
    public function setRestaurant(Restaurant $restaurants = null)
    {
        $this->restaurants = $restaurants;

        return $this;
    }

    /**
     * Set restaurants
     *
     * @param \Log210\LivraisonBundle\Entity\Restaurant $restaurants
     * @return Restaurateur
     */
    public function setUser(User $user = null)
    {
        $oldUser = $this->user;
        $this->user = $user;

        if ($oldUser !== null && $oldUser->getRestaurateur() === $this) {
            $oldUser->setRestaurateur(null);
        }

        if ($user !== null && $user->getRestaurateur() !== $this) {
            $user->setRestaurateur($this);
        }
    }

    /**
     * Get restaurant
     *
     * @return \Log210\LivraisonBundle\Entity\Restaurant
     */
    public function getUser()
    {
        return $this->user;
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
