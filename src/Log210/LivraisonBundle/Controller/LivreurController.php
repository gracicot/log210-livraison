<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-07-15
 * Time: 2:04 PM
 */

namespace Log210\LivraisonBundle\Controller;


use Log210\CommonBundle\Controller\Controller;
use Log210\LivraisonBundle\Form\LivreurType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LivreurController
 * @package Log210\LivraisonBundle\Controller
 * @Route("/livreur")
 */
class LivreurController extends Controller {

    protected function getRoutes() {
        return [
            "new" => "livreur_new",
            "create" => "livreur_create",
            "show" => "fos_user_security_login"
        ];
    }

    protected function getRepository() {
        return $this->getDoctrine()->getRepository('Log210LivraisonBundle:Livreur');
    }

    protected function getForm() {
        return new LivreurType();
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/", name="livreur_create")
     * @Method("POST")
     * @Template("Log210LivraisonBundle:Livreur:new.html.twig")
     */
    public function createAction(Request $request) {
        return parent::createAction($request);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/new", name="livreur_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request) {
        return parent::newAction($request);
    }
}
