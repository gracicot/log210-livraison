<?php

namespace Log210\CommonBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class EntityToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    private $type;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, $type)
    {
        $this->om = $om;
        $this->type = (string)$type;
    }

    /**
     * Transforms an object to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($entity)
    {
        if ($entity === null) {
            return null;
        }

        return $entity->getId();
    }

    /**
     * Transforms a string (number) to an object.
     *
     * @param  string $id
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $entity = $this->om->getRepository($this->type)->find($id);

        if ($entity === null) {
            throw new TransformationFailedException('An entity with the id ' . $id . ' does not exist!');
        }

        return $entity;
    }
}