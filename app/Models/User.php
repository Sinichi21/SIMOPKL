<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = url(route('password.reset', ['token' => $token, 'email' => $this->email], false));

    $this->notify(new ResetPasswordNotification($url, $this->email));
    }

    public function scopeAvailableEmail($query, $email)
    {
        return $query->where('email', $email)->where('is_registered', false);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'verification_string',
        'role_id',
        'status',
        'pp_url',
        'is_registered'
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
    ];

    protected $dates = ['deleted_at'];

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

    public function registers()
    {
        return $this->hasMany(Register::class);
    }
}
