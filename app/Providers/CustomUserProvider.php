<?php

namespace App\Providers;

use App\Services\AuthService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;

class CustomUserProvider implements UserProvider
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function retrieveById($identifier): ?Authenticatable
    {
        $response = $this->authService->getUserByToken($identifier);

        Log::info('retrieveById: ' . json_encode($response));

        if ($response['success']) {
            return $this->getAuthUser($response['data']);
        }

        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        // Cette méthode n'est pas nécessaire pour cette implémentation
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Cette méthode n'est pas nécessaire pour cette implémentation
    }

    public function retrieveByCredentials(array $credentials)
    {
        // Cette méthode n'est pas nécessaire pour cette implémentation
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Cette méthode n'est pas nécessaire pour cette implémentation
        return false;
    }

    protected function getAuthUser(array $userData): Authenticatable
    {
        return new class($userData) implements Authenticatable {
            private $userData;

            public function __construct(array $userData)
            {
                $this->userData = $userData;
            }

            public function getAuthIdentifierName()
            {
                return 'id';
            }

            public function getAuthIdentifier()
            {
                return $this->userData['id'] ?? null;
            }

            public function getAuthPassword()
            {
                // Pas nécessaire pour cette implémentation
                return null;
            }

            public function getRememberToken()
            {
                // Pas nécessaire pour cette implémentation
                return null;
            }

            public function setRememberToken($value)
            {
                // Pas nécessaire pour cette implémentation
            }

            public function getRememberTokenName()
            {
                // Pas nécessaire pour cette implémentation
                return null;
            }

            // Ajout de la méthode magique __get pour accéder aux propriétés de l'utilisateur
            public function __get($name)
            {
                return $this->userData[$name] ?? null;
            }
            public function getUserData(): array
            {
                return $this->userData;
            }
        };
    }
}

