<?php

namespace Log210\LivraisonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Log210\LivraisonBundle\Entity\Restaurateur;
use Log210\LivraisonBundle\Form\RestaurateurType;

/**
 * Restaurateur controller.
 *
 * @Route("/restaurateur")
 */
class RestaurateurController extends Controller
{

    /**
     * Lists all Restaurateur entities.
     *
     * @Route("/", name="restaurateur")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Log210LivraisonBundle:Restaurateur')->findAll();

        return [
            'entities' => $entities,
        ];
    }
    /**
     * Creates a new Restaurateur entity.
     *
     * @Route("/", name="restaurateur_create")
     * @Method("POST")
     * @Template("Log210LivraisonBundle:Restaurateur:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Restaurateur();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('restaurateur_show', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Restaurateur entity.
     *
     * @param Restaurateur $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Restaurateur $entity)
    {
        $form = $this->createForm(new RestaurateurType(), $entity, [
            'action' => $this->generateUrl('restaurateur_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Restaurateur entity.
     *
     * @Route("/new", name="restaurateur_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Restaurateur();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Restaurateur entity.
     *
     * @Route("/{id}", name="restaurateur_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restaurateur entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Restaurateur entity.
     *
     * @Route("/{id}/edit", name="restaurateur_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restaurateur entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Restaurateur entity.
    *
    * @param Restaurateur $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Restaurateur $entity)
    {
        $form = $this->createForm(new RestaurateurType(), $entity, [
            'action' => $this->generateUrl('restaurateur_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }
    /**
     * Edits an existing Restaurateur entity.
     *
     * @Route("/{id}", name="restaurateur_update")
     * @Method("PUT")
     * @Template("Log210LivraisonBundle:Restaurateur:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restaurateur entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('restaurateur_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }
    /**
     * Deletes a Restaurateur entity.
     *
     * @Route("/{id}", name="restaurateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Log210LivraisonBundle:Restaurateur')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Restaurateur entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('restaurateur'));
    }

    /**
     * Creates a form to delete a Restaurateur entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('restaurateur_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm()
        ;
    }
}
