<?php

namespace Log210\LivraisonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    /**
     * Lists all Restaurant entities.
     *
     * @Route("/", name="restaurant")
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:index.html.twig")
     */
    public function indexAction()
    {
        $restaurantService = $this->get("livraisonBundle.restaurantService");

        $entities = $restaurantService->getAllRestaurants();

        return [
            'entities' => $entities,
        ];
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
        $restaurant = new Restaurant();
        $form = $this->createCreateForm($restaurant);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $restaurantService = $this->get("livraisonBundle.restaurantService");
            $restaurantService->createRestaurant($restaurant);

            return $this->redirect($this->generateUrl('restaurant_show', ['id' => $restaurant->getId()]));
        }

        return [
            'entity' => $restaurant,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Restaurant entity.
     *
     * @param Restaurant $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Restaurant $entity)
    {
        $form = $this->createForm(new RestaurantType(), $entity, [
            'action' => $this->generateUrl('restaurant_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Restaurant entity.
     *
     * @Route("/new", name="restaurant_new")
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:new.html.twig")
     */
    public function newAction()
    {
        $entity = new Restaurant();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Restaurant entity.
     *
     * @Route("/{id}", name="restaurant_show")
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:show.html.twig")
     */
    public function showAction($id)
    {

        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $entity = $restaurantService->getRestaurantById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restaurant entity.');
        }

        return [
            'entity'      => $entity
        ];
    }

    /**
     * Displays a form to edit an existing Restaurant entity.
     *
     * @Route("/{id}/edit", name="restaurant_edit")
     * @Method("GET")
     * @Template("Log210LivraisonBundle:Restaurant:edit.html.twig")
     */
    public function editAction($id)
    {
        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $entity = $restaurantService->getRestaurantById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restaurant entity.');
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
    * Creates a form to edit a Restaurant entity.
    *
    * @param Restaurant $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Restaurant $entity)
    {
        $form = $this->createForm(new RestaurantType(), $entity, [
            'action' => $this->generateUrl('restaurant_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
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
        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $entity = $restaurantService->getRestaurantById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restaurant entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $restaurantService->updateRestaurant($id, $entity);

            return $this->redirect($this->generateUrl('restaurant_show', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }
    
    /**
     * Deletes a Restaurant entity.
     *
     * @Route("/{id}", name="restaurant_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $restaurantService = $this->get("livraisonBundle.restaurantService");
            $entity = $restaurantService->getRestaurantById($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Restaurant entity.');
            }

            $restaurantService->deleteRestaurant($entity);
        }

        return $this->redirect($this->generateUrl('restaurant'));
    }
    
    /**
     * Deletes a Restaurant entity.
     *
     * @Route("/unsafe/{id}", name="restaurant_unsafe_delete")
     * @Method("GET")
     */
    public function unsafeDeleteAction(Request $request, $id)
    {
        $restaurantService = $this->get("livraisonBundle.restaurantService");
        $entity = $restaurantService->getRestaurantById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Restaurant entity.');
        }

        $restaurantService->deleteRestaurant($entity);

        return $this->redirect($this->generateUrl('restaurant'));
    }

    /**
     * Creates a form to delete a Restaurant entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('restaurant_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Supprimer'])
            ->getForm()
        ;
    }
}
