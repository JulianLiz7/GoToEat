<?php

namespace App\Domains\AI\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Domains\Restaurant\Models\Restaurant;

class AIConversation extends Model
{
    protected $table = 'ai_conversations';

    protected $fillable = [
        'user_id', 'restaurant_id', 'prompt', 'response',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
