<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestName extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'gender', 'origin_id', 'meaning', 'suggest_change'];
}
