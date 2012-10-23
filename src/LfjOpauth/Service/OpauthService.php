<?php

namespace LfjOpauth\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Opauth;

class OpauthService implements ServiceLocatorAwareInterface
{
    private $serviceLocator;
    private $options;
    private $router;
    private $loginUrlName;
    private $callbackUrlName;

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

        $this->options['path'] = $this->getRouter()->assemble(array(), array('name' => $this->getLoginUrlName()));
        $this->options['callback_url'] = $this->getRouter()->assemble(array('provider' => $provider), array('name' => $this->getCallbackUrlName()));

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

    public function setCallbackUrlName($callbackUrlName)
    {
        $this->callbackUrlName = $callbackUrlName;
    }

    public function getCallbackUrlName()
    {
        if ($this->callbackUrlName == null) return 'lfjopauth_callback';

        return $this->callbackUrlName;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}