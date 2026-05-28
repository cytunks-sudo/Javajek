<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserRole;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected $fillable = [
    'name',
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
