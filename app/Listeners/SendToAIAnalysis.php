<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Jobs\ProcessAIAnalysis;
use App\Domains\AI\Models\AIConversation;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendToAIAnalysis implements ShouldQueue
{
    public string $queue = 'ai';

    public function handle(OrderCompleted $event): void
    {
        $order = $event->order;

        $payload = [
            'event'         => 'order_completed',
            'restaurant_id' => $order->restaurant_id,
            'order_id'      => $order->id,
            'total'         => $order->total,
            'tips'          => $order->tips,
            'items'         => $order->items,
            'timestamp'     => $order->updated_at->toIso8601String(),
        ];

        $conversation = AIConversation::create([
            'restaurant_id' => $order->restaurant_id,
            'user_id'       => null,
            'prompt'        => json_encode($payload),
            'response'      => null,
        ]);

        ProcessAIAnalysis::dispatch($conversation);
    }
}
