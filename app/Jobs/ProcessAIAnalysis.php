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
        $apiKey = env('GEMINI_API_KEY');

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

            // 1. Recopilar contexto real de la base de datos
            $inventoryItems = $restaurant->inventoryItems()->get();
            $inventoryText = $inventoryItems->isEmpty() ? 'Inventario vacío.' : $inventoryItems->map(function ($i) {
                return "{$i->name}: {$i->quantity} {$i->unit} (Min: {$i->min_stock})";
            })->implode(', ');

            $staffCount = $restaurant->employees()->count();
            $tablesCount = $restaurant->tables()->count();

            // 2. Construir el System Prompt
            $systemPrompt = "Eres el asistente experto de IA (AI Assistant) para el gerente del restaurante '{$restaurant->name}'.
Te integras directamente en su panel de administración.
Debes responder de forma profesional, clara, amigable y muy concisa.
Aquí tienes los datos en tiempo real de tu restaurante para que puedas responder:
- Mesas registradas: {$tablesCount}
- Empleados registrados: {$staffCount}
- Inventario actual: {$inventoryText}

Cuando el gerente te haga una pregunta, responde usando SOLO esta información. Si te pregunta algo fuera de contexto o que no está en los datos, dile amablemente que como asistente de restaurante, solo tienes acceso a los datos del sistema.";

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
