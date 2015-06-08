<?php

namespace Log210\CommonBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use ReflectionClass;

class ReflectionExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('class', [$this, 'classShortName'])
        ];
    }

    public function getName()
    {
        return 'reflection_twig_extension';
    }

    public function classShortName($object)
    {
        if (is_object($object)) {
            return (new ReflectionClass($object))->getShortName();
        }

        return null;
    }
}