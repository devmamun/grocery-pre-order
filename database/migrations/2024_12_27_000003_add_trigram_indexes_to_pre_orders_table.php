<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enable the pg_trgm extension for trigram matching
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');

        // Add GIN indexes for trigram search on 'name' and 'email' columns
        DB::statement('CREATE INDEX idx_pre_orders_name_trgm ON pre_orders USING gin (name gin_trgm_ops)');
        DB::statement('CREATE INDEX idx_pre_orders_email_trgm ON pre_orders USING gin (email gin_trgm_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the indexes if rolled back
        DB::statement('DROP INDEX IF EXISTS idx_pre_orders_name_trgm');
        DB::statement('DROP INDEX IF EXISTS idx_pre_orders_email_trgm');
    }
};
