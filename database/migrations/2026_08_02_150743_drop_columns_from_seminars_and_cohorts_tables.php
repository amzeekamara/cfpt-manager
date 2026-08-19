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
        Schema::table('cohorts', function (Blueprint $table) {
            $table->dropColumn('topics');
        });

        Schema::table('seminars', function (Blueprint $table) {
            $table->dropColumn('department_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cohorts', function (Blueprint $table) {
            $table->string('topics')->nullable();
        });

        Schema::table('seminars', function (Blueprint $table) {
            $table->string('department_name')->nullable();
        });
    }
};
