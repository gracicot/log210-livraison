<?php

namespace Log210\CommonBundle\Controller;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 

use InvalidArgumentException;

/**
 *  CRUDController.
 */
abstract class Controller extends BaseController
{
    protected abstract function getRoutes();
    protected abstract function getRepository();
    protected abstract function getForm();

    protected final function getRoute($route)
    {
        $routes = $this->getRoutes();

        if (array_key_exists($route, $routes)) {
            return $routes[$route];
        } else {
            throw new InvalidArgumentException('Error: No route named ' . $route . ' is provided by getRoute().');
        }
    }

    /**
     * Lists all entities.
     */
    public function indexAction(Request $request)
    {
        $entities = $this->getRepository()->findAll();

        return [
            'entities' => $entities,
            'routes' => $this->getRoutes()
        ];
    }

    public function listingAction(Request $request)
    {
        $entities = $this->getRepository()->findAll();

        return [
            'entities' => $this->getEncoder($entities),
        ];
    }

    /**
     * Creates a new entity.
     */
    public function createAction(Request $request)
    {
        $service = $this->getRepository();
        $entity = $this->getRepository()->makeEntity();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl($this->getRoute('show'), ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a  entity.
     *
     * @param $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm($entity)
    {
        $form = $this->createForm($this->getForm(), $entity, [
            'action' => $this->generateUrl($this->getRoute('create')),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new entity.
     */
    public function newAction(Request $request)
    {
        $entity = $this->getRepository()->makeEntity();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a entity.
     */
    public function showAction(Request $request, $id)
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing entity.
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a entity.
    */
    protected function createEditForm($entity, AbstractType $form = null, $route = 'update')
    {
        $form = $this->createForm(($form !== null ? $form:$this->getForm()), $entity, [
            'action' => $this->generateUrl($this->getRoute($route), ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'save']);

        return $form;
    }
    /**
     * Edits an existing entity.
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getEntityManager();
            $em->flush();

            return $this->redirect($this->generateUrl($this->getRoute('show'), ['id' => $id]));
        }

        return [
            'entity' => $entity,
            'form' => $editForm->createView(),
        ];
    }
    
    /**
     * Deletes a entity.
     */
    public function deleteFormAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        return [
            'title' => 'delete',
            'form' => $form->createView()
        ];
    }

    /**
     * Deletes a entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->getRepository()->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find entity.');
            }

            $em = $this->getEntityManager();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl($this->getRoute('index')));
    }

    /**
     * Creates a form to delete a entity by id.
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($this->getRoute('delete'), ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'delete'])
            ->getForm()
        ;
    }
}
