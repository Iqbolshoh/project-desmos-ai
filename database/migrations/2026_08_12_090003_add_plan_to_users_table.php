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
        Schema::table('users', function (Blueprint $table) {
            // Null falls back to the default free plan, so existing rows keep
            // working without a backfill.
            $table->foreignId('plan_id')->nullable()->after('avatar_url')
                ->constrained('plans')->nullOnDelete();

            // Null on a paid plan means the subscription never expires.
            $table->timestamp('plan_expires_at')->nullable()->after('plan_id');

            $table->index(['plan_id', 'plan_expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['plan_id', 'plan_expires_at']);
            $table->dropConstrainedForeignId('plan_id');
            $table->dropColumn('plan_expires_at');
        });
    }
};
