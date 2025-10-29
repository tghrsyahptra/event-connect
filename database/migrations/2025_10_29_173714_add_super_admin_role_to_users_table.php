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
            // Update the role enum to include super_admin
            $table->enum('role', ['admin', 'participant', 'super_admin'])->default('participant')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back to original enum
            $table->enum('role', ['admin', 'participant'])->default('participant')->change();
        });
    }
};
