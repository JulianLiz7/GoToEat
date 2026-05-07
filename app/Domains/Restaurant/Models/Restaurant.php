<?php

namespace App\Domains\Restaurant\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'logo',
        'category', 'cuisine_type', 'address', 'phone', 'email', 'status',
    ];

    public function owner()
    {
        return $this->belongsTo(\App\Domains\Auth\Models\User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(
            \App\Domains\Auth\Models\User::class,
            'restaurant_user'
        )->withPivot('role', 'status', 'joined_at')->withTimestamps();
    }

    public function employees()
    {
        return $this->hasMany(\App\Models\Employee::class);
    }

    public function menuItems()
    {
        return $this->hasMany(\App\Models\MenuItem::class);
    }

    public function tables()
    {
        return $this->hasMany(\App\Models\Table::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function expenses()
    {
        return $this->hasMany(\App\Models\Expense::class);
    }

    public function tips()
    {
        return $this->hasMany(\App\Models\Tip::class);
    }
}
