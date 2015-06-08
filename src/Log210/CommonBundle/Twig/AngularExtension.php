<?php

namespace Log210\CommonBundle\Twig;

use Twig_Extension;
use Twig_SimpleFilter;
use ReflectionClass;
use Twig_SimpleFunction;

class AngularExtension extends Twig_Extension
{
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('ngExpr', [$this, 'expr'])
        ];
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('ngPath', [$this, 'path'])
        ];
    }

    public function getName()
    {
        return 'angular_twig_extension';
    }

    public function expr($expr)
    {
        return '{{ ' . $expr . ' }}';
    }

    public function path($path, $params)
    {
        $sParam = '';
        if (!empty($params)) {
            $sParam = '{';
            foreach ($params as $name => $value) {
                $sParam .= '\'' . $name . '\': ' . $value . ',';
            }
            $sParam = substr($sParam, 0, -1) . '}';
        }
        return '{{ Routing.generate(\'' . $path . '\', ' . $sParam . ') }}';
    }
}
