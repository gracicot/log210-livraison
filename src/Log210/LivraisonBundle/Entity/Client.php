<?php

namespace Log210\LivraisonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Client
 * @package Log210\LivraisonBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client {

    /**
     * @var int $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
}
