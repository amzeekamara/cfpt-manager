<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('evaluations') && !Schema::hasColumn('evaluations', 'instructions')) {
            Schema::table('evaluations', function (Blueprint $table) {
                $table->text('instructions')->nullable()->after('title');
                $table->string('attachment_path')->nullable()->after('instructions');
            });
        }

        if (!Schema::hasTable('course_chapters')) {
            Schema::create('course_chapters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seminar_id')->constrained('seminars')->cascadeOnDelete();
                $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
                $table->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
                $table->string('title');
                $table->longText('content')->nullable();
                $table->string('video_url')->nullable();
                $table->string('attachment_path')->nullable();
                $table->unsignedInteger('duration_minutes')->default(45);
                $table->unsignedInteger('order_index')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('evaluation_submissions')) {
            Schema::create('evaluation_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
                $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
                $table->text('student_comment')->nullable();
                $table->string('file_path')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->text('trainer_feedback')->nullable();
                $table->timestamps();
                $table->unique(['evaluation_id', 'registration_id']);
            });
        }

        if (!Schema::hasTable('course_discussions')) {
            Schema::create('course_discussions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seminar_id')->constrained('seminars')->cascadeOnDelete();
                $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
                $table->foreignId('evaluation_id')->nullable()->constrained('evaluations')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
                $table->string('author_name');
                $table->string('author_role')->default('Étudiant'); // 'Formateur', 'Étudiant', 'Chef de Département'
                $table->text('body');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_discussions');
        Schema::dropIfExists('evaluation_submissions');
        Schema::dropIfExists('course_chapters');
        if (Schema::hasTable('evaluations') && Schema::hasColumn('evaluations', 'instructions')) {
            Schema::table('evaluations', function (Blueprint $table) {
                $table->dropColumn(['instructions', 'attachment_path']);
            });
        }
    }
};
