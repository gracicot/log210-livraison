<?php

namespace Log210\CommonBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use ReflectionClass;

class GravatarExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('gravatar', array($this, 'gravatarHash'))
        );
    }

    public function getName()
    {
        return 'gravatar_twig_extension';
    }

    public function gravatarHash($email)
    {
        return md5(strtolower(trim($email)));
    }
}