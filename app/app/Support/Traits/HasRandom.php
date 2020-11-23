<?php

namespace App\Support\Traits;



trait HasRandom
{



    public function random(int $length=8){
        return RandomString($length);
    }

}
