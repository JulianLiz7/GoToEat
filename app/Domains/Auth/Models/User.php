<?php

namespace App\Domains\Auth\Models;

use App\Domains\Restaurant\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $guard_name = 'web';

    protected $fillable = ['name', 'email', 'username', 'password', 'avatar', 'phone'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ownedRestaurants()
    {
        return $this->hasMany(Restaurant::class, 'owner_id');
    }

    public function restaurants()
    {
        return $this->belongsToMany(
            Restaurant::class,
            'restaurant_user'
        )->withPivot('role', 'status', 'joined_at')->withTimestamps();
    }
}
