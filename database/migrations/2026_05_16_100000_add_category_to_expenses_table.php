<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'category')) {
                $table->string('category', 100)->nullable()->after('name');
            }
            if (!Schema::hasColumn('expenses', 'notes')) {
                $table->text('notes')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('expenses', 'due_date')) {
                $table->date('due_date')->nullable()->after('expense_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            foreach (['category', 'notes', 'due_date'] as $col) {
                if (Schema::hasColumn('expenses', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
