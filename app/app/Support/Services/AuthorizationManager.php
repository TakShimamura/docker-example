<?php

namespace App\Support\Services;

use App\Support\Facades\HTTPClient;

class AuthorizationManager {

    protected static function url($endpoint=null){
        $base = config('services.auth.url');
        if(empty($endpoint)){ return $base; }
        return $base.$endpoint;
    }

    public static function Validate($id){
        if(!isProduction() && $id == 'sample'){ return true; }
        $params = ['id' => rsa()->encrypt($id)];
        $response = HTTPClient::post(self::url('/v1/auth/check'),['json' => $params]);
        // return $response;
        if($response->successfull()){
            return FindOrDefault('valid',$response->json(),false);
        }
        return false;
    }

    public static function Users(){}
    public static function User(){}




}