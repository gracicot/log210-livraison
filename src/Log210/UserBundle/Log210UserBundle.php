<?php

namespace Log210\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Log210UserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
