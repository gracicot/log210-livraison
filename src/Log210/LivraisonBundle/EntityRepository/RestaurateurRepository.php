<?php

namespace Log210\LivraisonBundle\EntityRepository;

use Log210\CommonBundle\EntityRepository\EntityRepository;
use Log210\LivraisonBundle\Entity\Restaurateur;

class RestaurateurRepository extends EntityRepository
{
	public function makeEntity()
	{
		return new Restaurateur();
	}
}
