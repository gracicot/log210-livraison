<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-07-15
 * Time: 1:26 PM
 */

namespace Log210\LivraisonBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Log210\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="livreur")
 */
class Livreur extends User {
    /**
     * @var string $name
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string $emplacementActuel
     * @ORM\Column(type="string", nullable=true)
     */
    private $emplacementActuel;

    /**
     * @var ArrayCollection $commandesAcceptees
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="livreur")
     * @ORM\JoinColumn(name="livreur_id", referencedColumnName="id")
     */
    private $commandesAcceptees;

    public function __construct() {
        parent::__construct();
        $this->addRole("ROLE_LIVREUR");
        $this->commandesAcceptees = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmplacementActuel() {
        return $this->emplacementActuel;
    }

    /**
     * @param string $emplacementActuel
     */
    public function setEmplacementActuel($emplacementActuel) {
        $this->emplacementActuel = $emplacementActuel;
    }

    /**
     * @return ArrayCollection
     */
    public function getCommandesAcceptees() {
        return $this->commandesAcceptees;
    }

    /**
     * @param ArrayCollection $commandesAcceptees
     */
    public function setCommandesAcceptees($commandesAcceptees) {
        $this->commandesAcceptees = $commandesAcceptees;
    }
}
