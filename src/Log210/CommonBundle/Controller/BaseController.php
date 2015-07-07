<?php

namespace Log210\CommonBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class BaseController extends SymfonyController
{

    /**
     * @return ObjectManager
     */
	protected function getEntityManager()
	{
		return $this->getDoctrine()->getManager();
	}

	protected function toJson($content)
	{
        $encoders = [new JsonEncoder()];
        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer([$normalizer], $encoders);
        

        return $serializer->serialize($content, 'json');
	}

    protected function fromJson($json, $returnClass) {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->deserialize($json, $returnClass, 'json');
    }

	protected function jsonResponse(Response $response)
	{
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
}