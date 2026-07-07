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
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 1. Identité
            $table->string('first_names');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('birth_place');
            $table->enum('gender', ['M', 'F']);
            $table->string('nationality');
            $table->string('residence_country');
            $table->string('photo_path')->nullable();
            $table->enum('family_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
            $table->string('religion')->nullable();

            // 2. Coordonnées
            $table->text('address');
            $table->string('phone');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_email')->nullable();
            $table->string('emergency_contact_relation');

            // 3. Profil Professionnel
            $table->string('highest_degree')->nullable();
            $table->string('current_profession')->nullable();
            $table->string('current_employer')->nullable();
            $table->string('previous_employer')->nullable();
            $table->text('job_description')->nullable();
            $table->text('training_utility')->nullable();

            // 4. Santé & Logistique
            $table->text('health_record_obs')->nullable();
            $table->text('health_questionnaire_json')->nullable(); // For Yes/No answers
            $table->string('insurance_details')->nullable();
            $table->decimal('insurance_premium', 15, 2)->nullable();
            $table->enum('accommodation_preference', ['hotel', 'allocation', 'none'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
