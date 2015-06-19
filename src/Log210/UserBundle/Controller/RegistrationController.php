<?php

namespace Log210\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
/**
 * Registration controller.
 *
 *
 * @Secrity("has_role('ROLE_USER')")
 */
class RegistrationController extends BaseController
{

}
