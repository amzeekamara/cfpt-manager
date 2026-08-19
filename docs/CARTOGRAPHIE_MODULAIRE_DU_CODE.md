# 📦 CARTOGRAPHIE & ORGANISATION MODULAIRE DU PROJET PFPT MANAGER

> **Guide officiel de découpage modulaire du code source & de l'architecture**  
> **Système** : Plateforme PFPT Manager (CFPT Sénégal-Japon / JICA)  
> **Total** : 10 Modules Métier • 28 Modèles / Tables • Architecture MVC

---

## 🗺️ Matrice Globale de Répartition du Code par Module

Le tableau ci-dessous établit la correspondance stricte entre les **10 Modules Fonctionnels**, les **Modèles Eloquent (Tables)**, les **Contrôleurs**, les **Vues Blade**, les **Routes** et les **Migrations**.

```
╔════════════════════════════════════════════════════════════════════════════════════════════════════════════╗
║                                 ORGANISATION PAR MODULES FONCTIONNELS                                      ║
╠════╦══════════════════════════════════════╦════════════════════════════════╦══════════════════════════════╣
║ N° ║ MODULE MÉTIER                        ║ TABLES & MODÈLES ELOQUENT      ║ CONTRÔLEURS ASSOCIÉS         ║
╠════╬══════════════════════════════════════╬════════════════════════════════╬══════════════════════════════╣
║ 01 ║ Authentification & Gestion Accès     ║ users, roles, password_resets  ║ AuthController, UserCtrl...  ║
║ 02 ║ Éditions & Cohortes JICA             ║ cohorts                        ║ Admin\CohortController       ║
║ 03 ║ Structure Pédagogique                ║ departments, seminars, topics  ║ SeminarCtrl, TopicCtrl...    ║
║ 04 ║ Candidatures & Inscriptions          ║ registrations, candidate_prof..║ CandidateCtrl, Public\Reg..  ║
║ 05 ║ Corps Enseignant & Formateurs        ║ trainers, trainer_sessions...  ║ Portal\TrainerCtrl, FinCtrl  ║
║ 06 ║ Suivi Pédagogique & GED              ║ attendances, resources         ║ AttendanceCtrl, ResourceCtrl ║
║ 07 ║ Finances & Facturation               ║ expenses, expense_cats, invoices║ Admin\FinanceController      ║
║ 08 ║ Formulaires Dynamiques               ║ form_templates, submissions    ║ Admin\FormCtrl, Public\Form  ║
║ 09 ║ Communication & Alertes              ║ messages, message_user, notif..║ MessageCtrl, NotificationCtrl║
║ 10 ║ Audit & Traçabilité                  ║ activity_logs                  ║ Middleware ActivityLogger... ║
╚════╩══════════════════════════════════════╩════════════════════════════════╩══════════════════════════════╝
```

---

## 🔍 Détail Exhaustif des 10 Modules

### 🔹 Module 1 : Authentification, Sécurité & Gestion des Rôles (RBAC)
* **Objectif :** Gestion des comptes utilisateurs, hachage Bcrypt, sessions unifiées, Google OAuth et contrôle d'accès strict.
* **Modèles & Tables :**
  * `App\Models\User` (`users`)
  * `App\Models\Role` (`roles`)
  * `App\Models\PasswordResetToken` (`password_reset_tokens`)
* **Contrôleurs :**
  * `App\Http\Controllers\AuthController.php` (Connexion, Déconnexion, Forgot Password)
  * `App\Http\Controllers\PasswordController.php` (Changement forcé de mot de passe)
  * `App\Http\Controllers\Auth\OAuthController.php` (Intégration Google OAuth2)
  * `App\Http\Controllers\Auth\PasswordSetupController.php` (Initialisation premier mot de passe)
  * `App\Http\Controllers\Admin\UserController.php` (Gestion CRUD des utilisateurs et rôles)
* **Middlewares :**
  * `App\Http\Middleware\Authenticate.php`
  * `App\Http\Middleware\RoleMiddleware.php`
  * `App\Http\Middleware\CheckPasswordChanged.php`
* **Vues Blade :**
  * `resources/views/auth/login.blade.php`
  * `resources/views/auth/forgot-password.blade.php`
  * `resources/views/auth/reset-password.blade.php`
  * `resources/views/auth/password-change.blade.php`
  * `resources/views/admin/users/` (`index.blade.php`, `create.blade.php`, `edit.blade.php`)

---

### 🔹 Module 2 : Éditions & Cohortes
* **Objectif :** Définition des cadres annuels du programme JICA, fixation de l'enveloppe budgétaire et planification temporelle.
* **Modèles & Tables :**
  * `App\Models\Cohort` (`cohorts`)
* **Contrôleurs :**
  * `App\Http\Controllers\Admin\CohortController.php`
* **Vues Blade :**
  * `resources/views/admin/cohorts/` (`index.blade.php`, `create.blade.php`, `edit.blade.php`)

---

### 🔹 Module 3 : Structure Pédagogique & Départements
* **Objectif :** Organisation des séminaires de formation continue, liaison avec les départements et découpage en thématiques.
* **Modèles & Tables :**
  * `App\Models\Department` (`departments`)
  * `App\Models\Seminar` (`seminars`)
  * `App\Models\Topic` (`topics`)
  * `App\Models\DepartmentSeminar` (`department_seminar`)
* **Contrôleurs :**
  * `App\Http\Controllers\Admin\SeminarController.php`
  * `App\Http\Controllers\Admin\DepartmentController.php`
  * `App\Http\Controllers\Admin\TopicController.php`
