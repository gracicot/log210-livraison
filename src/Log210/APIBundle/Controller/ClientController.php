<?php

namespace Log210\APIBundle\Controller;
use Log210\APIBundle\Entity\Token;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Client;
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

        $response = new Response('', Response::HTTP_CREATED, [
            "Location" => $this->generateUrl("client_api_get", [
                "id" => $newClientEntity->getId()
            ], true),
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Client:client.json.twig", [
            "client" => $newClientEntity
        ], $response);
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

        if (!$user instanceof Client)
            return new Response('', Response::HTTP_FORBIDDEN);

        $response = new Response('', Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Client:client.json.twig", [
            "client" => $user
        ], $response);
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

        if (!$user instanceof Client)
            return new Response('', Response::HTTP_FORBIDDEN);

        if ($user->getId() != $id)
            return new Response('', Response::HTTP_UNAUTHORIZED);

        $response = new Response('', Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Client:client.json.twig", [
            "client" => $user
        ], $response);
    }

    /**
     * @param string $id
     * @return Token
     */
    private function findTokenById($id) {
        return $this->getEntityManager()->getRepository('Log210APIBundle:Token')->find($id);
    }

}
