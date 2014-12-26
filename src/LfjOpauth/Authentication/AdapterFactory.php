<?php

namespace LfjOpauth\Authentication;

use LfjOpauth\Authentication\Adapter as AuthenticationAdapter;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactory
{
    public function __invoke(ServiceLocatorInterface $services)
    {
        return new AuthenticationAdapter();
    }
}