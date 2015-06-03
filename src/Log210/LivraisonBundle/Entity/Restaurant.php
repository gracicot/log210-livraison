<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="restaurant")
 * @ORM\Entity(repositoryClass="Log210\LivraisonBundle\EntityRepository\RestaurantRepository")
 */
class Restaurant
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
     * @ORM\Column(type="string", length=255)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $phone;

    /**
     * @ORM\ManyToOne(targetEntity="Restaurateur", inversedBy="restaurants")
     * @ORM\JoinColumn(name="restaurateur_id", referencedColumnName="id")
     **/
    protected $restaurateur;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="restaurant")
     **/
    protected $menus;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
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
     * @return Restaurant
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
     * @return Restaurant
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
     * Set address
     *
     * @param string $address
     * @return Restaurant
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Restaurant
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set restaurateur
     *
     * @param \Log210\LivraisonBundle\Entity\Restaurateur $restaurateur
     * @return Restaurant
     */
    public function setRestaurateur(Restaurateur $restaurateur = null)
    {
        $this->restaurateur = $restaurateur;

        return $this;
    }

    /**
     * Get restaurateur
     *
     * @return \Log210\LivraisonBundle\Entity\Restaurateur 
     */
    public function getRestaurateur()
    {
        return $this->restaurateur;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add menus
     *
     * @param \Log210\LivraisonBundle\Entity\menu $menus
     * @return Restaurant
     */
    public function addMenu(\Log210\LivraisonBundle\Entity\menu $menus)
    {
        $this->menus[] = $menus;

        return $this;
    }

    /**
     * Remove menus
     *
     * @param \Log210\LivraisonBundle\Entity\menu $menus
     */
    public function removeMenu(\Log210\LivraisonBundle\Entity\menu $menus)
    {
        $this->menus->removeElement($menus);
    }

    /**
     * Get menus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenus()
    {
        return $this->menus;
    }
}
