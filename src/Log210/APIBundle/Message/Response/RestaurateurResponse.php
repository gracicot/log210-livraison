<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-05-27
 * Time: 4:21 PM
 */

namespace Log210\APIBundle\Message\Response;


class RestaurateurResponse {
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
     * @var string $restaurants_href
     */
    private $restaurants_href;

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
    public function getRestaurants_href()
    {
        return $this->restaurants_href;
    }

    /**
     * @param string $restaurants_href
     */
    public function setRestaurants_href($restaurants_href)
    {
        $this->restaurants_href = $restaurants_href;
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