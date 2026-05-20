<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            if (!Schema::hasColumn('tables', 'waiter_id')) {
                $table->unsignedBigInteger('waiter_id')->nullable()->after('reservation_notes');
                $table->foreign('waiter_id')->references('id')->on('employees')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            if (Schema::hasColumn('tables', 'waiter_id')) {
                $table->dropForeign(['waiter_id']);
                $table->dropColumn('waiter_id');
            }
        });
    }
};
