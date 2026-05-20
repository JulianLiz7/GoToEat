<?php

namespace App\Domains\Reservations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Auth\Models\User;
use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Staff\Models\Employee;
use App\Domains\Tables\Models\RestaurantTable;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'restaurant_id', 'table_id', 'waiter_id',
        'reservation_date', 'reservation_time', 'party_size',
        'status', 'selected_items', 'notes', 'qr_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $reservation) {
            if (empty($reservation->qr_token)) {
                $reservation->qr_token = Str::random(40);
            }
        });
    }

    protected $casts = [
        'reservation_date'  => 'date',
        'selected_items'    => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
        return $this->belongsTo(Employee::class, 'waiter_id');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Pendiente',
            'confirmed' => 'Confirmada',
            'completed' => 'Finalizada',
            'cancelled' => 'Cancelada',
            default     => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'   => 'amber',
            'confirmed' => 'secondary',
            'completed' => 'blue',
            'cancelled' => 'error',
            default     => 'gray',
        };
    }
}
