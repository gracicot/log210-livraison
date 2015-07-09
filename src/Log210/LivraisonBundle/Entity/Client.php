<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Log210\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $address;

    /**
     * @ORM\OneToOne(targetEntity="\Log210\UserBundle\Entity\User", inversedBy="client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

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

        if ($oldUser !== null && $oldUser->getClient() === $this) {
            $oldUser->setClient(null);
        }

        if ($user !== null && $user->getClient() !== $this) {
            $user->setClient($this);
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

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
