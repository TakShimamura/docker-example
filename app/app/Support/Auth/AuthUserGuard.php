<?php

namespace App\Support\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuardHelpers;
use App\Support\Auth\AuthUserProvider as UserProvider;
use App\Http\Requests\Base\APIRequest;
use Illuminate\Support\Facades\Log;

class AuthUserGuard implements Guard
{
    use GuardHelpers;

    protected $provider;
    protected $request;
    protected $token;

    public function __construct(UserProvider $provider, $request){
        $this->provider = $provider;
        $this->request = $request;
        
    }

    public function getRequestCredentials(){
        if(empty($this->token)){ 
            return [];
        }
        return (array)$this->token;
    }


    public function getTokenForRequest() {
        $encrypted = $this->request->header(config('services.auth.header'));
        if(empty($encrypted)){ return null; }
        $token = jwt()->decrypt($encrypted);
        if(!$token){ return null; }
        return $token;
    }
    
    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
     public function user() {
        // if user is already set return it.
        if($this->hasUser()){
            return $this->user;
        }

        $user = null;

        $this->token = $this->getTokenForRequest();
    
        if($this->validate($this->getRequestCredentials())){
            $user = $this->provider->retrieveByToken($this->token->id,$this->token);
        }

        return $this->user = $user;
     }

     
    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = []){
        if($this->isSample($credentials)){ return true; }
        $ip = $this->request->ip();
        $userAgent = $this->request->userAgent();
        $fingerprint = $this->request->header(config('services.auth.fingerprint'));
        return (
            (!empty($ip) && !empty($userAgent))
            && $ip == FindOrDefault('ip',$credentials)
            && $userAgent == FindOrDefault('user_agent',$credentials)
            && $fingerprint == FindOrDefault('fingerprint',$credentials)
        );
    }



    
    public function isSample(array $credentials = []){
        if(!isProduction() && FindOrDefault('fingerprint',$credentials) == 'sample'){ 
            return true; 
        }
    }

}
