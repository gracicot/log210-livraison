<?php

namespace Log210\APIBundle\Controller;
use FOS\UserBundle\Model\UserManager;
use Log210\APIBundle\Entity\Token;
use Log210\APIBundle\Message\Response\Link;
use Log210\APIBundle\Message\Response\ClientResponse;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Client;
use Log210\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package Log210\LivraisonBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/clients")
 */
class ClientController extends BaseController {

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="client_api_create")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createAction(Request $request) {
        $content = json_decode($request->getContent(), true);

        $newClientEntity = new Client();

        $newClientEntity->setUsername($content["username"]);
        $newClientEntity->setEmail($content["email"]);
        $newClientEntity->setPlainPassword($content["password"]);
        $newClientEntity->setAddress($content["address"]);
        $newClientEntity->setPhoneNumber($content["phoneNumber"]);
        $newClientEntity->setEnabled(true);

        $this->getEntityManager()->persist($newClientEntity);
        $this->getEntityManager()->flush();
        return new Response('', Response::HTTP_CREATED, array('Location' => $this
            ->generateUrl('client_api_get', array("id" => $newClientEntity->getId()), true)));
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/me", name="client_api_get_me")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getMeAction(Request $request) {
        $access_token = $request->headers->get("Authorization");
        if (is_null($access_token))
            return new Response('No Authorization Header', Response::HTTP_UNAUTHORIZED);

        $token = $this->findTokenById($access_token);
        if (is_null($token))
            return new Response('Invalid Token', Response::HTTP_UNAUTHORIZED);

        if ($token->isExpired())
            return new Response('Expired Token', Response::HTTP_UNAUTHORIZED);

        $user = $token->getUser();

        $client = $this->getClientById($user->getId());
        if (is_null($client))
            return new Response('', Response::HTTP_FORBIDDEN);

        $clientResponse = $this->toClientResponse($client);

        return new Response($this->toJson($clientResponse), Response::HTTP_OK, array('Content-Type' => 'application/json'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="client_api_get")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAction($id, Request $request) {
        $access_token = $request->headers->get("Authorization");
        if (is_null($access_token))
            return new Response('No Authorization Header', Response::HTTP_UNAUTHORIZED);

        $token = $this->findTokenById($access_token);
        if (is_null($token))
            return new Response('Invalid Token', Response::HTTP_UNAUTHORIZED);

        if ($token->isExpired())
            return new Response('Expired Token', Response::HTTP_UNAUTHORIZED);

        $user = $token->getUser();

        $client = $this->getClientById($user->getId());
        if (is_null($client))
            return new Response('', Response::HTTP_FORBIDDEN);

        if ($user->getId() != $id)
            return new Response('', Response::HTTP_UNAUTHORIZED);

        $clientResponse = $this->toClientResponse($client);

        return new Response($this->toJson($clientResponse), Response::HTTP_OK, array('Content-Type' => 'application/json'));
    }

    /**
     * @param string $id
     * @return Token
     */
    private function findTokenById($id)
    {
        return $this->getEntityManager()->getRepository('Log210APIBundle:Token')->find($id);
    }

    /**
     * @param Client $client
     * @return ClientResponse
     */
    private function toClientResponse($client)
    {
        $clientResponse = new ClientResponse();
        $clientResponse->setId($client->getId());
        $clientResponse->setUsername($client->getUsername());
        $clientResponse->setEmail($client->getEmail());
        $clientResponse->setAddress($client->getAddress());
        $clientResponse->setPhone_number($client->getPhoneNumber());
        $clientResponse->setRoles($client->getRoles());
        $clientResponse->setLinks(array(new Link('self', '/api/clients/' . $client->getId())));
        return $clientResponse;
    }

    /**
     * @param $id
     * @return Client
     */
    private function getClientById($id) {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Client')->find($id);
    }

}
