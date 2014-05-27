<?php

namespace LfjOpauth;

use Zend\EventManager\Event;

class LfjOpauthEvent extends Event
{
    const EVENT_LOGIN_CALLBACK = 'lfjopauth.login.callback';
}