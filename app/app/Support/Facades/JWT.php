<?php

namespace App\Support\Facades;

use Firebase\JWT\JWT as JWTLib;

class JWT   {

    protected $handler;

    protected $type;

    const MODES = [
        'rs' => 'RS256',
    ];

    
    public function __construct($type=null,$encoding=null){
        $this->mode($encoding);
        $this->key($type);
    }

    public function key($type=null){
        $key = config("rsa.keys.$type");
        if(empty($type) || empty($key)){
            $type = config('rsa.default.jwt-key');
        }
        $this->type = $type;
        return $this;
    }

    public function mode($encoding=null){
        $default = config('rsa.default.jwt-mode');
        if(empty($encoding) || !array_key_exists($encoding,self::MODES)){
            $encoding = $default;
        }
        $this->encoding = self::MODES[$encoding];
        return $this;
    }  

    protected function getKey($access){
        return config("rsa.keys.$this->type.$access");   
    }

    public function encrypt(array $payload,$size=null){
        return rsa($size)->encrypt($this->encode($payload));
    }

    public function decrypt($token,$size=null){
        $encoded = rsa($size)->decrypt($token);
        if($encoded){
            return $this->decode($encoded);
        }
        return false;
    }

    public function encode(array $payload){
        return JWTLib::encode(
            $payload,
            $this->getKey('secret'),
            $this->encoding,
        );
    }

    public function decode($token){
        return JWTLib::decode(
            $token,
            $this->getKey('public'),
            array($this->encoding)
        );
    }


}


