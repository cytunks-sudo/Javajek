<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserRole;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'latitude',
        'longitude',
        'photo',
        'notif_sound_mode',
        'notif_sound_file',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'owner_id');
    }

    public function roles()
    {
        return $this->hasMany(UserRole::class);
    }

    public function hasRole($role)
    {
        return $this->roles()
            ->where('role', $role)
            ->where('status', 'approved')
            ->exists();
    }
}