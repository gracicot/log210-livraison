<?php

namespace Log210\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Log210\LivraisonBundle\Entity\Restaurateur;
use Log210\LivraisonBundle\Entity\Client;

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

    /**
     * @ORM\OneToOne(targetEntity="\Log210\LivraisonBundle\Entity\Restaurateur", mappedBy="user")
     */
    protected $restaurateur;

    /**
     * @ORM\OneToOne(targetEntity="\Log210\LivraisonBundle\Entity\Client", mappedBy="user")
     */
    protected $client;

    public function __construct() {
        parent::__construct();
    	$this->roles = ['ROLE_USER'];
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

    public function getRestaurateur()
    {
        return $this->restaurateur;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient(Client $client = null)
    {
        $oldClient = $this->client;
        $this->client = $client;

        if ($oldClient !== null && $oldClient->getUser() === $this) {
            $oldClient->setUser(null);
        }

        if ($client !== null && $client->getUser() !== $this) {
            $client->setUser($this);
        }
    }
}
