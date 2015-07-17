<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Log210\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Log210\LivraisonBundle\EntityRepository\ClientRepository")
 * @ORM\Table(name="clients")
 */
class Client extends User {

    /**
     * @var string $address
     * @ORM\Column(type="text")
     */
    protected $address;

    /**
     * @var string $phoneNumber
     * @ORM\Column(type="text")
     */
    protected $phoneNumber;

    public function __construct() {
        parent::__construct();
        $this->addRole("ROLE_CLIENT");
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }
}
