<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-07-17
 * Time: 10:42 AM
 */

namespace Log210\APIBundle\Tests\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ClientControllerTest extends WebTestCase {
    public function testCreateAction() {
        $content = [
            "username" => bin2hex(openssl_random_pseudo_bytes(5)),
            "email" => bin2hex(openssl_random_pseudo_bytes(5)) . "@" . bin2hex(openssl_random_pseudo_bytes(5)) . "." . bin2hex(openssl_random_pseudo_bytes(2)),
            "password" => bin2hex(openssl_random_pseudo_bytes(5)),
            "address" => bin2hex(openssl_random_pseudo_bytes(5)),
            "phoneNumber" => "1234567890"
        ];

        $client = static::createClient();
        $client->request("POST", "/api/clients", [], [], [], json_encode($content));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $responseContent = json_decode($response->getContent(), true);

        $this->assertArrayHasKey("id", $responseContent);
        $this->assertEquals($content["username"], $responseContent["username"]);
        $this->assertEquals($content["email"], $responseContent["email"]);
        $this->assertEquals($content["address"], $responseContent["address"]);
        $this->assertEquals($content["phoneNumber"], $responseContent["phone_number"]);
    }
}
