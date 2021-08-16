<?php

namespace Druidfi\SimpleSamlPhpBundle\Service;

use Exception;
use SimpleSAML\Auth\Simple;
use SimpleSAML\Session;

class LoginService
{
    private ?Simple $as = null;
    private string $sp = 'default-sp';
    private array $valid_attributes = [
        'firstname',
        'lastname',
        'email',
        'uid',
    ];

    /**
     * Require authentication.
     */
    public function requireAuth(string $returnTo, string $errorUrl): array
    {
        $this->getAS()->requireAuth([
            'ReturnTo' => $returnTo,
            'ErrorURL' => $errorUrl,
        ]);

        return $this->getUserData($this->getAS()->getAttributes());
    }

    /**
     * Logout.
     *
     * @throws Exception
     */
    public function logout(string $returnTo): void
    {
        $this->getAS()->logout($returnTo);

        Session::getSessionFromRequest()->cleanup();
    }

    /**
     * Get login URL.
     */
    public function getLoginURL(string $returnTo): string
    {
        return $this->getAS()->getLoginURL($returnTo);
    }

    /**
     * Get logout URL.
     */
    public function getLogoutURL(string $returnTo): string
    {
        return $this->getAS()->getLogoutURL($returnTo);
    }

    /**
     * Get user data.
     */
    public function getUser(): array
    {
        $attributes = $this->getAS()->getAttributes();

        if ($attributes) {
            $data = [
                'authenticated' => true,
                'data' => $this->getUserData($attributes),
            ];
        } else {
            $data = [
                'authenticated' => false,
                'data' => [],
            ];
        }

        return $data;
    }

    /**
     * Get AuthSource.
     */
    private function getAS(): Simple
    {
        if (!$this->as) {
            $this->as = new Simple($this->sp);
        }

        return $this->as;
    }

    /**
     * Get user data from received attributes.
     */
    private function getUserData(array $attributes): array
    {
        $data = [];

        foreach ($attributes as $attr => $values) {
            if (in_array($attr, $this->valid_attributes)) {
                $data[$attr] = $values[0];
            }
        }

        return $data;
    }
}
