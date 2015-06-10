<?php

namespace Log210\LivraisonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Log210\CommonBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Log210\LivraisonBundle\Entity\Menu;
use Log210\LivraisonBundle\Entity\Restaurant;
use Log210\LivraisonBundle\Form\MenuType;

/**
 * Menu controller.
 *
 * @Route("/menu")
 */
class MenuController extends Controller
{
    protected function getRoutes()
    {
        return [
            'show' => 'menu_show',
            'new' => 'menu_new',
            'update' => 'menu_update',
            'delete' => 'menu_delete',
            'create' => 'menu_create',
            'edit' => 'menu_edit'
        ];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('Log210LivraisonBundle:Menu');
    }

    protected function getForm()
    {
        return new MenuType();
    }
    /**
     * Lists all Menu entities.
     *
     * @Route("/", name="menu")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    /**
     * Creates a new Menu entity.
     *
     * @Route("/", name="menu_create")
     * @Method("POST")
     * @Template("Log210LivraisonBundle:Menu:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new Menu entity.
     *
     * @Route("new/{restaurant}", name="menu_new")
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Menu:new.html.twig")
     */
    public function newLierAction(Request $request, Restaurant $restaurant)
    {
        $entity = $this->getRepository()->makeEntity();
        $entity->setRestaurant($restaurant);
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Menu entity.
     *
     * @Route("/{id}", name="menu_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        return parent::showAction($request, $id);
    }

    /**
     * Displays a form to edit an existing Menu entity.
     *
     * @Route("/{id}/edit", name="menu_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }


    /**
     * Edits an existing Menu entity.
     *
     * @Route("/{id}", name="menu_update")
     * @Method("PUT")
     * @Template("Log210LivraisonBundle:Menu:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }
    /**
     * Deletes a Menu entity.
     *
     * @Route("/{id}", name="menu_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Deletes a Restaurant entity.
     *
     * @Route("/delete/{id}", name="menu_delete_form", options={"expose"=true})
     * @Method("GET")
     * @Template("Log210CommonBundle::modalForm.html.twig")
     */
    public function deleteFormAction(Request $request, $id)
    {
        return parent::deleteFormAction($request, $id);
    }
}
