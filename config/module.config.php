<?php

return array(
    'service_manager' => array(
        'invokables' => array(
            'lfjopauth_auth_service' => 'Zend\Authentication\AuthenticationService',
            'lfjopauth_auth_adapter' => 'LfjOpauth\Authentication\Adapter'
        ),
        'factories' => array(
            'opauth_service' => 'LfjOpauth\Service\OpauthServiceFactory'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'LfjOpauth\Controller\Login'  => 'LfjOpauth\Controller\LoginController',
            'LfjOpauth\Controller\Logout' => 'LfjOpauth\Controller\LogoutController',
            'LfjOpauth\Controller\Check'  => 'LfjOpauth\Controller\CheckController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'lfjopauth_logout' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/user/opauth/logout[/:provider]',
                    'constraints' => array(
                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller'    => 'LfjOpauth\Controller\Logout',
                        'action'        => 'logout'
                    )
                )
            ),
            'lfjopauth_login' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/user/opauth/login/[:provider[/:oauth_callback]]',
                    'constraints' => array(
                        'provider'       => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'oauth_callback' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller'    => 'LfjOpauth\Controller\Login',
                        'action'        => 'redirectAndReturn'
                    )
                )
            ),
            'lfjopauth_callback' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/user/opauth/callback/[:provider]',
                    'constraints' => array(
                        'provider'  => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller'    => 'LfjOpauth\Controller\Login',
                        'action'        => 'callback'
                    )
                )
            ),
            'lfjopauth_check' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/user/opauth/check',
                    'defaults' => array(
                        'controller'    => 'LfjOpauth\Controller\Check',
                        'action'        => 'check'
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
