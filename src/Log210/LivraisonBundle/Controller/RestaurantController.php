<?php

namespace Log210\LivraisonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Log210\CommonBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Log210\LivraisonBundle\Entity\Restaurant;
use Log210\LivraisonBundle\Form\RestaurantType;

/**
 * Restaurant controller.
 *
 * @Route("/restaurant")
 */
class RestaurantController extends Controller
{
    protected function getRoutes()
    {
        return [
            'index' => 'restaurant',
            'show' => 'restaurant_show',
            'new' => 'restaurant_new',
            'update' => 'restaurant_update',
            'delete' => 'restaurant_delete',
            'create' => 'restaurant_create',
            'edit' => 'restaurant_edit'
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
     * @Route("/", name="restaurant")
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    /**
     * Creates a new Restaurant entity.
     * @param Request $request the request
     * @return Response the response
     *
     * @Route("", name="restaurant_create")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new Restaurant entity.
     *
     * @Route("/new", name="restaurant_new")
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:new.html.twig")
     */
    public function newAction(Request $request)
    {
        return parent::newAction($request);
    }

    /**
     * Finds and displays a Restaurant entity.
     *
     * @Route("/{id}", name="restaurant_show", options={"expose"=true})
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:show.html.twig")
     */
    public function showAction(Request $request, $id)
    {
        return parent::showAction($request, $id);
    }

    /**
     * Displays a form to edit an existing Restaurant entity.
     *
     * @Route("/{id}/edit", name="restaurant_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:edit.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     * Displays a form to edit an existing Restaurant entity.
     *
     * @Route("/{id}/edit_partial", name="restaurant_edit_partial")
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:edit_partial.html.twig")
     */
    public function editPartialAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     * Edits an existing Restaurant entity.
     *
     * @Route("/{id}", name="restaurant_update")
     * @Method("PUT")
     * @Template("Log210LivraisonBundle:Restaurant:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * Deletes a Restaurant entity.
     *
     * @Route("/{id}", name="restaurant_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }


    /**
     * Deletes a Restaurant entity.
     *
     * @Route("/delete/{id}", name="restaurant_delete_form", options={"expose"=true})
     * @Method("GET")
     * @Template("Log210CommonBundle::modalForm.html.twig")
     */
    public function deleteFormAction(Request $request, $id)
    {
        return parent::deleteFormAction($request, $id);
    }
}
