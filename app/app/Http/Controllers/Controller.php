<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){
        $this->setSubject();
    }

    public function setSubject(){
        \Config::set('subject',$this->getSubject());
    }

    public function getSubject(){
        if (empty($this->subject)){
            $class = get_class($this);
            $paths = explode('\\',$class);
            $name = $paths[count($paths)-1];
            $this->subject = strtolower(str_replace('sController','',$name));
        }
        return $this->subject;
    }

    public function find($record){
        if(gettype($record) == 'object'){
            return $record;
        }

        $model = contract(subject());

        return FindOrAbort(
            $model->find($record),
            ['No record found.'],
            404
        );
    
    }
}
