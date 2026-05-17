<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'logo',
        'latitude',
        'longitude',
        'status',
    ];

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
    
    public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}
}