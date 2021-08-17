# SimpleSAMLphp Bundle for Symfony 5

Use [SimpleSAMLphp](https://simplesamlphp.org/) for logging in to Symfony application.

## Installation

Require the bundle with:

```
composer require -W druidfi/simplesamlphp-bundle:dev-main
```

Configure Security component in `config/packages/security.yaml`:

```
security:
    # https://symfony.com/doc/current/security/experimental_authenticators.html
    enable_authenticator_manager: true
    # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
    providers:
        druidfi_simplesamlphp_user_provider:
            id: Druidfi\SimpleSamlPhpBundle\Security\UserProvider
    firewalls:
        main:
            provider: druidfi_simplesamlphp_user_provider

            # activate different ways to authenticate
            # https://symfony.com/doc/current/security.html#firewalls-authentication
            custom_authenticators:
                - Druidfi\SimpleSamlPhpBundle\Security\SamlAuthenticator
            logout:
                path: druidfi_simplesamlphp_logout
                target: /

    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        - { path: '^/login', roles: ROLE_USER }
        # This must be last one
        - { path: '^/', roles: IS_AUTHENTICATED_ANONYMOUSLY }
```


Add routes to `config/routes.yaml`:

```
###> druidfi/simplesamlphp-bundle ###
druidfi_simplesamlphp_login:
  path: /login
  controller: Druidfi\SimpleSamlPhpBundle\Controller\LoginController::login
druidfi_simplesamlphp_logout:
  path: /logout
  controller: Druidfi\SimpleSamlPhpBundle\Controller\LoginController::logout
###< druidfi/simplesamlphp-bundle ###
```

## License

This bundle is under the MIT license. For more information, see the complete [LICENSE](LICENSE) file in the bundle.

## Other information

This project can be found from the Packagist: https://packagist.org/packages/druidfi/simplesamlphp-bundle
