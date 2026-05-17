<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('shift_date');
            $table->string('shift_name', 50)->default('completo');
            $table->decimal('cash_expected', 14, 2)->default(0);
            $table->decimal('cash_counted', 14, 2)->default(0);
            $table->decimal('difference', 14, 2)->default(0);
            $table->decimal('deposit_amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('denomination_counts')->nullable();
            $table->enum('status', ['draft', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
