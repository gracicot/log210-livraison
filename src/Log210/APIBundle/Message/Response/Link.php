<?php

namespace Log210\APIBundle\Message\Response;

class Link {

    /**
     * @var string $rel
     */
    private $rel;

    /**
     * @var string $href
     */
    private $href;

    /**
     * @param string $rel
     * @param string $href
     */
    function __construct($rel, $href)
    {
        $this->rel = $rel;
        $this->href = $href;
    }

    /**
     * @return string
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * @param string $rel
     */
    public function setRel($rel)
    {
        $this->rel = $rel;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param string $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }
}
