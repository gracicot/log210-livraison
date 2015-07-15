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

class ClientRepository extends EntityRepository {

    public function makeEntity() {
        return new Client();
    }
}
