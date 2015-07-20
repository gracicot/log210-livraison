<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 15-07-15
 * Time: 1:36 PM
 */

namespace Log210\APIBundle\Controller;
use Log210\APIBundle\Entity\Token;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Commande;
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
        $livreurEntity = $this->getEntityManager()->getRepository("Log210LivraisonBundle:Livreur")->find($id);
        if (is_null($livreurEntity))
            return new Response("", Response::HTTP_NOT_FOUND);

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Livreur:livreur.json.twig", [
            "livreur" => $livreurEntity
        ], $response);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/me", name="livreur_api_get_me")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getMeAction(Request $request) {
        $access_token = $request->headers->get("Authorization");
        if (is_null($access_token))
            return new Response('No Authorization Header', Response::HTTP_UNAUTHORIZED);

        $token = $this->findTokenById($access_token);
        if (is_null($token))
            return new Response('Invalid Token', Response::HTTP_UNAUTHORIZED);

        if ($token->isExpired())
            return new Response('Expired Token', Response::HTTP_UNAUTHORIZED);

        $user = $token->getUser();

        if (!$user instanceof Livreur)
            return new Response("", Response::HTTP_FORBIDDEN);

        $response = new Response("", Response::HTTP_OK, [
            "Content-Type" => "application/json"
        ]);
        return $this->render("Log210APIBundle:Livreur:livreur.json.twig", [
            "livreur" => $user
        ], $response);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/me/commandes", name="livreur_api_link_commande")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function linkCommandeAction(Request $request) {
        $access_token = $request->headers->get("Authorization");

        $user = null;

        if (!is_null($access_token)) {
            $token = $this->findTokenById($access_token);
            if (is_null($token))
                return new Response("", Response::HTTP_UNAUTHORIZED);

            if ($token->isExpired())
                return new Response("", Response::HTTP_UNAUTHORIZED);

            $user = $token->getUser();
        } else {
            $user = $this->getUser();
        }

        if (is_null($user))
            return new Response("", Response::HTTP_UNAUTHORIZED);

        if (!$user instanceof Livreur)
            return new Response("", Response::HTTP_FORBIDDEN);

        $commandeRequest = json_decode($request->getContent(), true);
        $commandeEntity = $this->findComandeById($commandeRequest["commande_id"]);
        if (is_null($commandeEntity))
            return new Response("", Response::HTTP_UNPROCESSABLE_ENTITY);

        if (!$commandeEntity->getEtat() === Commande::ETAT_PRETE)
            return new Response("", Response::HTTP_UNPROCESSABLE_ENTITY);

        if (!is_null($commandeEntity->getLivreur()))
            return new Response("", Response::HTTP_UNPROCESSABLE_ENTITY);

        $commandeEntity->setLivreur($user);
        $commandeEntity->setDateHeureLivraison(new \DateTime());

        $this->getEntityManager()->flush();

        return new Response("", Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $id
     * @return Token
     */
    private function findTokenById($id) {
        return $this->getEntityManager()->getRepository('Log210APIBundle:Token')->find($id);
    }

    /**
     * @param int $id
     * @return Commande
     */
    private function findComandeById($id) {
        return $this->getEntityManager()->getRepository("Log210LivraisonBundle:Commande")->find($id);
    }
}
