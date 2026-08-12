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
            // Google's stable numeric subject ("sub") claim. Kept separate from
            // the email because a user may change their Google email address
            // while the subject stays the same.
            $table->string('google_id')->nullable()->unique()->after('email_verified_at');

            // Remote picture URL returned by Google. Uploaded avatars live on
            // the public disk (student_profiles.avatar_path) and take priority.
            $table->string('avatar_url')->nullable()->after('google_id');

            // Users created through Google have no password of their own.
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['google_id']);
            $table->dropColumn(['google_id', 'avatar_url']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
