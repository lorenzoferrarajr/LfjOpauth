<?php

namespace LfjOpauth\Authentication;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;

class AuthenticationAdapter implements AdapterInterface
{
    public $opauthReponse;
    public $opauthProvider;
    public $opauth;
    public $authService;

    public function authenticate()
    {
        $provider = $this->getOpauthProvider();
        $opauth   = $this->getOpauth();

        $response = null;

        switch ($opauth->env['callback_transport']) {
            case 'session':
                if (isset($_SESSION['opauth'])) {
                    $response = $_SESSION['opauth'];
                    unset($_SESSION['opauth']);
                }
                break;
            case 'post':
                if (isset($_POST['opauth']))
                    $response = unserialize(base64_decode($_POST['opauth']));
                break;
            case 'get':
                if (isset($_GET['opauth']))
                    $response = unserialize(base64_decode($_GET['opauth']));
                break;
        }

        if (!is_array($response) || $response == null) {
                $result = new AuthenticationResult(
                    AuthenticationResult::FAILURE_UNCATEGORIZED,
                    array(
                        'provider' => $provider,
                        'response'   => $response,
                        'opauth_tutorial_message' => 'Authentication error: Opauth response is not an array or is null'
                    )
                );
        } elseif (array_key_exists('error', $response)) {
                $result = new AuthenticationResult(
                    AuthenticationResult::FAILURE_UNCATEGORIZED,
                    array(
                        'provider' => $provider,
                        'response'   => $response,
                        'opauth_tutorial_message' => 'Authentication error: Opauth returns error auth response'
                    )
                );
        } else {
            if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])) {
                $result = new AuthenticationResult(
                    AuthenticationResult::FAILURE_UNCATEGORIZED,
                    array(
                        'provider' => $provider,
                        'response'   => $response,
                        'opauth_tutorial_message' => 'Invalid auth response: Missing key auth response components'
                    )
                );
            } elseif (!$opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)) {
                $result = new AuthenticationResult(
                    AuthenticationResult::FAILURE_UNCATEGORIZED,
                    array(
                        'provider' => $provider,
                        'response'   => $response,
                        'opauth_tutorial_message' => 'Invalid auth response: '.$reason
                    )
                );
            } else {

                if ($this->getAuthenticationService()->hasIdentity())
                    $identity = $this->getAuthenticationService()->getIdentity();
                else
                    $identity = array();

                if (!isset($identity['lfjopauth']) || !is_array($identity['lfjopauth'])) $identity['lfjopauth'] = array();
                if (!isset($identity['lfjopauth']['opauth']) || !is_array($identity['lfjopauth']['opauth'])) $identity['lfjopauth']['opauth'] = array();
                if (!isset($identity['lfjopauth']['current_providers']) || !is_array($identity['lfjopauth']['opauth'])) $identity['lfjopauth']['current_providers'] = array();

                $identity['lfjopauth']['opauth'][$provider] = $response;

                if (!isset($identity['lfjopauth']['current_providers']) || !is_array($identity['lfjopauth']['current_providers']))
                    $identity['lfjopauth']['current_providers'] = array();

                if (!in_array($provider, $identity['lfjopauth']['current_providers']))
                    $identity['lfjopauth']['current_providers'][] = $provider;

                $result = new AuthenticationResult(
                    AuthenticationResult::SUCCESS,
                    $identity
                );
            }
        }

        return $result;
    }

    public function setAuthenticationService($auth)
    {
        $this->authService = $auth;
    }

    public function getAuthenticationService()
    {
        return $this->authService;
    }

    public function setOpauthProvider($provider)
    {
        $this->opauthProvider = $provider;
    }

    public function getOpauthProvider()
    {
        return $this->opauthProvider;
    }

    public function setOpauth($opauth)
    {
        $this->opauth = $opauth;
    }

    public function getOpauth()
    {
        return $this->opauth;
    }
}
