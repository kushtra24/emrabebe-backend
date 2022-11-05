<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabyName extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'gender_id', 'meaning', 'created_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function post()
    {
        return $this->belongsToMany(Article::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @param $originID
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function origins()
    {
        return $this->belongsToMany(Origin::class, 'baby_name_origin', 'babyName_id', 'origin_id');
//        return $this->belongsToMany(Origin::class, 'baby_name_origin', 'origin_id', 'babyName_id')->wherePivot('origin_id', $originID);
    }

    /**
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }
}
