<?php

namespace App\Domains\Tables\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Orders\Models\Order;

class RestaurantTable extends Model
{
    protected $table = 'tables';

    protected $fillable = [
        'restaurant_id', 'number', 'capacity', 'zone', 'status',
        'qr_code', 'is_reserved', 'reservation_time', 'customer_name',
        'customer_phone', 'party_size', 'reservation_notes',
    ];

    protected $casts = [
        'is_reserved'      => 'boolean',
        'reservation_time' => 'datetime',
    ];

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'disponible';
    }
}
