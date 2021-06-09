<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'name', 'name_de', 'name_en', 'native', 'active'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function origin() {
        return $this->hasMany(BabyName::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }
}
