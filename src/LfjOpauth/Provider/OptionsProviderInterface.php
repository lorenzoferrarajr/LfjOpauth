<?php

namespace LfjOpauth\Provider;


interface OptionsProviderInterface {

    /**
     * @param string Provider name
     * @return Array return the lfjopauth_module_options
     */
    public function getOptions($provider);

}