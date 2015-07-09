<?php

namespace Log210\APIBundle\Message\Response;

class MenuResponse {

    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var array $links
     */
    private $links;

    /**
     * @var array $plats
     */
    private $plats;

    /**
     * MenuResponse constructor.
     * @param int $id
     */
    public function __construct()
    {
        $this->links = array();
        $this->plats = array();
    }

    /**
     * @var array $links
     */
    private $warining;

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
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param array $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function getPlats()
    {
        return $this->plats;
    }

    /**
     * @param array $plats
     */
    public function setPlats($plats)
    {
        $this->plats = $plats;
    }

    public function setWarning($warning)
    {
        $this->warning = $warning;
    }

    public function getWarning()
    {
        return $this->warning;
    }
}
