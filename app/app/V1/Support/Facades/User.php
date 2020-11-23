<?php

namespace App\V1\Support\Facades;

/**
 * declare traits
 */
use App\V1\Support\Traits\HasRandom;

class User {

    /**
     * implement traits
     */
    use HasRandom;

    /**
     * Build this user object from passed in user object.
     */
    public function __construct($object){
        foreach($object as $property => $value){
            $this->$property = $value;
        }
    }

}