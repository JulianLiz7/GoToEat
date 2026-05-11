<?php

namespace App\Domains\Restaurant\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Domains\Staff\Models\Employee;
use App\Domains\Menu\Models\MenuItem;
use App\Domains\Tables\Models\RestaurantTable;
use App\Domains\Orders\Models\Order;
use App\Domains\Finance\Models\Expense;
use App\Domains\Finance\Models\Tip;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\AI\Models\AIConversation;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'logo',
        'category', 'cuisine_type', 'address', 'phone', 'email', 'status',
    ];

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'restaurant_user')
            ->withPivot('role', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function menuItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function inventoryItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function tables(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function expenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function tips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tip::class);
    }

    public function aiConversations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AIConversation::class);
    }
}
