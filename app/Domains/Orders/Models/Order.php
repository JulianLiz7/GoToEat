<?php

namespace App\Domains\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Tables\Models\RestaurantTable;
use App\Domains\Auth\Models\User;
use App\Domains\Finance\Models\Tip;

class Order extends Model
{
    protected $fillable = [
        'restaurant_id', 'table_id', 'waiter_id', 'status', 'total', 'tips', 'items',
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
        'tips'  => 'decimal:2',
    ];

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function waiter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function tipRecords(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tip::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
