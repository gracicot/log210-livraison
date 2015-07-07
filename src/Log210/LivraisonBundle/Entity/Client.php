<?php

namespace Log210\LivraisonBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Log210\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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
}