* **Vues Blade :**
  * `resources/views/admin/seminars/` (`index.blade.php`, `create.blade.php`, `edit.blade.php`)
  * `resources/views/admin/departments/` (`index.blade.php`)
  * `resources/views/admin/topics/` (`index.blade.php`)

---

### 🔹 Module 4 : Candidatures, Profils & Inscriptions
* **Objectif :** Soumission des candidatures, formulaire d'admission normalisé JICA, instruction des dossiers, demandes de transfert et d'actualisation de profil.
* **Modèles & Tables :**
  * `App\Models\Registration` (`registrations`)
  * `App\Models\CandidateProfile` (`candidate_profiles`)
  * `App\Models\ApplicationDocument` (`application_documents`)
  * `App\Models\Education` (`educations`)
  * `App\Models\SeminarChangeRequest` (`seminar_change_requests`)
  * `App\Models\ProfileUpdateRequest` (`profile_update_requests`)
* **Contrôleurs :**
  * `App\Http\Controllers\Public\RegistrationController.php` (Portail public de candidature)
  * `App\Http\Controllers\Admin\CandidateController.php` (Instruction, Validation, Rejet)
  * `App\Http\Controllers\Portal\CandidateController.php` (Espace personnel stagiaire)
* **Vues Blade :**
  * `resources/views/public/registration.blade.php`
  * `resources/views/admin/candidates/` (`index.blade.php`, `show.blade.php`, `edit.blade.php`)
  * `resources/views/portal/candidate/` (`dashboard.blade.php`, `profile.blade.php`)

---

### 🔹 Module 5 : Corps Enseignant & Affectations
* **Objectif :** Gestion des fiches formateurs, affectation aux thèmes de séminaires, suivi des volumes horaires et contractualisation des honoraires.
* **Modèles & Tables :**
  * `App\Models\Trainer` (`trainers`)
  * `App\Models\TrainerSession` (`trainer_sessions`)
  * `App\Models\SeminarTrainer` (`seminar_trainer`)
* **Contrôleurs :**
  * `App\Http\Controllers\Admin\FinanceController.php` (Gestion des honoraires et vacataires)
  * `App\Http\Controllers\Portal\TrainerController.php` (Tableau de bord formateur)
* **Vues Blade :**
  * `resources/views/portal/trainer/` (`dashboard.blade.php`, `attendances.blade.php`, `resources.blade.php`)

---

### 🔹 Module 6 : Suivi Pédagogique, Assiduité & GED
* **Objectif :** Feuille d'émargement journalière par thème/formateur, justification des absences et partage documentaire sécurisé.
* **Modèles & Tables :**
  * `App\Models\Attendance` (`attendances`)
  * `App\Models\Resource` (`resources`)
* **Contrôleurs :**
  * `App\Http\Controllers\Admin\AttendanceController.php`
  * `App\Http\Controllers\Admin\ResourceController.php`
  * `App\Http\Controllers\Admin\ArchiveController.php`
* **Vues Blade :**
  * `resources/views/admin/attendances/` (`index.blade.php`)
  * `resources/views/admin/resources/` (`index.blade.php`)
  * `resources/views/admin/archives/` (`index.blade.php`)

---

### 🔹 Module 7 : Finances, Facturation & Dépenses (Budget JICA)
* **Objectif :** Suivi budgétaire, comptabilisation des dépenses (règle exclusive Cohorte vs Séminaire), justificatifs et factures d'inscription.
* **Modèles & Tables :**
  * `App\Models\Expense` (`expenses`)
  * `App\Models\ExpenseCategory` (`expense_categories`)
  * `App\Models\Invoice` (`invoices`)
* **Contrôleurs :**
  * `App\Http\Controllers\Admin\FinanceController.php`
* **Vues Blade :**
  * `resources/views/admin/finances/` (`index.blade.php`)

---

### 🔹 Module 8 : Formulaires Dynamiques & Enquêtes
* **Objectif :** Création d'enquêtes d'évaluation à chaud/froid, formulaires de satisfaction partageables sans compte, collecte et export des réponses.
* **Modèles & Tables :**
  * `App\Models\FormTemplate` (`form_templates`)
  * `App\Models\FormSubmission` (`form_submissions`)
* **Contrôleurs :**
  * `App\Http\Controllers\Admin\FormController.php`
  * `App\Http\Controllers\Public\FormController.php`
* **Vues Blade :**
  * `resources/views/admin/forms/` (`index.blade.php`, `create.blade.php`, `edit.blade.php`, `responses.blade.php`)
  * `resources/views/public/form.blade.php`

---

### 🔹 Module 9 : Communication, Messagerie & Alertes
* **Objectif :** Diffusion de messages ciblés par rôle / séminaire / cohorte, accusés de réception et notifications in-app temps réel.
* **Modèles & Tables :**
  * `App\Models\Message` (`messages`)
  * `App\Models\MessageUser` (`message_user`)
  * `App\Models\Notification` (`notifications`)
* **Contrôleurs :**
  * `App\Http\Controllers\Admin\MessageController.php`
  * `App\Http\Controllers\Admin\NotificationController.php`
* **Vues Blade :**
  * `resources/views/admin/messages/` (`index.blade.php`, `create.blade.php`)
  * `resources/views/admin/notifications/` (`index.blade.php`)

---

### 🔹 Module 10 : Audit, Sécurité & Traçabilité
* **Objectif :** Journalisation inaltérable de tous les événements sensibles (admissions, décaissements, modifications) pour auditabilité JICA / CFPT.
* **Modèles & Tables :**
  * `App\Models\ActivityLog` (`activity_logs`)
* **Services & Observateurs :**
  * Traçabilité automatique via les contrôleurs d'administration et middleware de logging.
