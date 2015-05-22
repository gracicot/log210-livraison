<?php

namespace Log210\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends SymfonyController
{
	protected function getEntityManager()
	{
		return $this->getDoctrine()->getManager();
	}

	protected function toJson($content)
	{
        $encoders = [new JsonEncoder()];
        $normalizers = [new GetSetMethodNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($content, 'json');
	}

	protected function jsonResponse(Response $response)
	{
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
}