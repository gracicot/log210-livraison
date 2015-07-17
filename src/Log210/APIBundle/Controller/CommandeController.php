<?php

namespace Log210\APIBundle\Controller;
use Log210\APIBundle\Entity\Token;
use Log210\APIBundle\Mapper\CommandeMapper;
use Log210\APIBundle\Message\Request\CommandeRequest;
use Log210\APIBundle\Message\Response\CommandeResponse;
use Log210\CommonBundle\Controller\BaseController;
use Log210\LivraisonBundle\Entity\Commande;
use Log210\LivraisonBundle\Entity\CommandePlat;
use Log210\LivraisonBundle\Entity\Plat;
use Log210\LivraisonBundle\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class CommandeController
 * @package Log210\APIBundle\Controller
 * @Symfony\Component\Routing\Annotation\Route("/commandes")
 */
class CommandeController extends BaseController {

    /**
     * @param Request $request
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("", name="commande_api_create")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("POST")
     */
    public function createAction(Request $request) {
        $access_token = $request->headers->get("Authorization");

        $user = null;

        if ($access_token !== null) {
            $token = $this->findTokenById($access_token);
            
            if (is_null($token)) {
                return new Response('Invalid Token', Response::HTTP_UNAUTHORIZED);
            }

            if ($token->isExpired()) {
                return new Response('Expired Token', Response::HTTP_UNAUTHORIZED);
            }

            $user = $token->getUser();
        } else {
            $user = $this->getUser();
        }

        if ($user === null) {
            return new Response('No Authorization', Response::HTTP_UNAUTHORIZED);
        }

        $commandeRequest = $this->convertCommandeRequest($request->getContent());

        $commandeRequest->setDate_heure(new \DateTime($commandeRequest->getDate_heure()));

        $commandeEntity = new Commande();
        $commandeEntity->setAdresse($commandeRequest->getAdresse());
        $commandeEntity->setDateHeure($commandeRequest->getDate_heure());
        $commandeEntity->setRestaurant($this->getRestaurantById($commandeRequest->getRestaurant_id()));
        $commandeEntity->setClient($user);

        $this->getEntityManager()->persist($commandeEntity);

        $this->getEntityManager()->flush();

        foreach ($commandeRequest->getCommande_plats() as $commande_plat) {
            $commandePlatEntity = new CommandePlat();
            $commandePlatEntity->setCommande($commandeEntity);
            $commandePlatEntity->setQuantity($commande_plat['quantity']);
            $commandePlatEntity->setPlat($this->findPlatById($commande_plat['plat_id']));
            $commandeEntity->addCommandePlat($commandePlatEntity);

            $this->getEntityManager()->persist($commandePlatEntity);
        }

        $this->getEntityManager()->flush();

        $commandeResponse = CommandeMapper::convertCommandeEntityToCommandeResponse($commandeEntity);

        $transport = \Swift_SmtpTransport::newInstance('smtp.mail.com', 587)->setUsername("log210ete201501@mail.com")->setPassword("fuhrmanator")->setEncryption("tls");
        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance('Confirmation de commande')->setFrom("log210ete201501@mail.com")->setTo(trim(strtoupper($user->getEmail())))->setBody("Votre commande avec le numero de confirmation " . $commandeEntity->getId() . " a ete creer");
        var_dump($mailer->send($message));

        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, "http://textbelt.com/canada");
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, "number=" . urlencode($user->getPhoneNumber()) . "&message=" . urlencode("Votre commande avec le numero de confirmation " . $commandeEntity->getId() . " a ete creer"));

        $result = curl_exec($curlHandle);
        var_dump($result);

        curl_close($curlHandle);

        return new Response($this->toJson($commandeResponse), Response::HTTP_CREATED, array(
            'Location' => $this->generateUrl('commande_api_get', array(
                'id' => $commandeEntity->getId()
            )),
            'Content-Type' => 'application/json'
        ));
    }

    /**
     * @param int $id
     * @return Response
     *
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="commande_api_get")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Method("GET")
     */
    public function getAction($id) {
        $commandeEntity = $this->findCommandeById($id);

        $commandeResponse = CommandeMapper::convertCommandeEntityToCommandeResponse($commandeEntity);

        return new Response($this->toJson($commandeResponse), Response::HTTP_OK, array(
            "Content-Type" => "application/json"
        ));
    }

    /**
     * @param string $json
     * @return CommandeRequest
     */
    private function convertCommandeRequest($json)
    {
        return $this->fromJson($json, 'Log210\APIBundle\Message\Request\CommandeRequest');
    }

    /**
     * @param int $id
     * @return Restaurant
     */
    private function getRestaurantById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Restaurant')->find($id);
    }

    /**
     * @param int $id
     * @return Plat
     */
    private function findPlatById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Plat')->find($id);
    }

    /**
     * @param int $id
     * @return Client
     */
    private function findClientById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Client')->find($id);
    }

    /**
     * @param string $id
     * @return Token
     */
    private function findTokenById($id)
    {
        return $this->getEntityManager()->getRepository('Log210APIBundle:Token')->find($id);
    }

    /**
     * @param int $id
     * @return Commande
     */
    private function findCommandeById($id)
    {
        return $this->getEntityManager()->getRepository('Log210LivraisonBundle:Commande')->find($id);
    }

}
