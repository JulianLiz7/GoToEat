<?php

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password', 'avatar', 'phone'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function ownedRestaurants()
    {
        return $this->hasMany(\App\Domains\Restaurant\Models\Restaurant::class, 'owner_id');
    }

    public function restaurants()
    {
        return $this->belongsToMany(
            \App\Domains\Restaurant\Models\Restaurant::class,
            'restaurant_user'
        )->withPivot('role', 'status', 'joined_at')->withTimestamps();
    }
}
