<?php

namespace Log210\APIBundle\Message\Response;

class RestaurantResponse {
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var string $address
     */
    private $address;

    /**
     * @var string $phone
     */
    private $phone;

    /**
     * @var string $restaurateur_href
     */
    private $restaurateur_href;

    /**
     * @var string $self_href
     */
    private $self_href;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getRestaurateur_href()
    {
        return $this->restaurateur_href;
    }

    /**
     * @param string $restaurateur_href
     */
    public function setRestaurateur_href($restaurateur_href)
    {
        $this->restaurateur_href = $restaurateur_href;
    }

    /**
     * @return string
     */
    public function getSelf_href()
    {
        return $this->self_href;
    }

    /**
     * @param string $self_href
     */
    public function setSelf_href($self_href)
    {
        $this->self_href = $self_href;
    }
}