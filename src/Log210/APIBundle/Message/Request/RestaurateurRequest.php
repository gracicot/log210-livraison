<?php
/**
 * Created by PhpStorm.
 * User: Tomasz
 * Date: 27/05/2015
 * Time: 11:47 PM
 */

namespace Log210\APIBundle\Message\Request;


class RestaurateurRequest {
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string description
     */
    private $description;

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


}