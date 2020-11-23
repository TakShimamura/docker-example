<?php

namespace App\Support\Facades;

use App\Contracts\RSA as Contract;
use \phpseclib\Crypt\RSA as RSALib;


class RSA implements Contract {

    /**
     * Valid modes
     */
    const MODES = [
        'pkcs1' => RSALib::ENCRYPTION_PKCS1,
        'oaep' => RSALib::ENCRYPTION_OAEP,
    ];
    /**
     * accepted content types 
     * mapped to the appropiate functions
     */
    const CONTENT_TYPES = [
        'array' => [
            'encrypt' => 'encryptArray',
            'decrypt' => 'decryptArray', 
        ],
        'string' => [
            'encrypt' => 'encryptValue',
            'decrypt' => 'decryptValue', 
        ],
        'boolean' => [
            'encrypt' => 'encryptValue',
            'decrypt' => 'decryptValue', 
        ],
        'integer' => [
            'encrypt' => 'encryptValue',
            'decrypt' => 'decryptValue', 
        ],
        'double' => [
            'encrypt' => 'encryptValue',
            'decrypt' => 'decryptValue', 
        ],
        'object' => [
            'encrypt' => 'encryptObject',
            'decrypt' => 'decryptObject',
        ],
    ];

    /**
     * Instance of the external library class
     */
    protected $handler;

    /**
     * Used to determine the type of key ( byte size )
     */
    protected $type;

    /**
     * Sets the key based off of the currently defined type and the passed in access parameter
     * This value is pulled from the configuration file.
     */
    protected function setKey(string $access){
        $key = config("rsa.keys.$this->type.$access");
        return $this->handler->loadKey($key);
    }

    /**
     * This function routes the incoming content type to the proper
     * encryption / decryption function.
     * encryption / decyption is determined based on the parameter "direction"
     */
    protected function handleContent($content,string $direction){
        $functions = FindOrDefault(gettype($content),self::CONTENT_TYPES);
        if(empty($functions)){ return false; }
        $function = $functions[$direction];
        try{
            return $this->$function($content);
        } catch(\Exception $ex){
            return false;
        }
    }
    /**
     * Recursively encrypt an array.
     */
    protected function encryptArray(array $array){
        $encrypted = [];
        foreach($array as $key => $value){
            if(is_string($key)){
                $key = $this->encryptValue($key);
            }
            $encrypted[$key] = $this->handleContent($value,'encrypt');
        }
        return $encrypted;
    }
    /**
     * encrypt string / int / float / double / boolean
     */
    protected function encryptValue($value){            
        $cipher = $this->handler->encrypt($value);
        return base64_encode($cipher);
    }
    /**
     * Recursively encrypt an object.
     */
    protected function encryptObject($object){
        $array = (array)$object;
        return $this->encryptArray($array);
    }
    /**
     * Recursively decrypt an object.
     */
    protected function decryptObject($object){
        $array = (array)$object;
        return $this->decryptArray($array);
    }
    /**
     * Recursively encrypt an array.
     */
    protected function decryptArray(array $array){
        $decrypted = [];
        foreach($array as $key => $value){
            if(is_string($key)){
                $key = $this->decryptValue($key);
            }
            $decrypted[$key] = $this->handleContent($value,'decrypt');
        }
        return $decrypted;
    }
    /**
     * encrypt string / int / float / double / boolean
     */
    protected function decryptValue($value){
        $cipher = base64_decode($value);
        return $this->handler->decrypt($cipher);
    }


    /**
     * Construct this object.
     * Set default state
     */
    public function __construct($type=null,$encoding=null){
        $this->handler = new RSALib();
        $this->mode($encoding);
        $this->key($type);
    }
    /**
     * Set the key type to be used.
     * return self for function chaining
     */
    public function key($type=null){
        $key = config("rsa.keys.$type");
        if(empty($type) || empty($key)){
            $type = config('rsa.default.key');
        }
        $this->type = $type;
        return $this;
    }
    /**
     * Set the encryption mode to be used.
     * return self for function chaining
     */
    public function mode($encoding=null){
        $default = config('rsa.default.mode');
        if(empty($encoding) || !array_key_exists($encoding,self::MODES)){
            $encoding = $default;
        }
        $this->handler->setEncryptionMode(self::MODES[$encoding]);
        return $this;
    }  
    /**
     * encrypt content
     * return false if failed.
     */
    public function encrypt($content){
        if($this->setKey('public')){
            return $this->handleContent($content,'encrypt');
        }
        return false;
    }
    /**
     * decrypt content
     * return false if failed.
     */
    public function decrypt($content){
        if($this->setKey('secret')){
            return $this->handleContent($content,'decrypt');
        }
        return false;
    }

}