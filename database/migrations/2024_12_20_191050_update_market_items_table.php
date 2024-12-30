<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('market_items', function (Blueprint $table) {
            $table->foreignId('produce_id')
                ->nullable()
                ->after('user_id')
                ->constrained('current_prices')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('market_items', function (Blueprint $table) {
            $table->dropForeign(['produce_id']);
            $table->dropColumn('produce_id');
        });
    }
};
