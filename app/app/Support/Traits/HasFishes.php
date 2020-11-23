<?php

namespace App\Support\Traits;



trait HasFishes
{


    public function fishes(){
        return $this->hasMany(\App\Models\Fish::class,'user_id','id');
    }

    public function fish($id=null){
        $ids = $this->fishes()->pluck('id');
        if(!found($ids)){ return null; }
        $index = rand(0,count($ids)-1);
        $id = $ids[$index];
        $fish = $this->fishes()->where('id',$id)->first();
        $fish->name = $fish->talk();
        $fish->says = $fish->talk();
        return $fish;
    }


}
