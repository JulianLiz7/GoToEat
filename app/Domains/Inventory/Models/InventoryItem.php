<?php

namespace App\Domains\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Menu\Models\MenuItem;

class InventoryItem extends Model
{
    protected $fillable = [
        'restaurant_id', 'name', 'category', 'quantity',
        'unit', 'min_stock', 'cost_price', 'status',
    ];

    protected $casts = [
        'quantity'   => 'decimal:2',
        'min_stock'  => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItems(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            MenuItem::class,
            'menu_item_ingredients',
            'inventory_item_id',
            'menu_item_id'
        )->withPivot('quantity_required')->withTimestamps();
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock;
    }
}
