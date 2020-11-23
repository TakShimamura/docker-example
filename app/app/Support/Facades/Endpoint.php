<?php

namespace App\Support\Facades;


class Endpoint {


    public $request;
    public $path;
    public $method;
    public $action;
    public $rule;
    public $rules;
    public $type;

    public function __construct($request, $type="api"){
        $this->request = $request;
        $this->type = $type;
        $this->setPath();
        $this->setMethod();
        $this->setAction();
        $this->setRule();
    }

    public function setPath($path = null){
        if(!empty($path)){
            $this->path = $path;
            return $this;
        }

        $this->path = $this->request->path();
        return $this;
    }

    public function setMethod($method = null){
        if(!empty($method)){
            $this->method = $method;
            return $this;
        }

        $this->method = $this->request->method();
        return $this;
    }

    public function setAction($action = null){
        if(!empty($action)){
            $this->action = $action;
            return $this;
        }

        $this->action = \Route::currentRouteAction();
        return $this;
    }

    public function setRule($rule = null){
        if(!empty($rule)){
            $this->rule = $rule;
            return $this;
        }   

        // $pathPattern = "/$this->type\/[a-zA-Z0-9_]*/";
        $pattern = "/[a-zA-Z0-9_\\\\]*@/";

        $this->rule = config('subject').'-'.preg_replace($pattern,'',$this->action);
        return $this;
    }

    public function rules($rule = null){
        if(empty($rule)){ 
            $rule = $this->rule;
        }
        $rules = config("requests.rules.$rule"); 
        if(gettype($rules) == 'string'){
            return $this->rules($rules);
        }
        return $rules;
    }
}