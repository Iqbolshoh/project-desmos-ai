<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('avatar_path')->nullable();
            $table->unsignedSmallInteger('sat_goal_score')->nullable();
            $table->unsignedSmallInteger('sat_current_score')->nullable();
            $table->unsignedInteger('xp')->default(0);
            $table->unsignedSmallInteger('level')->default(1);
            $table->unsignedSmallInteger('streak_current')->default(0);
            $table->unsignedSmallInteger('streak_longest')->default(0);
            $table->date('last_activity_date')->nullable();
            $table->unsignedSmallInteger('daily_goal_minutes')->default(30);
            $table->timestamp('onboarded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
