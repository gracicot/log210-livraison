<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="Log210\LivraisonBundle\EntityRepository\MenuRepository")
 */
class Menu
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
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="menus")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     **/
    protected $restaurant;



    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @ORM\OneToMany(targetEntity="Plat", mappedBy="menu", cascade={"all"}, orphanRemoval=true)
     **/
    protected $plats;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
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
     * @return Menu
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
     * Set restaurant
     *
     * @param \Log210\LivraisonBundle\Entity\Restaurant $restaurant
     * @return Menu
     */
    public function setRestaurant(Restaurant $restaurant = null)
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * Get restaurant
     *
     * @return \Log210\LivraisonBundle\Entity\Restaurant 
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Add plats
     *
     * @param \Log210\LivraisonBundle\Entity\Plat $plats
     * @return Menu
     */
    public function addPlat(Plat $plats)
    {
        if ($plats->getMenu() !== $this) {
            $plats->setMenu($this);
        }
        $this->plats[] = $plats;

        return $this;
    }

    /**
     * Remove plats
     *
     * @param \Log210\LivraisonBundle\Entity\Plat $plats
     */
    public function removePlat(Plat $plats)
    {
        if ($plats->getMenu() === $this) {
            $plats->setMenu(null);
        }

        $this->plats->removeElement($plats);
    }

    /**
     * Get plats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlats()
    {
        return $this->plats;
    }
}
