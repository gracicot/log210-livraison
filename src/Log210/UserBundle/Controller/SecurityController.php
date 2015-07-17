<?php

namespace Log210\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * Registration controller.
 *
 *
 *
 */
class SecurityController extends BaseController
{
    public function loginAction()
    {
        $reponse=parent::loginAction();
        $request=$this->container->get('request');
        $username=$request->request->get("_username");
        $password=$request->request->get("_password");

        $tm = $this->get('log210_api.token_manager');
        $token=$tm->fromCredential($username,$password);

        $tokenJSON=$this->toJson($tm->toJson($token));
        $reponse->headers->setCookie(new Cookie('cookieToken', $tokenJSON));

        return $reponse;
    }
}