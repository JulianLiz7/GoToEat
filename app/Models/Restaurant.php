<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Restaurant extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'logo',
        'category',
        'cuisine_type',
        'address',
        'phone',
        'email',
        'status',
    ];

    /**
     * Get the owner of the restaurant.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users that belong to the restaurant.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role', 'status', 'joined_at')
                    ->withTimestamps();
    }
}
