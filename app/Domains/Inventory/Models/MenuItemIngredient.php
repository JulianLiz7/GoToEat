<?php

namespace App\Domains\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Menu\Models\MenuItem;

class MenuItemIngredient extends Model
{
    protected $table = 'menu_item_ingredients';

    protected $fillable = [
        'menu_item_id', 'inventory_item_id', 'quantity_required',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:2',
    ];

    public function menuItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function inventoryItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
