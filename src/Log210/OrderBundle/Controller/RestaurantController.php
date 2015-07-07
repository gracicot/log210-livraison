<?php

namespace Log210\OrderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Log210\CommonBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Log210\LivraisonBundle\Entity\Restaurant;

/**
 * Restaurant controller.
 *
 * @Route("/order")
 * @Security("has_role('ROLE_CLIENT')")
 */
class RestaurantController extends Controller
{
    protected function getRoutes()
    {
        return [
            'index' => 'restaurant_order',
            'show' => 'restaurant_order_show',

        ];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('Log210LivraisonBundle:Restaurant');
    }

    protected function getForm()
    {
        return new RestaurantType();
    }

    /**
     * Lists all Restaurant entities.
     *
     * @Route("/", name="restaurant_order")
     * @Method("GET")
     * @Template("Log210OrderBundle:Restaurant:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }



    /**
     * Finds and displays a Restaurant entity.
     *
     * @Route("/{id}", name="restaurant_order_show", options={"expose"=true})
     * @Method("GET")
     * @Template("Log210OrderBundle:Restaurant:show.html.twig")
     */
     public function showAction(Request $request, $id)
     {
         $entity = $this->getRepository()->find($id);

         if (!$entity) {
             throw $this->createNotFoundException('Unable to find entity.');
         }
         return [
             'entity'      => $entity,
             
         ];
     }


}
