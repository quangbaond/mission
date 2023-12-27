<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithDraw extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'card_value',
        'card_name',
        'phone',
        'bank_number',
        'bank_owner',
        'method',
        'bank_name',
        'user_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
