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
        Schema::table('events', function (Blueprint $table) {
            $table->enum('event_type', ['offline', 'online', 'hybrid'])->default('offline')->after('location');
            $table->text('contact_info')->nullable()->after('event_type');
            $table->text('requirements')->nullable()->after('contact_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'contact_info', 'requirements']);
        });
    }
};