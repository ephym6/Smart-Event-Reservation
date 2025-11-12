<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // For authentication support
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; // since it's not the default 'id'

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'otp_hash',
        'otp_expires',
        'phone_number',
        'role',
        'two_factor_secret',
        'is_verified',
    ];

    protected $hidden = [
        'password_hash',
        'otp_hash',
        'two_factor_secret',
        'remember_token',
    ];

    protected $casts = [
        'otp_expires' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the password field for authentication.
     * Laravel's Auth::attempt() looks for $user->getAuthPassword()
     * Our DB stores the password hash in 'password_hash', not the Laravel default 'password'
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
