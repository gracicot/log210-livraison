<?php

namespace Log210\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Log210\LivraisonBundle\Entity\Restaurateur;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct() {
        parent::__construct();
    	$this->roles = ['ROLE_USER'];
        parent::__construct();
    }

    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    public function getAdress()
    {
        return $this->adress;
    }

    public function setRestaurateur(Restaurateur $restaurateur = null)
    {
        $oldRestaurateur = $this->restaurateur;
        $this->restaurateur = $restaurateur;

        if ($oldRestaurateur !== null && $oldRestaurateur->getUser() === $this) {
            $oldRestaurateur->setUser(null);
        }

        if ($restaurateur !== null && $restaurateur->getUser() !== $this) {
            $restaurateur->setUser($this);
        }
    }
}
