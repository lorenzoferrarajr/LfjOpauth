<?php

namespace LfjOpauth\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class CheckController extends AbstractActionController
{
    public function checkAction()
    {

        if (!$this->isCheckControllerEnabled()) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $return = array();

        $auth = $this->getServiceLocator()->get('lfjopauth_auth_service');

        $return['loggedIn'] = $auth->hasIdentity();
        $return['identity'] = $auth->getIdentity();

        return $return;
    }

    public function isCheckControllerEnabled()
    {
        $options = $this->getServiceLocator()->get('lfjopauth_module_options');
        if (!isset($options['check_controller_enabled'])) return false;

        return (bool) $options['check_controller_enabled'];
    }

}
