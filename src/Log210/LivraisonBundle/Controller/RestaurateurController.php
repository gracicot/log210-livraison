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
use Log210\LivraisonBundle\Entity\Restaurateur;
use Log210\LivraisonBundle\Form\RestaurateurType;
use Log210\LivraisonBundle\Form\RestaurateurNewType;
use Log210\LivraisonBundle\Form\RestaurateurUserType;
use Log210\UserBundle\Entity\User;
use IllegalArgumentException;

/**
 * Restaurateur controller.
 *
 * @Route("/restaurateur")
 * @Security("has_role('ROLE_ENTREPRENEUR')")
 */
class RestaurateurController extends Controller
{
    protected function getRoutes()
    {
        return [
            'show' => 'restaurateur_show',
            'new' => 'restaurateur_new',
            'index' => 'restaurateur',
            'update' => 'restaurateur_update',
            'delete' => 'restaurateur_delete',
            'create' => 'restaurateur_create',
            'edit' => 'restaurateur_edit',
            'update_user' => 'restaurateur_update_user'
        ];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('Log210LivraisonBundle:Restaurateur');
    }

    protected function getForm()
    {
        return new RestaurateurType();
    }

    /**
     * Lists all Restaurateur entities.
     *
     * @Route("/", name="restaurateur")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
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
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new Restaurateur entity.
     *
     * @Route("/new", name="restaurateur_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = $this->getRepository()->makeEntity();
        $form   = $this->createCreateForm($entity, new RestaurateurNewType);

        return [ 'form' => $form->createView() ];
    }

    /**
     * Finds and displays a Restaurateur entity.
     *
     * @Route("/{id}", name="restaurateur_show", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        return parent::showAction($request, $id);
    }

    /**
     * Displays a form to edit an existing Restaurateur entity.
     *
     * @Route("/{id}/edit", name="restaurateur_edit", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
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
        return parent::updateAction($request, $id);
    }

    /**
     * Deletes a Restaurateur entity.
     *
     * @Route("/{id}", name="restaurateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Deletes a Restaurant entity.
     *
     * @Route("/delete/{id}", name="restaurateur_delete_form", options={"expose"=true})
     * @Method("GET")
     * @Template("Log210CommonBundle::modalForm.html.twig")
     */
    public function deleteFormAction(Request $request, $id)
    {
        return parent::deleteFormAction($request, $id);
    }

    /**
     * Deletes a Restaurant entity.
     *
     * @Route("/fetch_user/{restaurateur}", name="restaurateur_fetch_user")
     * @Method("GET")
     */
    public function fetchUserAction(Restaurateur $restaurateur)
    {
        $user = $restaurateur->getUser();
        return $this->jsonResponse(new Response(json_encode([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'enabled' => $user->isEnabled()
        ])));
    }

    /**
     * Edits an existing Restaurateur entity.
     *
     * @Route("/enable_user/{user}/{enabled}", name="restaurateur_activate_user", options={"expose"=true})
     * @Method("GET")
     */
    public function activateUserAction(User $user, $enabled)
    {
        if ((int)$enabled === 1 || (int)$enabled === 0) {
            $user->setEnabled((int)$enabled === 1);
            $em = $this->getEntityManager();
            $em->flush();
        } else {
            throw new IllegalArgumentException("The enabled parameter is invalid");
        }
        return $this->jsonResponse(new Response(json_encode(['success' => true])));
    }
}
