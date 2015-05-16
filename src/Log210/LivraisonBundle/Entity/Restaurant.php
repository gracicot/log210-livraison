<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="restaurant")
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
     * @ManyToOne(targetEntity="Restaurateur", inversedBy="restaurants")
     * @JoinColumn(name="restaurateur_id", referencedColumnName="id")
     **/
    protected $restaurateur;
}
