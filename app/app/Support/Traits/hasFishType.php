<?php

namespace App\Support\Traits;

use App\Models\FishType;

trait hasFishType
{

    public function type(){
        return $this->hasOne(FishType::class,'id','type_id');
    }

}
