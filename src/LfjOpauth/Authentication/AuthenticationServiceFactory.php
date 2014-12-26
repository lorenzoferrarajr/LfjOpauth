<?php

namespace LfjOpauth\Authentication;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationServiceFactory
{
    public function __invoke(ServiceLocatorInterface $services)
    {
        if ($services->has('Zend\Authentication\AuthenticationService')) {
            return $services->get('Zend\Authentication\AuthenticationService');
        }

        return new AuthenticationService();
    }
}