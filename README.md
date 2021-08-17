# SimpleSAMLphp Bundle for Symfony 5

Use [SimpleSAMLphp](https://simplesamlphp.org/) for logging in to Symfony application.

## Installation

Require the bundle with:

```
composer require -W druidfi/simplesamlphp-bundle:dev-main
```

Copy following files to your Symfony app:

- `vendor/druidfi/simplesamlphp-bundle/Resources/config/packages/security.yaml` > `config/packages/security.yaml`

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
