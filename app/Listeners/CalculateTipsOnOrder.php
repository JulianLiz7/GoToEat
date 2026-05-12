<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Domains\Finance\Models\Tip;
use App\Domains\Staff\Models\Employee;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Registra la propina de la orden completada según Ley 1935 de 2018.
 * La propina pertenece íntegramente al mesero que atendió la mesa.
 * Si el pago es en tarjeta o transferencia, queda marcada como
 * pendiente de giro por parte del empleador.
 */
class CalculateTipsOnOrder implements ShouldQueue
{
    public string $queue = 'finance';

    public function handle(OrderCompleted $event): void
    {
        $order = $event->order;

        if (!$order->tips || $order->tips <= 0) return;

        // Determine payment method from order metadata
        $paymentMethod = $order->items[0]['payment_method'] ?? 'cash';

        // Identify the waiter employee record
        $employee = $order->waiter_id
            ? Employee::where('user_id', $order->waiter_id)
                ->where('restaurant_id', $order->restaurant_id)
                ->first()
            : null;

        Tip::create([
            'restaurant_id'  => $order->restaurant_id,
            'order_id'       => $order->id,
            'employee_id'    => $employee?->id,
            'amount'         => $order->tips,
            'date'           => $order->updated_at->toDateString(),
            'payment_method' => $paymentMethod,
        ]);
    }
}
