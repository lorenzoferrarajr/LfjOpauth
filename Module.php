<?php

namespace LfjOpauth;

use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\EventManager\EventInterface as Event;
use Zend\Session\SessionManager;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'lfjopauth_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return isset($config['lfjopauth']) ? $config['lfjopauth'] : array();
                },
                'opauth_service' => function($sm) {
                    $opauth = new \LfjOpauth\Service\OpauthService();
                    $router = $sm->get('router');
                    $opauth->setRouter($router);
                    return $opauth;
                }
             )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(Event $e)
    {
         $session = new SessionManager();
         if (!$session->sessionExists()) $session->start();
    }

    public function getConfig($env = null)
    {
        return include __DIR__ . '/config/module.config.php';
    }
}