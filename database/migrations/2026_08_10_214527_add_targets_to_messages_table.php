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
        Schema::table('messages', function (Blueprint $table) {
            $table->json('target_roles')->nullable()->after('body');
            $table->json('target_seminars')->nullable()->after('target_roles');
            $table->json('target_departments')->nullable()->after('target_seminars');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['target_roles', 'target_seminars', 'target_departments']);
        });
    }
};
