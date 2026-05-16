<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Canal de notificaciones para el personal del restaurante.
 * El admin envía mensajes a todo el equipo con tiempo de vigencia.
 * Los mensajes expirados dejan de mostrarse pero el registro persiste.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'aviso', 'urgente'])->default('info');
            $table->timestamp('expires_at')->nullable(); // null = permanente
            $table->timestamp('archived_at')->nullable(); // manualmente archivado (oculto del feed)
            $table->timestamps();

            $table->index(['restaurant_id', 'expires_at']);
            $table->index(['restaurant_id', 'archived_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_notifications');
    }
};
