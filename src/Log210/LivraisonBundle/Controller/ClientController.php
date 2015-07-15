<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-07-15
 * Time: 2:04 PM
 */

namespace Log210\LivraisonBundle\Controller;


use Log210\CommonBundle\Controller\Controller;
use Log210\LivraisonBundle\Form\ClientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ClientController
 * @package Log210\LivraisonBundle\Controller
 * @Route("/client")
 */
class ClientController extends Controller {

    protected function getRoutes() {
        return [
            "new" => "client_new",
            "create" => "client_create",
            "show" => "fos_user_security_login"
        ];
    }

    protected function getRepository() {
        return $this->getDoctrine()->getRepository('Log210LivraisonBundle:Client');
    }

    protected function getForm() {
        return new ClientType();
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/", name="client_create")
     * @Method("POST")
     * @Template("Log210LivraisonBundle:Client:new.html.twig")
     */
    public function createAction(Request $request) {
        return parent::createAction($request);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/new", name="client_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request) {
        if (!is_null($this->getUser()))
            return $this->redirectToRoute("index");
        return parent::newAction($request);
    }
}
