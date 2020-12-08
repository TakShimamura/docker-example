<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    use HasFactory;

    // public function validate(array $payload = []){
    //     return true;
    // }

    protected $failedValidation = [];

    public function validate(array $payload = null){
        if(empty($payload)){ return true; }
        foreach($this->unique as $key){
            if($this->$key != FindOrDefault($key,$payload)){
                $hasRecord = found(
                    self::where($key,$payload[$key])->first()
                );
                if($hasRecord){ 
                    $this->failedValidation[] = "$key already exists.";
                    return false; 
                }
            }
        }
        return true;
    }

    public function errors(){
        return $this->failedValidations;
    }

    public static function Generate($object, $id = null){
        if(!empty($id)){
            $model = self::where('id',$id)->first();
        } else {
            $model = new static;
        }
        foreach($object as $property => $value){
            $model->$property = $value;
        }
        return $model;
    }

}
