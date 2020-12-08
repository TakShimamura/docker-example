<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishType extends Model
{
    use HasFactory;

    public $table = 'fish_types';

    public $fillable = [
        'name',
    ];

    public $hidden = [
        'id', 'created_at','updated_at'
    ];
}
