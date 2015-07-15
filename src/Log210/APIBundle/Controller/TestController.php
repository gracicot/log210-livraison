<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-07-14
 * Time: 11:08 AM
 */

namespace Log210\APIBundle\Controller;


use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Client;
use Log210\LivraisonBundle\Entity\Restaurateur;
use Log210\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class TestController extends BaseController {

    /**
     * @Symfony\Component\Routing\Annotation\Route("/testUser", name="test_api_create_user")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createUserAction() {
        $user = new User();
        $user->setUsername("testUser");
        $user->setEmail("testUser@test.test");
        $user->setPlainPassword("test");
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        return new Response();
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/testClient", name="test_api_create_client")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createClientAction() {
        $client = new Client();
        $client->setUsername("testClient");
        $client->setEmail("testClient@test.test");
        $client->setPlainPassword("test");
        $client->setAddress("test");
        $this->getEntityManager()->persist($client);
        $this->getEntityManager()->flush();
        return new Response();
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/testRestaurateur", name="test_api_create_restaurateur")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createRestaurateurAction() {
        $restaurateur = new Restaurateur();
        $restaurateur->setUsername("testRestaurateur");
        $restaurateur->setEmail("testRestaurateur@test.test");
        $restaurateur->setPlainPassword("test");
        $restaurateur->setName("test");
        $restaurateur->setDescription("test");
        $this->getEntityManager()->persist($restaurateur);
        $this->getEntityManager()->flush();
        return new Response();
    }

}
