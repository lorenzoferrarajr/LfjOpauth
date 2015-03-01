<?php

namespace LfjOpauth\Authentication;

use LfjOpauth\Authentication\AuthenticationAdapter;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationAdapterFactory
{
    public function __invoke(ServiceLocatorInterface $services)
    {
        return new AuthenticationAdapter();
    }
}