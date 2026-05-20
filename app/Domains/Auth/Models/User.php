<?php

namespace App\Domains\Auth\Models;

use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Reservations\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $guard_name = 'web';

    protected $fillable = [
        'name', 'email', 'username', 'password',
        'avatar', 'phone', 'date_of_birth', 'dietary_preferences',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'date_of_birth'        => 'date',
            'dietary_preferences'  => 'array',
            'password'             => 'hashed',
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

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function avatarUrl(): string
    {
        if ($this->avatar && \Storage::disk('public')->exists($this->avatar)) {
            return \Storage::url($this->avatar);
        }
        $initial = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initial}&background=f97316&color=fff&size=128";
    }
}
