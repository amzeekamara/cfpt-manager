<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update departments table: add head_user_id and description
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'head_user_id')) {
                $table->foreignId('head_user_id')->nullable()->after('name')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('departments', 'description')) {
                $table->text('description')->nullable()->after('head_user_id');
            }
        });

        // 2. Update users table: add department_id
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('role_id')->constrained('departments')->nullOnDelete();
            }
        });

        // 3. Create classrooms table (Salles par département)
        if (!Schema::hasTable('classrooms')) {
            Schema::create('classrooms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
                $table->string('name'); // Ex: Salle A101, Atelier Froid 1
                $table->integer('capacity')->default(30);
                $table->string('type')->default('cours'); // cours, atelier, labo
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Create timetables table (Emplois du temps des étudiants et formateurs selon les salles)
        if (!Schema::hasTable('timetables')) {
            Schema::create('timetables', function (Blueprint $table) {
                $table->id();
                $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
                $table->foreignId('seminar_id')->constrained('seminars')->cascadeOnDelete();
                $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
                $table->foreignId('trainer_id')->constrained('trainers')->cascadeOnDelete();
                $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
                $table->string('day_of_week'); // Lundi, Mardi, Mercredi, Jeudi, Vendredi, Samedi
                $table->time('start_time');
                $table->time('end_time');
                $table->decimal('planned_hours', 4, 2)->default(2.00);
                $table->string('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Update trainer_sessions table: link with timetable_id & planned_hours for compliance check
        Schema::table('trainer_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('trainer_sessions', 'timetable_id')) {
                $table->foreignId('timetable_id')->nullable()->after('topic_id')->constrained('timetables')->nullOnDelete();
            }
            if (!Schema::hasColumn('trainer_sessions', 'planned_hours')) {
                $table->decimal('planned_hours', 4, 2)->nullable()->after('hours_taught');
            }
            if (!Schema::hasColumn('trainer_sessions', 'is_compliant')) {
                $table->boolean('is_compliant')->default(true)->after('planned_hours');
            }
        });

        // 6. Create evaluations table (Devoirs, TP, Examens)
        if (!Schema::hasTable('evaluations')) {
            Schema::create('evaluations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seminar_id')->constrained('seminars')->cascadeOnDelete();
                $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
                $table->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
                $table->string('title');
                $table->string('type')->default('devoir'); // devoir, tp, examen
                $table->decimal('coefficient', 4, 2)->default(1.00);
                $table->decimal('max_score', 5, 2)->default(20.00);
                $table->date('evaluation_date');
                $table->boolean('is_published')->default(true); // Restitution des notes aux étudiants
                $table->timestamps();
            });
        }

        // 7. Create grades table (Notes des étudiants par évaluation)
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
                $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
                $table->decimal('score', 5, 2)->nullable();
                $table->string('appreciation')->nullable();
                $table->timestamps();

                $table->unique(['evaluation_id', 'registration_id']);
            });
        }

        // 8. Ensure 'Chef de Département' role exists
        if (Schema::hasTable('roles')) {
            Role::firstOrCreate(
                ['name' => 'Chef de Département'],
                ['description' => 'Administrateur pédagogique d\'un département']
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
        Schema::dropIfExists('evaluations');

        Schema::table('trainer_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('trainer_sessions', 'timetable_id')) {
                $table->dropConstrainedForeignId('timetable_id');
            }
            if (Schema::hasColumn('trainer_sessions', 'planned_hours')) {
                $table->dropColumn(['planned_hours', 'is_compliant']);
            }
        });

        Schema::dropIfExists('timetables');
        Schema::dropIfExists('classrooms');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'department_id')) {
                $table->dropConstrainedForeignId('department_id');
            }
        });

        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'head_user_id')) {
                $table->dropConstrainedForeignId('head_user_id');
            }
            if (Schema::hasColumn('departments', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
