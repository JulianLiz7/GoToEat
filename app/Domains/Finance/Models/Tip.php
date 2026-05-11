<?php

namespace App\Domains\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Staff\Models\Employee;

class Tip extends Model
{
    protected $fillable = [
        'restaurant_id', 'order_id', 'employee_id', 'amount', 'date', 'payment_method',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date'   => 'date',
    ];

    // Ley 1935 de 2018: tips are voluntary and belong entirely to the worker.
    // Cash tips are received directly; card/transfer tips must be transferred by the employer.
    public function requiresTransfer(): bool
    {
        return in_array($this->payment_method, ['card', 'transfer']);
    }

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Domains\Orders\Models\Order::class);
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
