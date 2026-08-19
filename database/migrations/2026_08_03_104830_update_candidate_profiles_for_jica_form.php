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
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->string('matricule')->nullable()->after('user_id');
            $table->string('grade')->nullable()->after('matricule');
            $table->string('job_function')->nullable()->after('job_description');

            // Make existing fields nullable
            $table->string('first_names')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->date('birth_date')->nullable()->change();
            $table->string('birth_place')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('nationality')->nullable()->change();
            $table->string('residence_country')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('emergency_contact_name')->nullable()->change();
            $table->string('emergency_contact_phone')->nullable()->change();
            $table->string('emergency_contact_relation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table) {
            $table->dropColumn(['matricule', 'grade', 'job_function']);
        });
    }
};
