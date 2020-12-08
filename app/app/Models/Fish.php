<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Support\Traits\hasFishType;

class Fish extends Base
{
    use HasFactory, hasFishType;

    const TABLE = 'fishes';
    public $table = self::TABLE;

    protected $hidden = [
        'user_id', 'type_id'
    ];

    protected $fillable = [
        'created_at','updated_at','type_id',
    ];

    public function user(){
        return $this->user_id;
    }

    public function talk(){
        return RandomString(rand(6,32));
    }
}
