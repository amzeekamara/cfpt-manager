<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Portail de candidature public
Route::get('/inscriptions/{edition}', [\App\Http\Controllers\Public\RegistrationController::class, 'show'])->name('public.registration.show');
Route::post('/inscriptions/{edition}', [\App\Http\Controllers\Public\RegistrationController::class, 'store'])->name('public.registration.store');

// Formulaires partageables (sans authentification)
Route::get('/f/{token}', [\App\Http\Controllers\Public\FormController::class, 'show'])->name('public.form.show');
Route::post('/f/{token}', [\App\Http\Controllers\Public\FormController::class, 'store'])->name('public.form.store');

// Authentication Routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Password Setup for new users
Route::get('/setup-password/{token}', [\App\Http\Controllers\Auth\PasswordSetupController::class, 'show'])->name('password.reset');
Route::post('/setup-password', [\App\Http\Controllers\Auth\PasswordSetupController::class, 'store'])->name('password.setup.store');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/editions', [\App\Http\Controllers\Admin\EditionController::class, 'index'])->name('editions.index');
    Route::get('/editions/create', [\App\Http\Controllers\Admin\EditionController::class, 'create'])->name('editions.create');
    Route::post('/editions', [\App\Http\Controllers\Admin\EditionController::class, 'store'])->name('editions.store');
    Route::get('/editions/{edition}/edit', [\App\Http\Controllers\Admin\EditionController::class, 'edit'])->name('editions.edit');
    Route::put('/editions/{edition}', [\App\Http\Controllers\Admin\EditionController::class, 'update'])->name('editions.update');
    Route::delete('/editions/{edition}', [\App\Http\Controllers\Admin\EditionController::class, 'destroy'])->name('editions.destroy');
    Route::patch('/editions/{edition}/toggle', [\App\Http\Controllers\Admin\EditionController::class, 'toggleStatus'])->name('editions.toggle');
    Route::get('/candidates', [\App\Http\Controllers\Admin\CandidateController::class, 'index'])->name('candidates');
    Route::get('/candidates/create', [\App\Http\Controllers\Admin\CandidateController::class, 'create'])->name('candidates.create');
    Route::get('/candidates/{enrollment}', [\App\Http\Controllers\Admin\CandidateController::class, 'show'])->name('candidates.show');
    Route::get('/candidates/{enrollment}/edit', [\App\Http\Controllers\Admin\CandidateController::class, 'edit'])->name('candidates.edit');
    Route::patch('/candidates/{enrollment}/status', [\App\Http\Controllers\Admin\CandidateController::class, 'updateStatus'])->name('candidates.update-status');
    Route::put('/candidates/{enrollment}', [\App\Http\Controllers\Admin\CandidateController::class, 'update'])->name('candidates.update');
    Route::post('/candidates', [\App\Http\Controllers\Admin\CandidateController::class, 'store'])->name('candidates.store');
    Route::delete('/candidates/{enrollment}', [\App\Http\Controllers\Admin\CandidateController::class, 'destroy'])->name('candidates.destroy');

    Route::get('/finances', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finances');
    Route::post('/finances/expenses', [\App\Http\Controllers\Admin\FinanceController::class, 'storeExpense'])->name('finances.expenses.store');
    Route::post('/finances/intervenants', [\App\Http\Controllers\Admin\FinanceController::class, 'storeIntervenant'])->name('finances.intervenants.store');
    Route::get('/finances/export', [\App\Http\Controllers\Admin\FinanceController::class, 'export'])->name('finances.export');

    Route::get('/seminars', [\App\Http\Controllers\Admin\SeminarController::class, 'index'])->name('seminars');
    Route::get('/seminars/create', [\App\Http\Controllers\Admin\SeminarController::class, 'create'])->name('seminars.create');
    Route::post('/seminars', [\App\Http\Controllers\Admin\SeminarController::class, 'store'])->name('seminars.store');
    Route::get('/seminars/{seminar}/edit', [\App\Http\Controllers\Admin\SeminarController::class, 'edit'])->name('seminars.edit');
    Route::put('/seminars/{seminar}', [\App\Http\Controllers\Admin\SeminarController::class, 'update'])->name('seminars.update');
    Route::delete('/seminars/{seminar}', [\App\Http\Controllers\Admin\SeminarController::class, 'destroy'])->name('seminars.destroy');
    
    Route::resource('themes', \App\Http\Controllers\Admin\ThemeController::class);
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
    
    Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class);
    Route::post('documents/export', [\App\Http\Controllers\Admin\DocumentController::class, 'export'])->name('documents.export');
    Route::patch('documents/{document}/archive', [\App\Http\Controllers\Admin\DocumentController::class, 'archive'])->name('documents.archive');
    
    Route::resource('defenses', \App\Http\Controllers\Admin\DefenseController::class);
    
    Route::get('/archives', [\App\Http\Controllers\Admin\ArchiveController::class, 'index'])->name('archives');
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->middleware('strict-admin');

    // Formulaires dynamiques partageables
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

    // Debugging (Local Only ideally, but grouped here for simplicity)
    Route::get('/debug/emails', [\App\Http\Controllers\Admin\DebugController::class, 'emails'])->name('debug.emails');
});

// Portails (Candidats et Formateurs)
Route::prefix('portal')->name('portal.')->group(function () {
    // Authentification Unifiée
    Route::get('/login', [\App\Http\Controllers\Portal\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Portal\AuthController::class, 'login']);
    
    // Fallback welcome redirecting to login
    Route::get('/', function () {
        return redirect()->route('portal.login');
    })->name('welcome');
    
    Route::post('/logout', [\App\Http\Controllers\Portal\AuthController::class, 'logout'])->name('logout');

    // Espace Candidat
    Route::middleware('portal.auth:candidate')->prefix('candidat')->name('candidate.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Portal\CandidateController::class, 'dashboard'])->name('dashboard');
    });

    // Espace Formateur
    Route::middleware('portal.auth:trainer')->prefix('formateur')->name('trainer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Portal\TrainerController::class, 'dashboard'])->name('dashboard');
        Route::post('/messages', [\App\Http\Controllers\Portal\TrainerController::class, 'sendMessage'])->name('messages.send');
    });
});
