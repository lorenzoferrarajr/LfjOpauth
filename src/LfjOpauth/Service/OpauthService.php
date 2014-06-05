<?php

namespace LfjOpauth\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Opauth;

class OpauthService implements ServiceLocatorAwareInterface, EventManagerAwareInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    private $serviceLocator;
    private $options;
    private $router;

    private $loginUrlName;
    private $loginUrlNameParams;

    private $callbackUrlName;
    private $callbackUrlNameParams;

    public function redirect($provider, $oauth_callback)
    {
        $opauth = new Opauth($this->getOptions($provider));
    }

    public function callback($provider)
    {
        $opauth = new Opauth($this->getOptions($provider), false);

        $auth = $this->getServiceLocator()->get('lfjopauth_auth_service');

        $authAdapter = $this->getServiceLocator()->get('lfjopauth_auth_adapter');
        $authAdapter->setOpauth($opauth);
        $authAdapter->setOpauthProvider($provider);
        $authAdapter->setAuthenticationService($auth);

        $result = $auth->authenticate($authAdapter);

        if (!$result->isValid()) {
            return array(
                'provider' => $provider,
                'result' => false,
                'code' => $result->getCode(),
                'messages' => $result->getMessages(),
                'debug' => $result
            );
        }

        return array(
            'provider' => $provider,
            'result' => true,
            'code' => $result->getCode(),
            'messages' => $result->getMessages(),
            'debug' => $result
        );
    }

    public function setRouter($router)
    {
        $this->router = $router;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions($provider)
    {
        if ($this->options === null || !is_array($this->options)) {
            $this->setOptions($this->getServiceLocator()->get('lfjopauth_module_options'));
        }

        $callbackUrlParams = array_replace(array('provider' => $provider), $this->getCallbackUrlNameParams());

        $this->options['path'] = $this->getRouter()->assemble($this->getLoginUrlNameParams(), array('name' => $this->getLoginUrlName()));
        $this->options['callback_url'] = $this->getRouter()->assemble($callbackUrlParams, array('name' => $this->getCallbackUrlName()));

        return $this->options;
    }

    public function setLoginUrlName($loginUrlName)
    {
        $this->loginUrlName = $loginUrlName;
    }

    public function getLoginUrlName()
    {
        if ($this->loginUrlName == null) return 'lfjopauth_login';
        return $this->loginUrlName;
    }

    public function setLoginUrlNameParams($loginUrlNameParams)
    {
        $this->loginUrlNameParams = $loginUrlNameParams;
    }

    public function getLoginUrlNameParams()
    {
        if ($this->loginUrlNameParams == null) return array();
        return $this->loginUrlNameParams;
    }

    public function setCallbackUrlName($callbackUrlName)
    {
        $this->callbackUrlName = $callbackUrlName;
    }

    public function getCallbackUrlName()
    {
        if ($this->callbackUrlName == null) return 'lfjopauth_callback';
        return $this->callbackUrlName;
    }

    public function setCallbackUrlNameParams($callbackUrlNameParams)
    {
        $this->callbackUrlNameParams = $callbackUrlNameParams;
    }

    public function getCallbackUrlNameParams()
    {
        if ($this->callbackUrlNameParams == null) return array();
        return $this->callbackUrlNameParams;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(array(
            get_called_class()
        ));

        $this->eventManager = $eventManager;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
}
