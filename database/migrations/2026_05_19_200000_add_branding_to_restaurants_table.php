<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurants', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('restaurants', 'cover_path')) {
                $table->string('cover_path')->nullable()->after('logo_path');
            }
            if (!Schema::hasColumn('restaurants', 'primary_color')) {
                $table->string('primary_color', 7)->default('#f97316')->after('cover_path');
            }
            if (!Schema::hasColumn('restaurants', 'secondary_color')) {
                $table->string('secondary_color', 7)->default('#006c49')->after('primary_color');
            }
            if (!Schema::hasColumn('restaurants', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
            if (!Schema::hasColumn('restaurants', 'opening_hours')) {
                $table->string('opening_hours', 100)->nullable()->after('website');
            }
            if (!Schema::hasColumn('restaurants', 'whatsapp')) {
                $table->string('whatsapp', 20)->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            foreach (['logo_path','cover_path','primary_color','secondary_color','website','opening_hours','whatsapp'] as $col) {
                if (Schema::hasColumn('restaurants', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
