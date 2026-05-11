<?php

namespace App\Domains\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Inventory\Models\MenuItemIngredient;

class MenuItem extends Model
{
    protected $fillable = [
        'restaurant_id', 'name', 'category', 'description',
        'price', 'image', 'tags', 'available', 'is_featured', 'prep_time',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'available'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function ingredients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenuItemIngredient::class);
    }

    public function inventoryItems(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            InventoryItem::class,
            'menu_item_ingredients',
            'menu_item_id',
            'inventory_item_id'
        )->withPivot('quantity_required')->withTimestamps();
    }
}
