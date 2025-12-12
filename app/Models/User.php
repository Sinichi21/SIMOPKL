<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'email',
        'password',
        'verification_string',
        'role_id',
        'status',
        'pp_url',
        'is_registered'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $dates = ['deleted_at'];

    public function sendPasswordResetNotification($token): void
    {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->email
        ], false));

        $this->notify(new ResetPasswordNotification($url, $this->email));
    }

    public function scopeAvailableEmail($query, $email)
    {
        return $query->where('email', $email)->where('is_registered', false);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function awardee(): HasOne
    {
        return $this->hasOne(Awardee::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function isSuperAdmin()
    {
        return $this->admin()->exists();
    }

    public function chats(): HasMany
    {
        return $this->hasMany(ThreadChat::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
