<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminars', function (Blueprint $table) {
            if (!Schema::hasColumn('seminars', 'training_type')) {
                $table->string('training_type')->default('initiale')->after('status'); // 'initiale' (Filière BTS/BT) ou 'continue' (Formation Continue)
            }
            if (!Schema::hasColumn('seminars', 'partner_company')) {
                $table->string('partner_company')->nullable()->after('training_type'); // Entreprise ou Partenaire demandeur
            }
            if (!Schema::hasColumn('seminars', 'duration_label')) {
                $table->string('duration_label')->nullable()->after('partner_company'); // Ex: 1 mois, 2 mois, 3 mois
            }
            if (!Schema::hasColumn('seminars', 'fee_amount')) {
                $table->decimal('fee_amount', 12, 2)->default(0)->after('duration_label'); // Tarif / Coût de la formation continue (FCFA)
            }
            if (!Schema::hasColumn('seminars', 'specific_theme')) {
                $table->string('specific_theme')->nullable()->after('fee_amount'); // Thème spécifique demandé par l'entreprise
            }
        });

        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'company_name')) {
                $table->string('company_name')->nullable()->after('status');
            }
            if (!Schema::hasColumn('registrations', 'certificate_issued_at')) {
                $table->timestamp('certificate_issued_at')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('registrations', 'certificate_number')) {
                $table->string('certificate_number')->nullable()->after('certificate_issued_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'certificate_issued_at', 'certificate_number']);
        });

        Schema::table('seminars', function (Blueprint $table) {
            $table->dropColumn(['training_type', 'partner_company', 'duration_label', 'fee_amount', 'specific_theme']);
        });
    }
};
