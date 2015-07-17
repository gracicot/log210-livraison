<?php

namespace Log210\APIBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager;
use Log210\APIBundle\Entity\Token;
use Log210\APIBundle\Message\Response\Link;
use Log210\APIBundle\Message\Response\TokenResponse;
use Log210\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Log210\CommonBundle\Controller\BaseController;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class TokenController
 * @package Log210\LivraisonBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/tokens")
 */
class TokenController extends BaseController {

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="token_api_create")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createAction(Request $request) {
        $decodedRequest = json_decode($request->getContent(), true);

        $newToken = null;

        if (array_key_exists("refresh_token", $decodedRequest)) {
            $oldToken = $this->findTokenByRefreshToken($decodedRequest["refresh_token"]);

            if (is_null($oldToken))
                return new Response('{"error": "Refresh token invalid"}', Response::HTTP_BAD_REQUEST, array(
                    'Content-Type' => 'application/json'));

            $newToken = new Token();
            $newToken->setUser($oldToken->getUser());

            $this->getEntityManager()->remove($oldToken);
        } else {
            $passwordOk = $this->authentificateUser($decodedRequest["username"], $decodedRequest["password"]);

            if ($passwordOk) {
                $username = $decodedRequest["username"];

                $newToken = new Token();
                $newToken->setUser($this->findUserByUsername($username));
            } else
                return new Response('', Response::HTTP_UNAUTHORIZED);
        }

        $newToken->setId(bin2hex(openssl_random_pseudo_bytes(30)));
        $newToken->setRefreshToken(bin2hex(openssl_random_pseudo_bytes(30)));
        $dateTime = new \DateTime();
        $dateTime->add(new \DateInterval("PT8H"));
        $newToken->setExpirationDate($dateTime);

        $this->getEntityManager()->persist($newToken);
        $this->getEntityManager()->flush();

        $response = new Response('', Response::HTTP_CREATED, [
            "Location" => $this->generateUrl('token_api_get', ["id" => $newToken->getId()], true),
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Token:token.json.twig", ["token" => $newToken], $response);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="token_api_get")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAction($id) {
        $token = $this->findTokenById($id);

        if (is_null($token))
            return new Response('', Response::HTTP_NOT_FOUND);

        $response = new Response('', Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
        return $this->render("Log210APIBundle:Token:token.json.twig", ["token" => $token], $response);
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="token_api_delete")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("DELETE")
     */
    public function deleteAction($id) {
        $token = $this->findTokenById($id);

        if (is_null($token))
            return new Response('', Response::HTTP_NOT_FOUND);

        $this->getEntityManager()->remove($token);
        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->get('fos_user.user_manager');
    }

    /**
     * @param UserInterface $user
     * @return PasswordEncoderInterface
     */
    private function getEncoder(UserInterface $user)
    {
        return $this->get('security.encoder_factory')->getEncoder($user);
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    private function authentificateUser($username, $password)
    {
        $userManager = $this->getUserManager();

        $user = $userManager->findUserByUsername($username);
        if (is_null($user))
             return false;

        $encoder = $this->getEncoder($user);

        return $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt()) == 1 ? true : false;
    }

    /**
     * @param Token $token
     * @return TokenResponse
     */
    private function toTokenResponse($token)
    {
        $tokenResponse = new TokenResponse();
        $tokenResponse->setAccess_token($token->getId());
        $tokenResponse->setRefresh_token($token->getRefreshToken());
        $dateNow = strtotime("now");
        $dateExpiry = strtotime($token->getExpirationDate()->format('Y-m-d H:i:s'));
        $tokenResponse->setExpires_in($dateExpiry - $dateNow);
        $tokenResponse->setLinks(array(new Link("self", "/api/tokens/" . $token->getId())));
        return $tokenResponse;
    }

    /**
     * @param string $id
     * @return Token
     */
    private function findTokenById($id) {
        return $this->getEntityManager()->getRepository('Log210APIBundle:Token')->find($id);
    }

    /**
     * @param string $refresh_token
     * @return Token
     */
    private function findTokenByRefreshToken($refresh_token) {
        return $this->getEntityManager()->getRepository('Log210APIBundle:Token')->findOneBy(array("refresh_token" => $refresh_token));
    }

    /**
     * @param string $username
     * @return User
     */
    private function findUserByUsername($username) {
        return $this->getUserManager()->findUserByUsername($username);
    }

}
