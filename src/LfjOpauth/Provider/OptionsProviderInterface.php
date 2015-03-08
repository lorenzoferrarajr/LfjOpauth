<?php

namespace LfjOpauth\Provider;


interface OptionsProviderInterface {

    /**
     * @return Array return the lfjopauth_module_options
     */
    public function getOptions();

}