<?php

namespace Log210\CommonBundle\EntityRepository;

use Doctrine\ORM\EntityRepository as SymfonyEntityRepository;

abstract class EntityRepository extends SymfonyEntityRepository
{
	public abstract function makeEntity();
}
