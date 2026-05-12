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

    public int $tries   = 3;
    public int $timeout = 60;

    public function __construct(public readonly AIConversation $conversation) {}

    public function handle(): void
    {
        $aiServiceUrl = config('services.ai.url');

        if (!$aiServiceUrl) {
            // AI service not configured; store placeholder response
            $this->conversation->update([
                'response' => json_encode([
                    'status'  => 'pending',
                    'message' => 'AI service not configured. Connect your RAG endpoint in config/services.php.',
                ]),
            ]);
            return;
        }

        try {
            $response = Http::timeout(30)->post("{$aiServiceUrl}/analyze", [
                'prompt'        => $this->conversation->prompt,
                'restaurant_id' => $this->conversation->restaurant_id,
            ]);

            $this->conversation->update([
                'response' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('AI analysis failed', [
                'conversation_id' => $this->conversation->id,
                'error'           => $e->getMessage(),
            ]);

            $this->fail($e);
        }
    }

    public string $queue = 'ai';
}
