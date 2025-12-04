<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->text('feedback_summary')->nullable()->after('description');
            $table->timestamp('feedback_summary_generated_at')->nullable();
            $table->integer('feedback_count_at_summary')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['feedback_summary', 'feedback_summary_generated_at', 'feedback_count_at_summary']);
        });
    }
};