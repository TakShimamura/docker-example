<?php

namespace App\V1\Support\Traits;



trait HasRandom
{

    public function random(int $length=8){
        return 'This is V1 random string: '.RandomString($length);
    }

}
