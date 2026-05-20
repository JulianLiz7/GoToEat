<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->string('title', 120)->nullable();
            $table->text('body')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'restaurant_id']); // Una reseña por usuario por restaurante
        });

        // Agregar campos de precio y calificación promedio al restaurante
        Schema::table('restaurants', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurants', 'price_range')) {
                $table->enum('price_range', ['$', '$$', '$$$', '$$$$'])->default('$$')->after('opening_hours');
            }
            if (!Schema::hasColumn('restaurants', 'avg_rating')) {
                $table->decimal('avg_rating', 3, 1)->default(0)->after('price_range');
            }
            if (!Schema::hasColumn('restaurants', 'reviews_count')) {
                $table->unsignedInteger('reviews_count')->default(0)->after('avg_rating');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_reviews');
        Schema::table('restaurants', function (Blueprint $table) {
            foreach (['price_range', 'avg_rating', 'reviews_count'] as $col) {
                if (Schema::hasColumn('restaurants', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
