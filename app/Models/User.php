<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notification;
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'motdepasse',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'motdepasse',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => Role::class,      
        'is_active' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->motdepasse;
    }

    public function setMotdepasseAttribute($value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['motdepasse'] = $value;
            return;
        }

        $looksHashed = str_starts_with($value, '$2y$')
            || str_starts_with($value, '$argon2i$')
            || str_starts_with($value, '$argon2id$');

        $this->attributes['motdepasse'] = $looksHashed ? $value : Hash::make($value);
    }

    public function champs()
    {
        return $this->hasMany(Champ::class);
    }

    public function activitesAgricoles()
    {
        return $this->hasMany(ActiviteAgricole::class);
    }

    public function diagnosticsIa()
    {
        return $this->hasMany(DiagnosticIa::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = rtrim(config('app.frontend_url'), '/')
            . '/reset-password?token=' . $token
            . '&email=' . urlencode($this->email);

        $this->notify(new ResetPasswordNotification($url));
    }
}