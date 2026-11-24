<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES & PORTAIL D'ACCUEIL (MODULE 4 & MODULE 8)
|--------------------------------------------------------------------------
*/
Route::get('/', [\App\Http\Controllers\Public\HomeController::class, 'index'])->name('welcome');
Route::get('/postuler', [\App\Http\Controllers\Public\RegistrationController::class, 'create'])->name('public.postuler');

// MODULE 4 : Inscriptions & Candidatures Publiques
Route::get('/inscriptions/{cohort?}', [\App\Http\Controllers\Public\RegistrationController::class, 'create'])->name('public.registration.show');
Route::post('/inscriptions', [\App\Http\Controllers\Public\RegistrationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('public.registration.store');

// MODULE 8 : Formulaires Partageables Dynamiques (Évaluations / Sondages)
Route::get('/f/{token}', [\App\Http\Controllers\Public\FormController::class, 'show'])->name('public.form.show');
Route::post('/f/{token}', [\App\Http\Controllers\Public\FormController::class, 'store'])
    ->middleware('throttle:15,1')
    ->name('public.form.store');


/*
|--------------------------------------------------------------------------
| MODULE 1 : AUTHENTIFICATION, SÉCURITÉ & GESTION DES ACCÈS
|--------------------------------------------------------------------------
*/
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/password/change', [\App\Http\Controllers\PasswordController::class, 'change'])->name('password.change');
Route::post('/password/change', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.change.update');

Route::get('/forgot-password', [\App\Http\Controllers\AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\AuthController::class, 'submitForgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\AuthController::class, 'submitResetPassword'])->name('password.update');

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Module 1 : Intégration Google OAuth2
Route::get('/auth/google', [\App\Http\Controllers\Auth\OAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\OAuthController::class, 'handleGoogleCallback']);

// Module 1 : Initialisation sécurisée de mot de passe (Nouveaux utilisateurs)
Route::get('/setup-password/{token}', [\App\Http\Controllers\Auth\PasswordSetupController::class, 'show'])->name('password.setup');
Route::post('/setup-password', [\App\Http\Controllers\Auth\PasswordSetupController::class, 'store'])->name('password.setup.store');


/*
|--------------------------------------------------------------------------
| ESPACE ADMINISTRATION & PILOTAGE (MODULES 1 À 10)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin', 'jica'])->prefix('admin')->name('admin.')->group(function () {

    // Tableau de bord global
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // MODULE 2 : GESTION DES ÉDITIONS & COHORTES
    Route::get('/cohorts', [\App\Http\Controllers\Admin\CohortController::class, 'index'])->name('cohorts.index');
    Route::get('/cohorts/create', [\App\Http\Controllers\Admin\CohortController::class, 'create'])->name('cohorts.create');
    Route::post('/cohorts', [\App\Http\Controllers\Admin\CohortController::class, 'store'])->name('cohorts.store');
    Route::get('/cohorts/{cohort}/edit', [\App\Http\Controllers\Admin\CohortController::class, 'edit'])->name('cohorts.edit');
    Route::put('/cohorts/{cohort}', [\App\Http\Controllers\Admin\CohortController::class, 'update'])->name('cohorts.update');
    Route::delete('/cohorts/{cohort}', [\App\Http\Controllers\Admin\CohortController::class, 'destroy'])->name('cohorts.destroy');
    Route::patch('/cohorts/{cohort}/toggle', [\App\Http\Controllers\Admin\CohortController::class, 'toggleStatus'])->name('cohorts.toggle');

    // MODULE 3 : STRUCTURE PÉDAGOGIQUE (SÉMINAIRES, THÈMES & DÉPARTEMENTS)
    Route::get('/seminars', [\App\Http\Controllers\Admin\SeminarController::class, 'index'])->name('seminars');
    Route::get('/seminars/create', [\App\Http\Controllers\Admin\SeminarController::class, 'create'])->name('seminars.create');
    Route::post('/seminars', [\App\Http\Controllers\Admin\SeminarController::class, 'store'])->name('seminars.store');
    Route::get('/seminars/{seminar}/edit', [\App\Http\Controllers\Admin\SeminarController::class, 'edit'])->name('seminars.edit');
    Route::put('/seminars/{seminar}', [\App\Http\Controllers\Admin\SeminarController::class, 'update'])->name('seminars.update');
    Route::delete('/seminars/{seminar}', [\App\Http\Controllers\Admin\SeminarController::class, 'destroy'])->name('seminars.destroy');
    Route::patch('/seminars/{seminar}/toggle', [\App\Http\Controllers\Admin\SeminarController::class, 'toggleStatus'])->name('seminars.toggle');

    Route::resource('topics', \App\Http\Controllers\Admin\TopicController::class);
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
    Route::post('/classrooms', [\App\Http\Controllers\Admin\DepartmentController::class, 'storeClassroom'])->name('classrooms.store');
    Route::delete('/classrooms/{classroom}', [\App\Http\Controllers\Admin\DepartmentController::class, 'destroyClassroom'])->name('classrooms.destroy');

    // EMPLOIS DU TEMPS (ÉTUDIANTS, FORMATEURS & SALLES PAR DÉPARTEMENT)
    Route::get('/timetables', [\App\Http\Controllers\Admin\TimetableController::class, 'index'])->name('timetables.index');
    Route::post('/timetables', [\App\Http\Controllers\Admin\TimetableController::class, 'store'])->name('timetables.store');
    Route::delete('/timetables/{timetable}', [\App\Http\Controllers\Admin\TimetableController::class, 'destroy'])->name('timetables.destroy');

    // FORMATIONS CONTINUES PROFESSIONNELLES (COURTE DURÉE, ENTREPRISES & ATTESTATIONS PDF)
    Route::get('/formations-continues', [\App\Http\Controllers\Admin\ContinuingEducationController::class, 'index'])->name('continuing-education.index');
    Route::post('/formations-continues', [\App\Http\Controllers\Admin\ContinuingEducationController::class, 'store'])->name('continuing-education.store');
    Route::post('/formations-continues/{training}/enroll', [\App\Http\Controllers\Admin\ContinuingEducationController::class, 'enrollTrainee'])->name('continuing-education.enroll');
    Route::get('/formations-continues/attestation/{registration}/pdf', [\App\Http\Controllers\Admin\ContinuingEducationController::class, 'downloadCertificatePdf'])->name('continuing-education.certificate.pdf');
    Route::delete('/formations-continues/{training}', [\App\Http\Controllers\Admin\ContinuingEducationController::class, 'destroy'])->name('continuing-education.destroy');

    // ÉVALUATIONS, NOTES & RESTITUTION (BULLETINS PDF)
    Route::get('/evaluations', [\App\Http\Controllers\Admin\EvaluationController::class, 'index'])->name('evaluations.index');
    Route::post('/evaluations', [\App\Http\Controllers\Admin\EvaluationController::class, 'store'])->name('evaluations.store');
    Route::post('/evaluations/{evaluation}/grades', [\App\Http\Controllers\Admin\EvaluationController::class, 'storeGrades'])->name('evaluations.grades.store');
    Route::patch('/evaluations/{evaluation}/toggle-publish', [\App\Http\Controllers\Admin\EvaluationController::class, 'togglePublish'])->name('evaluations.toggle-publish');
    Route::delete('/evaluations/{evaluation}', [\App\Http\Controllers\Admin\EvaluationController::class, 'destroy'])->name('evaluations.destroy');
    Route::get('/evaluations/report-card/{registration}/pdf', [\App\Http\Controllers\Admin\EvaluationController::class, 'exportReportCardPdf'])->name('evaluations.report-card.pdf');

    // MODULE 4 : CANDIDATURES, PROFILS & INSTRUCTIONS
    Route::get('/candidates', [\App\Http\Controllers\Admin\CandidateController::class, 'index'])->name('candidates');
    Route::get('/candidates/create', [\App\Http\Controllers\Admin\CandidateController::class, 'create'])->name('candidates.create');
    Route::get('/candidates/import-template', [\App\Http\Controllers\Admin\CandidateController::class, 'downloadImportTemplate'])->name('candidates.import-template');
    Route::post('/candidates/import', [\App\Http\Controllers\Admin\CandidateController::class, 'importExcel'])->name('candidates.import');
    Route::get('/candidates/{registration}', [\App\Http\Controllers\Admin\CandidateController::class, 'show'])->name('candidates.show');
    Route::get('/candidates/{registration}/edit', [\App\Http\Controllers\Admin\CandidateController::class, 'edit'])->name('candidates.edit');
    Route::patch('/candidates/{registration}/status', [\App\Http\Controllers\Admin\CandidateController::class, 'updateStatus'])->name('candidates.update-status');
    Route::put('/candidates/{registration}', [\App\Http\Controllers\Admin\CandidateController::class, 'update'])->name('candidates.update');
    Route::post('/candidates', [\App\Http\Controllers\Admin\CandidateController::class, 'store'])->name('candidates.store');
    Route::delete('/candidates/{registration}', [\App\Http\Controllers\Admin\CandidateController::class, 'destroy'])->name('candidates.destroy');

    // MODULE 5 & 7 : FINANCES, FACTURATION, VACATIONS & DÉPENSES
    Route::get('/finances', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finances');
    Route::post('/finances/expenses', [\App\Http\Controllers\Admin\FinanceController::class, 'storeExpense'])->name('finances.expenses.store');
    Route::post('/finances/categories', [\App\Http\Controllers\Admin\FinanceController::class, 'storeCategory'])->name('finances.categories.store');
    Route::delete('/finances/categories/{category}', [\App\Http\Controllers\Admin\FinanceController::class, 'destroyCategory'])->name('finances.categories.destroy');
    Route::post('/finances/trainers', [\App\Http\Controllers\Admin\FinanceController::class, 'storeTrainer'])->name('finances.trainers.store');
    Route::get('/finances/export', [\App\Http\Controllers\Admin\FinanceController::class, 'export'])->name('finances.export');

    // MODULE 6 : SUIVI PÉDAGOGIQUE, ÉMARGEMENT & GED
    Route::get('/attendances', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendances.index');
    Route::resource('resources', \App\Http\Controllers\Admin\ResourceController::class);
    Route::post('resources/export', [\App\Http\Controllers\Admin\ResourceController::class, 'export'])->name('resources.export');
    Route::patch('resources/{resource}/archive', [\App\Http\Controllers\Admin\ResourceController::class, 'archive'])->name('resources.archive');
    Route::get('/archives', [\App\Http\Controllers\Admin\ArchiveController::class, 'index'])->name('archives');

    // MODULE 10 : GÉNÉRATEUR AUTOMATISÉ DE CARTES, BADGES & ATTESTATIONS (PDF + EXCEL)
    Route::get('/badges-generator', [\App\Http\Controllers\Admin\BadgeGeneratorController::class, 'index'])->name('badges.generator');
    Route::get('/badges-generator/seminar/{seminar}', [\App\Http\Controllers\Admin\BadgeGeneratorController::class, 'seminarParticipants'])->name('badges.seminar-participants');
    Route::post('/badges-generator/presets', [\App\Http\Controllers\Admin\BadgeGeneratorController::class, 'savePreset'])->name('badges.save-preset');
    Route::delete('/badges-generator/presets/{id}', [\App\Http\Controllers\Admin\BadgeGeneratorController::class, 'deletePreset'])->name('badges.delete-preset');

    // MODULE 8 : FORMULAIRES DYNAMIQUES & ENQUÊTES
    Route::get('/forms', [\App\Http\Controllers\Admin\FormController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [\App\Http\Controllers\Admin\FormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [\App\Http\Controllers\Admin\FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{form}/edit', [\App\Http\Controllers\Admin\FormController::class, 'edit'])->name('forms.edit');
    Route::put('/forms/{form}', [\App\Http\Controllers\Admin\FormController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{form}', [\App\Http\Controllers\Admin\FormController::class, 'destroy'])->name('forms.destroy');
    Route::get('/forms/{form}/responses', [\App\Http\Controllers\Admin\FormController::class, 'responses'])->name('forms.responses');
    Route::get('/forms/{form}/export', [\App\Http\Controllers\Admin\FormController::class, 'exportCsv'])->name('forms.export');
    Route::patch('/forms/{form}/toggle', [\App\Http\Controllers\Admin\FormController::class, 'toggleStatus'])->name('forms.toggle');
    Route::patch('/form-submissions/{submission}/status', [\App\Http\Controllers\Admin\FormController::class, 'updateSubmissionStatus'])->name('forms.submissions.status');

    // MODULE 9 : COMMUNICATION & NOTIFICATIONS
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [\App\Http\Controllers\Admin\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('messages.destroy');

    // MODULE 1 : GESTION DES UTILISATEURS DU SYSTÈME (STRICT-ADMIN)
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->middleware('strict-admin');
    Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset_password')->middleware('strict-admin');

    // Module Technique : Debugging local
    if (app()->environment('local')) {
        Route::get('/debug/emails', [\App\Http\Controllers\Admin\DebugController::class, 'emails'])->name('debug.emails')->middleware('strict-admin');
    }
});


/*
|--------------------------------------------------------------------------
| PORTAILS DÉDIÉS AUX ACTEURS (MODULE 4 & MODULE 5)
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->name('portal.')->group(function () {

    Route::get('/', function () {
        return redirect()->route('login');
    })->name('welcome');

    // MODULE 4 : ESPACE PERSONNEL ÉTUDIANT
    Route::middleware('portal.auth:candidate')->prefix('candidat')->name('candidate.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Portal\CandidateController::class, 'dashboard'])->name('dashboard');
        Route::get('/report-card/pdf', [\App\Http\Controllers\Portal\CandidateController::class, 'downloadReportCard'])->name('report-card.pdf');
        Route::get('/continuing-certificate/pdf', [\App\Http\Controllers\Portal\CandidateController::class, 'downloadContinuingCertificate'])->name('continuing-certificate.pdf');
        Route::get('/profile/edit', [\App\Http\Controllers\Portal\CandidateController::class, 'editProfile'])->name('profile.edit');
        Route::post('/messages/{message}/read', [\App\Http\Controllers\Portal\CandidateController::class, 'markMessageAsRead'])->name('messages.read');
        Route::post('/profile/update-request', [\App\Http\Controllers\Portal\CandidateController::class, 'requestProfileUpdate'])->name('profile.update-request');
        Route::post('/seminars/change-request', [\App\Http\Controllers\Portal\CandidateController::class, 'requestSeminarChange'])->name('seminars.change-request');
    });

    // MODULE 5 & 6 : ESPACE INTERVENANT / FORMATEUR
    Route::middleware('portal.auth:trainer')->prefix('formateur')->name('trainer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Portal\TrainerController::class, 'dashboard'])->name('dashboard');
        Route::post('/messages', [\App\Http\Controllers\Portal\TrainerController::class, 'sendMessage'])->name('messages.send');
        Route::get('/attendances', [\App\Http\Controllers\Portal\TrainerController::class, 'attendances'])->name('attendances');
        Route::post('/attendances', [\App\Http\Controllers\Portal\TrainerController::class, 'storeAttendances'])->name('attendances.store');
        Route::get('/evaluations', [\App\Http\Controllers\Portal\TrainerController::class, 'evaluations'])->name('evaluations');
        Route::post('/evaluations', [\App\Http\Controllers\Portal\TrainerController::class, 'storeEvaluation'])->name('evaluations.store');
        Route::post('/evaluations/{evaluation}/grades', [\App\Http\Controllers\Portal\TrainerController::class, 'storeGrades'])->name('evaluations.grades.store');

        // GED Pédagogique Formateur
        Route::post('/resources', [\App\Http\Controllers\Portal\TrainerController::class, 'storeResource'])->name('resources.store');
        Route::delete('/resources/{resource}', [\App\Http\Controllers\Portal\TrainerController::class, 'destroyResource'])->name('resources.destroy');

        // Export Stagiaires par Thématique
        Route::get('/topics/{topic}/export', [\App\Http\Controllers\Portal\TrainerController::class, 'exportTopicCandidates'])->name('topics.export');
    });
});
