<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'code',
        'is_admin',
        'balance',
        'balance_pending',
        'balance_withdraw',
        'balance_deposit',
        'balance_introduce',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    // auto generate code when create user

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $code = substr(md5(rand()), 0, 7);
            $model->code = $code;
        });
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function mission(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Mission::class, 'user_missions', 'user_id', 'mission_id')->withTimestamps();
    }
    public function userMission(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserMission::class, 'user_id', 'id');
    }

    public function withDraw(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WithDraw::class, 'user_id', 'id');
    }

    public function introduce(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Introduce::class, 'user_id', 'id');
    }

    public function introduced(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Introduce::class, 'introduced_id', 'id');
    }

    public function introducedVerified(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // where user phone verified at is not null
        return $this->hasMany(Introduce::class, 'introduced_id', 'id')->where('phone_verified_at', '!=', null);

    }

}
