<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-07-15
 * Time: 1:36 PM
 */

namespace Log210\APIBundle\Controller;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Livreur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LivreurController
 * @package Log210\APIBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/livreurs")
 */
class LivreurController extends BaseController {
    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="livreur_api_create")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createAction(Request $request) {
        $content = json_decode($request->getContent(), true);

        $newLivreurEntity = new Livreur();

        $newLivreurEntity->setUsername($content["username"]);
        $newLivreurEntity->setPlainPassword($content["password"]);
        $newLivreurEntity->setEmail($content["email"]);
        $newLivreurEntity->setName($content["name"]);

        $this->getEntityManager()->persist($newLivreurEntity);
        $this->getEntityManager()->flush();

        return new Response('', Response::HTTP_CREATED, array(
            "Location" => $this->generateUrl('livreur_api_get', array("id" => $newLivreurEntity->getId()), true)
        ));
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="livreur_api_get")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAction($id) {
        return new Response();
    }
}
