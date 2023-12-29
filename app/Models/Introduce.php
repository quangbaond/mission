<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Introduce extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'introduced_id',
    ];


    // người giới thiệu

//    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo(User::class, 'user_id', 'id');
//    }

    // người được giới thiệu

    public function introduced(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'introduced_id');
    }

    // người được giới thiệu đã xác thực

    public function introducedVerified(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // where phone verified at is not null
        return $this->belongsTo(User::class, 'introduced_id')->where('phone_verified_at', '!=', null);
    }

}
