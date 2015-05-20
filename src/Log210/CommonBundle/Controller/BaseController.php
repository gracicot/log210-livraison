<?php

namespace Log210\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;

abstract class BaseController extends SymfonyController
{
	protected function getEntityManager()
	{
		return $this->getDoctrine()->getManager();
	}
}