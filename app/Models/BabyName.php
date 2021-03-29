<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabyName extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'origin', 'description', 'gender_id', 'meaning', 'created_at'];
}
