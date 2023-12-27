<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'url',
        'reward',
        'exp',
        'type',
        'is_public',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_mission', 'mission_id', 'user_id')->withTimestamps();
    }

    public function userMission(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserMission::class, 'mission_id', 'id');
    }
}
