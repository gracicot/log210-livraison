<?php

namespace Log210\LivraisonBundle\EntityRepository;

use Log210\CommonBundle\EntityRepository\EntityRepository;
use Log210\LivraisonBundle\Entity\Restaurant;

class RestaurantRepository extends EntityRepository
{
	public function makeEntity()
	{
		return new Restaurant();
	}
}
