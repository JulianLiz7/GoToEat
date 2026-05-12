<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Events\OrderPlaced;
use App\Events\OrderCompleted;
use App\Listeners\DeductInventoryOnOrder;
use App\Listeners\CalculateTipsOnOrder;
use App\Listeners\SendToAIAnalysis;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // OrderCompleted: deduct inventory (escandallo), settle tips (Ley 1935), feed AI pipeline
        Event::listen(OrderCompleted::class, DeductInventoryOnOrder::class);
        Event::listen(OrderCompleted::class, CalculateTipsOnOrder::class);
        Event::listen(OrderCompleted::class, SendToAIAnalysis::class);
    }
}
