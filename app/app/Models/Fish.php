<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
    use HasFactory;

    const TABLE = 'fishes';
    public $table = self::TABLE;

    protected $hidden = [
        'user_id',
    ];

    protected $fillable = [
        'created_at','updated_at'
    ];

    public function user(){
        return $this->user_id;
    }

    public function talk(){
        return RandomString(rand(6,32));
    }
}
