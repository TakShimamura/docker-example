<?php


if(!function_exists('found')){
    function found($object){
        if($object instanceof \Countable && count($object) <= 0){ return false; }
        if(empty($object)){ return false; }
        return true;
    }
}

if(!function_exists('Attempt')){
    function Attempt($function,$object=null,$payload=[]){
        return resolve(contract('Attempt'),['function' => $function, 'object' => $object,'payload' => $payload]);
    }
}

if(!function_exists('rsa')){
    function rsa(){
        return resolve(contract('RSA'));
    }
}

if(!function_exists('jwt')){
    function jwt(){
        return resolve(contract('JWT'));
    }
}

// TODO
if(!function_exists('contract')){
    function contract(string $name, string $namespace='App\\Contracts\\') {
        return $namespace.$name;
    }
}

if(!function_exists('controller')){
    function controller(string $name) {
        return spaceybaseychickenfacey().'Http\\Controllers\\'.$name.'Controller';
    }
}

/**
 * Fabians function
 */
if(!function_exists('spaceybaseychickenfacey')){
    function spaceybaseychickenfacey(){
        $version = version();
        return config("app.versions.$version.namespace");
    }
}

if(!function_exists('mappify')){
    function mappify($keys,$values){
        $map = [];
        if(count($keys) != count($values)){ return false; }
        foreach($keys as $index => $key){
            $map[$key] = $values[$index];
        }
        return $map;
    }
}

if(!function_exists('isProduction')){
    function isProduction(){
        return MyEnv() == 'production';
    }
}

if(!function_exists('MyEnv')){
    function MyEnv(){
        return config('app.env');
    }
}

if(!function_exists('FindOrDefault')){
    function FindOrDefault($key,$source,$default=null){
        if(is_array($source)){
            if(array_key_exists($key,$source)){
                return $source[$key];
            }
        }
        return $default;
    }
}

if(!function_exists('version')){
    function version(string $v = null){
        if(!empty($v)){ 
            if(!empty(config("app.versions.$v"))){
                \Config::set('app.version',$v);
            }
        }
        return config('app.version');
    }
}

if(!function_exists('RandomString')){
    function RandomString(int $length){
        return \Str::random($length);
    }
}

if(!function_exists('normalize')){
    function normalize(array &$data, $normalizations){
        foreach($normalizations as $key => $functions){
            if(array_key_exists($key,$data)){
                foreach($functions as $function){
                    $data[$key] = $function($data[$key]);
                }
            }
        }
    }
}

if(!function_exists('thisOrThat')){
    function thisOrThat($_this,$that){
        if(!$_this || empty($_this)){
            return $that;
        }
        return $_this;
    }
}

if(!function_exists('TimeToDate')){
    function TimeToDate($epoch){
        if(is_numeric($epoch)){
            return date("Y-m-d H:i:s", $epoch);
        }
        return false;
    }
}

if(!function_exists('DateToTime')){
    function DateToTime($date){
        return strtotime($date);
    }
}

if(!function_exists('hasPrefix')){
    function hasPrefix(string $haystack, string $needle){
        return (strpos($haystack,$needle) === 0 );
    }
}