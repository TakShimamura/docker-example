<?php

namespace App\Support\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;
use App\Support\Services\AuthorizationManager;
use App\Support\Facades\User;

class AuthUserProvider implements UserProvider
{


    public function retrieveByToken($identifier, $token){
        if(AuthorizationManager::Validate($identifier)){
            return  new User($token->user);
        }
        return null;
    }

    public function retrieveById($identifier){
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token){
        return null;
    }

    public function retrieveByCredentials(array $credentials){
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials){
        return null;
    }

}
