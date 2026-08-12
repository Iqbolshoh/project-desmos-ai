<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Composite indexes for the hot query paths. Foreign keys already carry
     * their own single-column indexes (added by constrained()), so only the
     * filter/sort combinations the controllers actually use are added here.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Practice picks random questions by topic + difficulty; the
            // diagnostic pulls its fixed question set by the flag.
            $table->index(['topic_id', 'difficulty'], 'questions_topic_difficulty_index');
            $table->index('is_diagnostic', 'questions_is_diagnostic_index');
        });

        Schema::table('question_attempts', function (Blueprint $table) {
            // History listing and daily-progress stats: newest first per user.
            $table->index(['user_id', 'created_at'], 'question_attempts_user_created_index');
        });

        Schema::table('ai_tutor_sessions', function (Blueprint $table) {
            // AI quota counting filters by user and today's date.
            $table->index(['user_id', 'created_at'], 'ai_tutor_sessions_user_created_index');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            // AI quota counting: today's user-authored messages per thread.
            $table->index(['chat_thread_id', 'role', 'created_at'], 'chat_messages_thread_role_created_index');
        });

        Schema::table('diagnostic_results', function (Blueprint $table) {
            // Latest completed diagnostic per user drives the dashboard.
            $table->index(['user_id', 'completed_at'], 'diagnostic_results_user_completed_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex('questions_topic_difficulty_index');
            $table->dropIndex('questions_is_diagnostic_index');
        });

        Schema::table('question_attempts', function (Blueprint $table) {
            $table->dropIndex('question_attempts_user_created_index');
        });

        Schema::table('ai_tutor_sessions', function (Blueprint $table) {
            $table->dropIndex('ai_tutor_sessions_user_created_index');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('chat_messages_thread_role_created_index');
        });

        Schema::table('diagnostic_results', function (Blueprint $table) {
            $table->dropIndex('diagnostic_results_user_completed_index');
        });
    }
};
