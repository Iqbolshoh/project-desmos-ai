<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roadmaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('current_score');
            $table->unsignedSmallInteger('goal_score');
            $table->unsignedSmallInteger('estimated_weeks');
            $table->unsignedSmallInteger('daily_study_minutes');
            $table->json('weekly_plan');
            $table->json('monthly_plan')->nullable();
            $table->unsignedTinyInteger('completion_percent')->default(0);
            $table->enum('status', ['active', 'completed', 'archived'])->default('active');
            $table->timestamp('generated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmaps');
    }
};
