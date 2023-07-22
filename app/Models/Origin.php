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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function babyNames() {
        return $this->belongsToMany(BabyName::class, 'baby_name_origin', 'origin_id', 'babyName_id');
    }

    /**
     * Determine if the user is an administrator.
     */
    protected function isAdmin(): Attribute
    {
        return new Attribute(
            get: fn () => 'yes',
        );
    }
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }
}
