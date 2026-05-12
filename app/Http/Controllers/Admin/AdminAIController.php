<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\AI\Models\AIConversation;
use App\Jobs\ProcessAIAnalysis;
use Illuminate\Http\Request;

class AdminAIController extends Controller
{
    public function index()
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $conversations = $restaurant->aiConversations()->latest()->take(20)->get();

        // Alertas proactivas de stock bajo
        $alerts = $restaurant->inventoryItems()
            ->whereColumn('quantity', '<=', 'min_stock')
            ->where('status', 'active')
            ->get()
            ->map(fn ($item) => [
                'type'    => 'low_stock',
                'level'   => 'warning',
                'message' => "Stock bajo: {$item->name} — {$item->quantity} {$item->unit} (mínimo: {$item->min_stock})",
            ]);

        return view('admin.ai', compact('restaurant', 'conversations', 'alerts'));
    }

    public function ask(Request $request)
    {
        $request->validate(['prompt' => ['required', 'string', 'max:2000']]);

        $restaurant = auth()->user()->ownedRestaurants()->first();

        $conversation = $restaurant->aiConversations()->create([
            'user_id'  => auth()->id(),
            'prompt'   => $request->prompt,
            'response' => null,
        ]);

        ProcessAIAnalysis::dispatch($conversation);

        return redirect()->route('admin.ai')->with('success', 'Tu pregunta fue enviada al asistente.');
    }
}
