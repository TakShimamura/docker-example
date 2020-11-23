<?php

namespace App\Support\Facades;


use Illuminate\Database\Eloquent\Model;

/**
 * declare traits
 */
use App\Support\Traits\HasRandom;
use App\Support\Traits\HasFishes;

class User extends Model{

    const FOREIGN_KEY = 'user_id';
    const PRIMARY_KEY = 'id';
    
    protected $keyType = 'string';
    
    /**
     * implement traits
     */
    use HasRandom, HasFishes;

    /**
     * Build this user object from passed in user object.
     */
    public function __construct($object){
        foreach($object as $property => $value){
            $this->$property = $value;
        }
    }



}