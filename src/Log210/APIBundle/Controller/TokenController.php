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

        if (array_key_exists("refresh_token", $decodedRequest)) {
            $token = $this->findTokenByRefreshToken($decodedRequest["refresh_token"]);

            if (is_null($token))
                return new Response('{"error": "Refresh token invalid"}', Response::HTTP_BAD_REQUEST, array(
                    'Content-Type' => 'application/json'));

            $newToken = new Token();
            $newToken->setId(bin2hex(openssl_random_pseudo_bytes(30)));
            $newToken->setRefreshToken(bin2hex(openssl_random_pseudo_bytes(30)));
            $dateTime = new \DateTime();
            $dateTime->add(new \DateInterval("PT8H"));
            $newToken->setExpirationDate($dateTime);
            $newToken->setUser($token->getUser());
            $this->getEntityManager()->persist($newToken);
            $this->getEntityManager()->remove($token);
            $this->getEntityManager()->flush();

            $tokenResponse = $this->toTokenResponse($newToken);
            return new Response($this->toJson($tokenResponse), Response::HTTP_CREATED, array("Location" => $this
                ->generateUrl('token_api_get', array("id" => $newToken->getId()), true), 'Content-Type' =>
                'application/json'));
        } else {
            if (!array_key_exists("username", $decodedRequest))
                return new Response('{"error": "username field missing"}', Response::HTTP_BAD_REQUEST, array(
                    'Content-Type' => 'application/json'));
            else if (!array_key_exists("password", $decodedRequest))
                return new Response('{"error": "password field missing"}', Response::HTTP_BAD_REQUEST, array(
                    'Content-Type' => 'application/json'));
            $passwordOk = $this->authentificateUser($decodedRequest["username"], $decodedRequest["password"]);

            if ($passwordOk) {
                $username = $decodedRequest["username"];

                $token = new Token();
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval("PT8H"));
                $token->setId(bin2hex(openssl_random_pseudo_bytes(30)));
                $token->setRefreshToken(bin2hex(openssl_random_pseudo_bytes(30)));
                $token->setExpirationDate($dateTime);
                $token->setUser($this->findUserByUsername($username));

                $this->getEntityManager()->persist($token);
                $this->getEntityManager()->flush();

                $tokenResponse = $this->toTokenResponse($token);
                return new Response($this->toJson($tokenResponse), Response::HTTP_CREATED, array("Location" => $this
                    ->generateUrl('token_api_get', array("id" => $token->getId()), true), 'Content-Type' =>
                    'application/json'));
            } else
                return new Response('', Response::HTTP_UNAUTHORIZED);
        }
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

        return new Response($this->toJson($this->toTokenResponse($token)), Response::HTTP_OK, array('Content-Type' =>
            'application/json'));
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
