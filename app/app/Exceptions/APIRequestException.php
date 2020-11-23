<?php

namespace App\Exceptions;

use Exception;
use App\Contracts\RenderableException;

class APIRequestException extends Exception implements RenderableException
{
    //

        protected $errors;
        protected $httpCode;
    
        public function __construct(array $errors, $httpCode=400){
            $this->httpCode = $httpCode;
            $this->errors = $errors;
            parent::__construct();
        }
    
        public function render($request){
            return response()->json(payload($this->errors,config('subject')),$this->httpCode);
        }
}
