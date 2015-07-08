<?php

namespace Log210\APIBundle\Controller;
use FOS\UserBundle\Model\UserManager;
use Log210\APIBundle\Entity\Token;
use Log210\APIBundle\Message\Response\Link;
use Log210\APIBundle\Message\Response\UserResponse;
use Log210\CommonBundle\Controller\BaseController;
use Log210\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package Log210\LivraisonBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/profiles")
 */
class UserController extends BaseController {

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="user_api_create")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createAction(Request $request) {
        $content = json_decode($request->getContent(), true);

        $newUser = $this->createUser();

        $newUser->setUsername($content["username"]);
        $newUser->setEmail($content["email"]);
        $newUser->setPlainPassword($content["password"]);
        $newUser->setEnabled(true);

        $this->getUserManager()->updateUser($newUser);
        return new Response('', Response::HTTP_CREATED, array('Location' => $this
            ->generateUrl('user_api_get', array("id" => $newUser->getId()), true)));
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/me", name="user_api_get_me")
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

        $userResponse = $this->toUserResponse($user);

        return new Response($this->toJson($userResponse), Response::HTTP_OK, array('Content-Type' => 'application/json'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="user_api_get")
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
        if ($user->getId() != $id) {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }

        $userResponse = $this->toUserResponse($user);

        return new Response($this->toJson($userResponse), Response::HTTP_OK, array('Content-Type' => 'application/json'));
    }

    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->get('fos_user.user_manager');
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
     * @return User
     */
    private function createUser()
    {
        return $this->getUserManager()->createUser();
    }

    /**
     * @param User $user
     * @return UserResponse
     */
    private function toUserResponse($user)
    {
        $userResponse = new UserResponse();
        $userResponse->setId($user->getId());
        $userResponse->setUsername($user->getUsername());
        $userResponse->setEmail($user->getEmail());
        $userResponse->setRoles($user->getRoles());
        $userResponse->setLinks(array(new Link('self', '/api/profiles/' . $user->getId())));
        return $userResponse;
    }

}
