<?php

namespace App\Domains\Staff\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Finance\Models\Tip;

class Employee extends Model
{
    protected $fillable = [
        'user_id', 'restaurant_id', 'position', 'salary',
        'hire_date', 'status', 'emergency_contact', 'notes', 'shifts',
    ];

    protected $casts = [
        'shifts'    => 'array',
        'salary'    => 'decimal:2',
        'hire_date' => 'date',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tip::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
