<?php

namespace Druidfi\SimpleSamlPhpBundle\Security;

use Druidfi\SimpleSamlPhpBundle\Service\LoginService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\SessionUnavailableException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class SamlAuthenticator extends AbstractAuthenticator
{
    const DEFAULT_ROUTE = 'index';

    private LoginService $loginService;
    private UrlGeneratorInterface $router;
    private Security $security;

    public function __construct(LoginService $loginService, UrlGeneratorInterface $router, Security $security)
    {
        $this->loginService = $loginService;
        $this->router = $router;
        $this->security = $security;
    }

    public function supports(Request $request): ?bool
    {
        if ($this->security->getUser()) {
            return false;
        }

        return 'login' === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $token = $this->security->getToken();

        if ($token) {
            $user = $this->security->getUser();
        }
        else {
            $to = $request->query->get('to', self::DEFAULT_ROUTE);

            // Redirect first back to login route
            $returnTo = $this->router->generate('login', ['to' => $to]);
            $errorUrl = $this->router->generate(self::DEFAULT_ROUTE, ['error' => 1]);
            $user_data = $this->loginService->requireAuth($returnTo, $errorUrl);

            $user = new User($user_data);
        }

        $userBadge = new UserBadge($user->getUid(), function () use ($user) {
            return $user;
        });

        return new SelfValidatingPassport($userBadge);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $to = $request->query->get('to', self::DEFAULT_ROUTE);
        $url = $this->router->generate($to);
        return new RedirectResponse($url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($exception instanceof SessionUnavailableException) {
            $error_params = ['error' => 'session-unavailable'];
        } elseif ($exception instanceof TokenNotFoundException) {
            $error_params = ['error' => 'token-not-found'];
        } else {
            $error_params = ['error' => 'authentication-required', 'exception' => $exception->getMessage()];
        }

        $url = $this->router->generate(self::DEFAULT_ROUTE, $error_params);

        return new RedirectResponse($url);
    }
}
