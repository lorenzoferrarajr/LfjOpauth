<?php

namespace LfjOpauth\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class LoginController extends AbstractActionController
{
    public function redirectAndReturnAction()
    {
        $provider = $this->params()->fromRoute('provider');
        $oauth_callback = $this->params()->fromRoute('oauth_callback');
        $this->getServiceLocator()->get('opauth_service')->redirect($provider, $oauth_callback);
    }

    public function callbackAction()
    {
        $provider = $this->params()->fromRoute('provider');
        $this->getServiceLocator()->get('opauth_service')->callback($provider);

        $auth = $this->getServiceLocator()->get('lfjopauth_auth_service');

        return array('result' => $auth->hasIdentity(), 'provider' => $provider);
    }
}
