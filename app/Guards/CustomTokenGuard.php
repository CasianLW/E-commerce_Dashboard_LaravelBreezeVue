<?php

namespace App\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomTokenGuard implements Guard
{
    protected Request $request;
    protected UserProvider $provider;
    protected $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
    }

    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->session()->get('token');

        if (! empty($token)) {
            $this->user = $this->provider->retrieveById($token);
        }

//        var_dump( $this->user);
//        die();
        return $this->user;
    }

    public function validate(array $credentials = []): bool
    {
        if (empty($credentials['token'])) {
            return false;
        }

        if ($user = $this->provider->retrieveById($credentials['token'])) {
            $this->user = $user;
            return true;
        }

        return false;
    }

    public function check(): bool
    {
        return ! is_null($this->user());
    }

    public function guest(): bool
    {
        return ! $this->check();
    }

    public function id()
    {
        if ($user = $this->user()) {
            return $this->user->getAuthIdentifier();
        } else {
            return null;
        }
    }

    public function setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        $this->user = $user;
        $this->request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $this;
    }

    public function hasUser()
    {
        // TODO: Implement hasUser() method.
    }
}