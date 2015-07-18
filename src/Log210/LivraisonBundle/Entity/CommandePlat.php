<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CommandePlat
 * @package Log210\LivraisonBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="commande_plat")
 */
class CommandePlat {

    /**
     * @var int $quantity
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var Commande $commande
     *
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="commandePlats")
     * @ORM\JoinColumn(name="commande_id", referencedColumnName="id")
     * @ORM\Id
     */
    private $commande;

    /**
     * @var Plat $plat
     *
     * @ORM\ManyToOne(targetEntity="Plat")
     * @ORM\JoinColumn(name="plat_id", referencedColumnName="id")
     * @ORM\Id
     */
    private $plat;

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * @param Commande $commande
     */
    public function setCommande($commande)
    {
        $this->commande = $commande;
    }

    /**
     * @return Plat
     */
    public function getPlat()
    {
        return $this->plat;
    }

    /**
     * @param Plat $plat
     */
    public function setPlat($plat)
    {
        $this->plat = $plat;
    }
}
