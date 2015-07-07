<?php

namespace Log210\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="token")
 */
class Token {
    /**
     * @ORM\Column(type="string")
     * @ORM\Id
     * @var string $id
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string $refresh_token
     */
    private $refresh_token;

    /**
     * @ORM\Column(type="datetime")
     * @var mixed $expirationDate
     */
    private $expirationDate;

    /**
     * @ORM\ManyToOne(targetEntity="Log210\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var \Log210\UserBundle\Entity\User $user
     **/
    private $user;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * @param string $refresh_token
     */
    public function setRefreshToken($refresh_token)
    {
        $this->refresh_token = $refresh_token;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param mixed $expirationDate
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return \Log210\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Log210\UserBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return boolean
     */
    public function isExpired() {
        return strtotime($this->getExpirationDate()->format('Y-m-d H:i:s')) < strtotime('now');
    }
}
