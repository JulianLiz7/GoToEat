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
        'owner_id', 'name', 'slug', 'description', 'logo', 'logo_path', 'cover_path',
        'primary_color', 'secondary_color',
        'category', 'cuisine_type', 'address', 'phone', 'whatsapp', 'email',
        'website', 'opening_hours', 'status',
        'price_range', 'avg_rating', 'reviews_count',
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

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Domains\Reservations\Models\RestaurantReview::class);
    }

    public function reservations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Domains\Reservations\Models\Reservation::class);
    }

    public function starsHtml(int $rating = null): string
    {
        $r = $rating ?? round($this->avg_rating ?? 0);
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $fill = $i <= $r ? '1' : '0';
            $html .= "<span class=\"material-symbols-outlined text-[16px] text-amber-400\" style=\"font-variation-settings:'FILL' {$fill}\">star</span>";
        }
        return $html;
    }
}
