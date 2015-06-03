<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="plat")
 * @ORM\Entity(repositoryClass="Log210\LivraisonBundle\EntityRepository\RestaurantRepository")
 */
class Plat
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
     * @ORM\Column(type="integer")
     */
    protected $prix;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="plats")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     **/
    protected $menu;

    public function __toString()
    {
        return $this->getName();
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
     * @return Plat
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
     * @return Plat
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
     * Set prix
     *
     * @param integer $prix
     * @return Plat
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set menu
     *
     * @param \Log210\LivraisonBundle\Entity\Menu $menu
     * @return Plat
     */
    public function setMenu(\Log210\LivraisonBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Log210\LivraisonBundle\Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
