<?php

namespace App\Domains\Staff\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Restaurant\Models\Restaurant;
use App\Domains\Auth\Models\User;

class StaffNotification extends Model
{
    protected $table = 'staff_notifications';

    protected $fillable = [
        'restaurant_id', 'sender_id', 'title', 'message', 'type', 'expires_at', 'archived_at',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'archived_at' => 'datetime',
    ];

    // ── Scopes ────────────────────────────────────────────────────────

    /** Activas: no archivadas y no vencidas (o sin vencimiento) */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at')
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    /** Vencidas: sin archivar pero con expires_at en el pasado */
    public function scopeExpired($query)
    {
        return $query->whereNull('archived_at')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /** Historial completo (activas + vencidas, sin las archivadas) */
    public function scopeHistory($query)
    {
        return $query->whereNull('archived_at')->latest();
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return is_null($this->archived_at)
            && (is_null($this->expires_at) || $this->expires_at->isFuture());
    }

    public function isExpired(): bool
    {
        return !is_null($this->expires_at) && $this->expires_at->isPast();
    }

    public function timeLeft(): string
    {
        if (is_null($this->expires_at)) return 'Permanente';
        if ($this->isExpired())         return 'Vencida';
        return $this->expires_at->diffForHumans(['parts' => 1]);
    }

    // ── Relaciones ────────────────────────────────────────────────────

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function sender(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
