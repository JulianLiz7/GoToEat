<?php

namespace App\Jobs;

use App\Domains\AI\Models\AIConversation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessAIAnalysis implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(public readonly AIConversation $conversation)
    {
        $this->onQueue('ai');
    }

    public function handle(): void
    {
        $apiKey = config('services.gemini.api_key');

        if (! $apiKey) {
            $this->conversation->update([
                'response' => json_encode([
                    'status' => 'pending',
                    'message' => 'Falta configurar la API Key de Gemini. Define GEMINI_API_KEY en tu archivo .env.',
                ]),
            ]);

            return;
        }

        try {
            $restaurant = $this->conversation->restaurant;
            $now = now();

            // ── Inventario ────────────────────────────────────────────
            $inventoryItems = $restaurant->inventoryItems()->get();
            $inventoryText = $inventoryItems->isEmpty()
                ? 'Inventario vacío.'
                : $inventoryItems->map(fn($i) => "{$i->name}: {$i->quantity} {$i->unit} (mínimo: {$i->min_stock})")->implode('; ');

            $lowStock = $inventoryItems->filter(fn($i) => $i->quantity <= $i->min_stock);
            $lowStockText = $lowStock->isEmpty()
                ? 'Ninguno.'
                : $lowStock->map(fn($i) => "{$i->name} ({$i->quantity} {$i->unit})")->implode(', ');

            // ── Personal ──────────────────────────────────────────────
            $staffCount = $restaurant->employees()->count();

            // ── Mesas ─────────────────────────────────────────────────
            $tables = $restaurant->tables()->get();
            $tablesCount = $tables->count();
            $availableTables = $tables->where('status', 'disponible')->count();
            $occupiedTables  = $tables->where('status', 'ocupada')->count();

            // ── Órdenes del mes ───────────────────────────────────────
            $monthOrders = $restaurant->orders()
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->get();
            $monthRevenue  = $monthOrders->sum('total');
            $monthCount    = $monthOrders->count();
            $avgTicket     = $monthCount > 0 ? round($monthRevenue / $monthCount) : 0;

            // Órdenes de hoy
            $todayOrders   = $restaurant->orders()->whereDate('created_at', $now->toDateString())->get();
            $todayRevenue  = $todayOrders->sum('total');
            $todayCount    = $todayOrders->count();

            // ── Egresos del mes ───────────────────────────────────────
            $monthExpenses = $restaurant->expenses()
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->sum('amount');

            // ── Reservas ──────────────────────────────────────────────
            $todayReservations = $restaurant->reservations()
                ->whereDate('reservation_date', $now->toDateString())
                ->count();
            $pendingReservations = $restaurant->reservations()
                ->where('status', 'pending')
                ->count();

            // ── Menú ──────────────────────────────────────────────────
            $menuItems = $restaurant->menuItems()->where('available', true)->get();
            $menuText  = $menuItems->isEmpty()
                ? 'Sin platos en el menú.'
                : $menuItems->map(fn($m) => "{$m->name} (\${$m->price})")->implode(', ');

            // ── System Prompt ─────────────────────────────────────────
            $systemPrompt = "Eres el asistente experto de IA para el gerente del restaurante '{$restaurant->name}'.
Respondes de forma profesional, amigable y concisa en español.
Usa los datos en tiempo real que te proporciono para responder con precisión.

=== DATOS DEL RESTAURANTE ===

FINANZAS (mes actual, {$now->translatedFormat('F Y')}):
- Ingresos del mes: \${$monthRevenue}
- Egresos del mes: \${$monthExpenses}
- Profit neto: \$" . ($monthRevenue - $monthExpenses) . "
- Órdenes este mes: {$monthCount}
- Ticket promedio: \${$avgTicket}

HOY ({$now->translatedFormat('d \\de F')}):
- Ingresos hoy: \${$todayRevenue}
- Órdenes hoy: {$todayCount}
- Reservas hoy: {$todayReservations}
- Reservas pendientes: {$pendingReservations}

OPERACIONES:
- Mesas totales: {$tablesCount}
- Mesas disponibles: {$availableTables}
- Mesas ocupadas: {$occupiedTables}
- Empleados registrados: {$staffCount}

INVENTARIO:
- Items en stock bajo: {$lowStockText}
- Inventario completo: {$inventoryText}

MENÚ ACTIVO:
{$menuText}

=== FIN DE DATOS ===

Responde usando estos datos. Si la pregunta no tiene relación con el restaurante, indícalo brevemente.";


            // 3. Hacer la petición a Gemini API
            $response = Http::timeout(45)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $this->conversation->prompt],
                            ],
                        ],
                    ],
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $systemPrompt],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1000,
                    ],
                ]);

            if ($response->successful()) {
                $reply = $response->json('candidates.0.content.parts.0.text');
                $this->conversation->update([
                    'response' => $reply,
                ]);
            } else {
                Log::error('Gemini Error: '.$response->body());
                $this->conversation->update([
                    'response' => 'Hubo un error al comunicarme con Gemini. Verifica tu API Key o saldo.',
                ]);
            }

        } catch (\Throwable $e) {
            Log::error('AI analysis failed', [
                'conversation_id' => $this->conversation->id,
                'error' => $e->getMessage(),
            ]);

            $this->fail($e);
        }
    }
}
