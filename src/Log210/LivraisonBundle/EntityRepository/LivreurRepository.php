<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-07-15
 * Time: 2:18 PM
 */

namespace Log210\LivraisonBundle\EntityRepository;


use Log210\CommonBundle\EntityRepository\EntityRepository;
use Log210\LivraisonBundle\Entity\Client;
use Log210\LivraisonBundle\Entity\Livreur;

class LivreurRepository extends EntityRepository {

    public function makeEntity() {
        return new Livreur();
    }
}
