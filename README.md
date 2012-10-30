LfjOpauth
=========

Created By Lorenzo Ferrara Junior

Introduction
------------

**LfjOpauth** is a Zend Framework 2 module that enables support for many authentication providers through the [Opauth](http://opauth.org/) framework.

Installation
-----

To use the module you need to install [Opauth](http://opauth.org/) and one of it's strategies:

- [opauth-facebook](https://github.com/uzyn/opauth-facebook)
- [opauth-twitter](https://github.com/uzyn/opauth-twitter)
- [more of them...](https://github.com/uzyn)

If you install LfjOpauth using `composer`, the Opauth dependecy is automatically resolved, but you still must provide at least one strategy.

Configuration
-----

Once LfjOpauth is installed you must create a file named `lfjopauth.global.php` in your `config/autoload` directory. This is the configuration file where you specify the LfjOpauth options.

An example of the `lfjopauth.global.php` file:

```php
$settings = array(
    'security_salt' => 'Some random text',
    'Strategy' => array(
        'Facebook' => array(
            'app_id' => 'facebook application id',
            'app_secret' => 'facebook application secret',
            'scope' => 'email,user_relationships',
        ),
        'second_facebook_app' => array(
            'app_id' => 'another facebook application id',
            'app_secret' => 'another facebook application secret',
            'scope' => 'email,user_relationships',
            'strategy_class' => 'Facebook',
            'strategy_url_name' => 'second_facebook_app'
        )
    )
);

return array('lfjopauth' => $settings);
```

The configuration is pretty much the same as the [Opauth configuration](https://github.com/uzyn/opauth/blob/master/example/opauth.conf.php.default), without the `path` and `callback_url` options, which are handled by the module.

Login and callback urls
-----

Given the above configuration (and the corresponding Facebook applications), you will be able to login using:

- http://example.com/user/opauth/login/facebook
- http://example.com/user/opauth/login/second_facebook_app

and to logout using:

- http://example.com/user/opauth/logout

For the two demo Facebook application described in the example configuration, you should use

- http://example.com/usa2012/login/facebook
- http://example.com/usa2012/login/second_facebook_app

as value of the **Website with Facebook Login, Site URL** option.


Other info
-----

LfjOpauth uses `Zend\Authentication\AuthenticationService` (alias `lfjopauth_auth_service`) to manage authentication.

The `LfjOpauth\Service\OpauthService` (alias: `opauth_service`) class exposes the `redirect` and `callback` methods which can be used in any controller. An example can be found in the [LfjOpauth\Controller\LoginController](https://github.com/lorenzoferrarajr/LfjOpauth/blob/master/src/LfjOpauth/Controller/LoginController.php) class.
