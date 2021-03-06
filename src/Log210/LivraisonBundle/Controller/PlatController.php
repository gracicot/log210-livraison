<?php

namespace Log210\LivraisonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Log210\CommonBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Log210\LivraisonBundle\Entity\Plat;
use Log210\LivraisonBundle\Entity\Menu;
use Log210\LivraisonBundle\Form\PlatType;

/**
 * Plat controller.
 *
 * @Route("/plat")
 * @Security("has_role('ROLE_RESTAURATEUR')")
 */
class PlatController extends Controller
{

    protected function getRoutes()
    {
        return [
            'update' => 'plat_update',
            'delete' => 'plat_delete',
            'create' => 'plat_create',
        ];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('Log210LivraisonBundle:Plat');
    }

    protected function getForm()
    {
        return new PlatType();
    }

    /**
     * Creates a new Plat entity.
     *
     * @Route("/", name="plat_create")
     * @Method("POST")
     * @Template("Log210LivraisonBundle:Plat:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new Plat entity.
     *
     * @Route("/new_modal/{menu}", name="plat_new_modal")
     * @Method("GET")
     * @Template("Log210CommonBundle::modalForm.html.twig")
     */
    public function newModalAction(Request $request, Menu $menu)
    {
        $entity = $this->getRepository()->makeEntity();
        $entity->setMenu($menu);

        $form = $this->createCreateForm($entity);

        return [
            'title' => 'create',
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Plat entity.
     *
     * @Route("/{id}/edit_modal", name="plat_edit_modal", options={"expose"=true})
     * @Method("GET")
     * @Template("Log210CommonBundle::modalForm.html.twig")
     */
    public function editModalAction(Request $request, $id)
    {
        return array_merge(['title' => 'plat'], $this->editAction($request, $id));
    }

    /**
     * Edits an existing Plat entity.
     *
     * @Route("/{id}", name="plat_update")
     * @Method("PUT")
     * @Template("Log210LivraisonBundle:Plat:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }
    /**
     * Deletes a Plat entity.
     *
     * @Route("/{id}", name="plat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Deletes a Restaurant entity.
     *
     * @Route("/delete/{id}", name="plat_delete_form", options={"expose"=true})
     * @Method("GET")
     * @Template("Log210CommonBundle::modalForm.html.twig")
     */
    public function deleteFormAction(Request $request, $id)
    {
        return parent::deleteFormAction($request, $id);
    }
}
