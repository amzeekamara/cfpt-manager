# 📚 DICTIONNAIRE DES DONNÉES - SYSTÈME DE GESTION PFPT • CFPT

> **Projet** : Plateforme de Formation Professionnelle & Technique (PFPT • CFPT / JICA)
> **Généré le** : 16/08/2026 10:29:40
> **Organisation** : Structuré par **Modules Fonctionnels Métier**
> **Nomenclature** : Entités et éléments entièrement traduits en français avec correspondances techniques.

---

## 📌 SOMMAIRE DES MODULES & TABLES

### 🗂️ MODULE 1 : AUTHENTIFICATION, COMPTES & GESTION DES ACCÈS
*Gestion sécurisée des identités, des rôles applicatifs et de la récupération de compte.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 1 | [UTILISATEURS](#table-users) | `users` | Comptes utilisateurs de la plateforme (Administrateurs, Coordinateurs, Formateurs, Candidats, Agents JICA). | 15 |
| 2 | [ROLES_ET_PERMISSIONS](#table-roles) | `roles` | Niveaux d'accès et types de profils dans le système (Admin, Formateur, Candidat, etc.). | 5 |
| 3 | [JETONS_REINITIALISATION_MOT_DE_PASSE](#table-password-reset-tokens) | `password_reset_tokens` | Jetons sécurisés pour la procédure de réinitialisation de mot de passe par courriel. | 3 |

### 🗂️ MODULE 2 : GESTION DES ÉDITIONS & SESSIONS DU PROGRAMME
*Administration des promotions annuelles, cycles de formation et budgets globaux alloués.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 4 | [EDITIONS_ET_COHORTES](#table-cohorts) | `cohorts` | Cycles et promotions annuelles du programme de formation continue PFPT - CFPT / JICA. | 8 |

### 🗂️ MODULE 3 : STRUCTURE PÉDAGOGIQUE, SÉMINAIRES & THÈMES
*Organisation départementale, catalogues de séminaires et modules thématiques de spécialisation.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 5 | [DEPARTEMENTS_PEDAGOGIQUES](#table-departments) | `departments` | Départements et filières techniques du centre de formation (Électrique, Mécanique, Informatique, etc.). | 5 |
| 6 | [SEMINAIRES_DE_FORMATION](#table-seminars) | `seminars` | Séminaires de renforcement de capacités organisés au sein d'une édition. | 11 |
| 7 | [THEMATIQUES_ET_MODULES](#table-topics) | `topics` | Modules thématiques et sous-domaines de spécialisation dispensés au sein d'un séminaire. | 6 |
| 8 | [LIAISONS_DEPARTEMENTS_SEMINAIRES](#table-department-seminar) | `department_seminar` | Table de liaison Many-to-Many entre les départements pédagogiques et les séminaires. | 5 |

### 🗂️ MODULE 4 : CANDIDATURES, DOSSIERS D'ADMISSION & GESTION DES PARTICIPANTS
*Traitement des demandes d'admission, profils normalisés JICA, documents justificatifs et suivi de statut.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 9 | [INSCRIPTIONS_ET_CANDIDATURES](#table-registrations) | `registrations` | Inscriptions et candidatures des participants aux séminaires avec circuit de décision et validation. | 7 |
| 10 | [PROFILS_CANDIDATS_JICA](#table-candidate-profiles) | `candidate_profiles` | Informations complètes du formulaire de candidature normalisé JICA (état civil, contact d'urgence, employeur, santé, logistique). | 34 |
| 11 | [DOCUMENTS_ET_PIECES_JUSTIFICATIVES](#table-application-documents) | `application_documents` | Pièces justificatives téléversées lors de la candidature (CV, diplômes, lettres de recommandation, passeport). | 7 |
| 12 | [PARCOURS_ACADEMIQUE_ET_DIPLOMES](#table-educations) | `educations` | Cursus universitaire, diplômes et formations antérieures suivies par les candidats. | 11 |
| 13 | [DEMANDES_CHANGEMENT_SEMINAIRE](#table-seminar-change-requests) | `seminar_change_requests` | Demandes de réaffectation de séminaire ou de session soumises par les candidats. | 9 |
| 14 | [DEMANDES_MODIFICATION_PROFIL](#table-profile-update-requests) | `profile_update_requests` | Demandes de modification de données personnelles soumises par les candidats ou formateurs. | 7 |

### 🗂️ MODULE 5 : CORPS ENSEIGNANT & GESTION DES FORMATEURS
*Gestion du corps professoral, vacations, volumes horaires et rémunérations.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 15 | [PROFILS_FORMATEURS](#table-trainers) | `trainers` | Fiches profils et coordonnées complémentaires des formateurs et experts enseignants. | 11 |
| 16 | [SESSIONS_ET_HEURES_FORMATEURS](#table-trainer-sessions) | `trainer_sessions` | Enregistrement des cours et séances d'animation assurés par les formateurs. | 8 |
| 17 | [AFFECTATIONS_FORMATEURS_SEMINAIRES](#table-seminar-trainer) | `seminar_trainer` | Table de liaison Many-to-Many entre les séminaires et les formateurs avec suivi des thèmes, heures et rémunérations. | 10 |

### 🗂️ MODULE 6 : SUIVI PÉDAGOGIQUE, ÉMARGEMENTS & RESSOURCES (GED)
*Suivi de l'assiduité des apprenants et gestion électronique des supports pédagogiques.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 18 | [PRESENCES_ET_EMARGEMENTS](#table-attendances) | `attendances` | Feuilles d'émargement et suivi journalier des présences/absences par participant et par séance. | 8 |
| 19 | [RESSOURCES_ET_SUPPORTS_PEDAGOGIQUES](#table-resources) | `resources` | Gestion Électronique des Documents (GED), supports de cours, polycopiés et guides téléversés. | 8 |

### 🗂️ MODULE 7 : GESTION FINANCIÈRE, BUDGETS & FACTURATION
*Suivi analytique des dépenses, rubriques budgétaires et émission des ordres de facturation.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 20 | [DEPENSES_ET_DECAISSEMENTS](#table-expenses) | `expenses` | Dépenses budgétaires, décaissements et achats opérationnels engagés pour les cohortes et séminaires. | 10 |
| 21 | [CATEGORIES_DE_DEPENSES](#table-expense-categories) | `expense_categories` | Plan comptable et rubriques budgétaires (ex: Hébergement, Restauration, Honoraires, Matériel). | 4 |
| 22 | [FACTURES_ET_PAIEMENTS](#table-invoices) | `invoices` | Factures, reçus et titres de paiement générés pour les inscriptions ou prestations. | 8 |

### 🗂️ MODULE 8 : FORMULAIRES DYNAMIQUES & ENQUÊTES
*Création de formulaires modulables, collectes de retours et évaluations de satisfaction.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 23 | [MODELES_DE_FORMULAIRES](#table-form-templates) | `form_templates` | Générateur de formulaires personnalisés créés par les administrateurs et partageables via un lien public. | 11 |
| 24 | [SOUMISSIONS_DE_FORMULAIRES](#table-form-submissions) | `form_submissions` | Réponses soumises par les candidats, formateurs ou visiteurs aux formulaires en ligne. | 8 |

### 🗂️ MODULE 9 : COMMUNICATION, DIFFUSION & NOTIFICATIONS
*Messagerie instantanée, diffusions ciblées multi-canaux et alertes système.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 25 | [MESSAGES_ET_DIFFUSIONS](#table-messages) | `messages` | Communications internes, annonces et messages diffusés aux formateurs, candidats et départements. | 15 |
| 26 | [MESSAGES_DESTINATAIRES_LECTURES](#table-message-user) | `message_user` | Table pivot pour le suivi de réception et d'accusé de lecture des messages par utilisateur. | 6 |
| 27 | [NOTIFICATIONS_SYSTEME](#table-notifications) | `notifications` | Notifications instantanées, alertes d'action et rappels système envoyés aux utilisateurs. | 8 |

### 🗂️ MODULE 10 : AUDIT, JOURNALISATION & HISTORIQUE
*Traçabilité des opérations, historique des accès et conformité de sécurité.*

| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |
|---|---|---|---|:---:|
| 28 | [JOURNAL_DES_ACTIVITES_AUDIT](#table-activity-logs) | `activity_logs` | Journalisation et audit de toutes les actions et modifications effectuées dans le système. | 9 |

---

## 🗄️ SPÉCIFICATION DÉTAILLÉE DES TABLES PAR MODULE

## 📦 MODULE 1 : AUTHENTIFICATION, COMPTES & GESTION DES ACCÈS

### <a id="table-users"></a> 📄 UTILISATEURS (`users`)

**Description Métier** : Comptes utilisateurs de la plateforme (Administrateurs, Coordinateurs, Formateurs, Candidats, Agents JICA).

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté de l'utilisateur. |
| **nom_complet** | `name` | `VARCHAR` | Non | - | - | Nom et prénom(s) complets du compte. |
| **adresse_courriel** | `email` | `VARCHAR` | Non | - | `UNIQUE` | Adresse email officielle / identifiant unique de connexion. |
| **date_verification_courriel** | `email_verified_at` | `DATETIME` | Oui | - | - | Date et heure de confirmation de l'adresse courriel. |
| **mot_de_passe_chiffre** | `password` | `VARCHAR` | Non | - | - | Empreinte sécurisée du mot de passe (hachage Bcrypt). |
| **jeton_memorisation** | `remember_token` | `VARCHAR` | Oui | - | - | Jeton de persistance de session (Se souvenir de moi). |
| **identifiant_role** | `role_id` | `INTEGER` | Oui | - | `FK->roles(id)` | Rôle attribué à l'utilisateur définissant ses privilèges. |
| **identifiant_google** | `google_id` | `VARCHAR` | Oui | - | - | Identifiant unique OAuth Google pour l'authentification tierce. |
| **chemin_avatar** | `avatar` | `VARCHAR` | Oui | - | - | Chemin d'accès au fichier image de la photo de profil. |
| **changement_mdp_requis** | `must_change_password` | `TINYINT(1)` | Non | 1 | - | Indicateur imposant le renouvellement du mot de passe temporaire à la première connexion. |
| **specialite** | `specialty` | `VARCHAR` | Oui | - | - | Domaine d'expertise ou spécialité technique. |
| **poste_fonction** | `position` | `VARCHAR` | Oui | - | - | Fonction administrative ou académique occupée. |
| **superviseur** | `supervisor` | `VARCHAR` | Oui | - | - | Nom du responsable ou superviseur direct. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-roles"></a> 📄 ROLES_ET_PERMISSIONS (`roles`)

**Description Métier** : Niveaux d'accès et types de profils dans le système (Admin, Formateur, Candidat, etc.).

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du rôle. |
| **libelle_role** | `name` | `VARCHAR` | Non | - | - | Code / Nom du rôle (ex: admin, trainer, candidate, jica_agent). |
| **description** | `description` | `TEXT` | Oui | - | - | Description détaillée des prérogatives associées au rôle. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-password-reset-tokens"></a> 📄 JETONS_REINITIALISATION_MOT_DE_PASSE (`password_reset_tokens`)

**Description Métier** : Jetons sécurisés pour la procédure de réinitialisation de mot de passe par courriel.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **adresse_courriel** | `email` | `VARCHAR` | Non | - | **PK** | Adresse email du compte demandeur de réinitialisation. |
| **jeton_securite** | `token` | `VARCHAR` | Non | - | - | Jeton cryptographique à usage unique. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de génération du jeton de réinitialisation. |

---

## 📦 MODULE 2 : GESTION DES ÉDITIONS & SESSIONS DU PROGRAMME

### <a id="table-cohorts"></a> 📄 EDITIONS_ET_COHORTES (`cohorts`)

**Description Métier** : Cycles et promotions annuelles du programme de formation continue PFPT - CFPT / JICA.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté de l'édition / cohorte. |
| **intitule_edition** | `title` | `VARCHAR` | Non | - | - | Intitulé officiel de la session (ex: Édition 2026 – 2027). |
| **budget_total_alloue** | `total_budget` | `NUMERIC` | Non | 0 | - | Budget total d'exploitation alloué à l'édition en FCFA. |
| **date_debut** | `start_date` | `DATETIME` | Non | - | - | Date officielle de démarrage de la session de formation. |
| **date_fin** | `end_date` | `DATETIME` | Non | - | - | Date de clôture et fin des séminaires de la cohorte. |
| **statut_edition** | `status` | `VARCHAR` | Non | 'draft' | - | Statut opérationnel de l'édition (draft, active, completed, archived). |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

## 📦 MODULE 3 : STRUCTURE PÉDAGOGIQUE, SÉMINAIRES & THÈMES

### <a id="table-departments"></a> 📄 DEPARTEMENTS_PEDAGOGIQUES (`departments`)

**Description Métier** : Départements et filières techniques du centre de formation (Électrique, Mécanique, Informatique, etc.).

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du département. |
| **nom_departement** | `name` | `VARCHAR` | Non | - | - | Nom ou libellé complet du département technique. |
| **est_actif** | `is_active` | `TINYINT(1)` | Non | 1 | - | État d'activation du département (1 = Actif, 0 = Inactif). |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-seminars"></a> 📄 SEMINAIRES_DE_FORMATION (`seminars`)

**Description Métier** : Séminaires de renforcement de capacités organisés au sein d'une édition.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du séminaire. |
| **titre_seminaire** | `title` | `VARCHAR` | Non | - | - | Intitulé complet du séminaire de formation. |
| **description_objectifs** | `description` | `TEXT` | Oui | - | - | Description du programme, prérequis et objectifs pédagogiques. |
| **date_debut** | `start_date` | `DATETIME` | Non | - | - | Date de commencement de la session de séminaire. |
| **date_fin** | `end_date` | `DATETIME` | Non | - | - | Date de fin et clôture du séminaire. |
| **capacite_max_places** | `max_participants` | `INTEGER` | Non | 30 | - | Nombre maximum de participants autorisés (capacité d'accueil). |
| **statut_seminaire** | `status` | `VARCHAR` | Non | 'draft' | - | État du séminaire (draft, active, completed, archived). |
| **image_couverture** | `image` | `VARCHAR` | Oui | - | - | Chemin vers l'image d'illustration ou bannière du séminaire. |
| **identifiant_edition** | `cohort_id` | `INTEGER` | Oui | - | `FK->cohorts(id)` | Référence vers l'édition / cohorte de rattachement. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-topics"></a> 📄 THEMATIQUES_ET_MODULES (`topics`)

**Description Métier** : Modules thématiques et sous-domaines de spécialisation dispensés au sein d'un séminaire.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté de la thématique. |
| **libelle_theme** | `name` | `VARCHAR` | Non | - | - | Intitulé complet du thème de formation technique. |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Oui | - | `FK->seminars(id)` | Référence vers le séminaire auquel est rattaché le thème. |
| **est_actif** | `is_active` | `TINYINT(1)` | Non | 1 | - | Indicateur d'activation du module (1 = Actif, 0 = Inactif). |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-department-seminar"></a> 📄 LIAISONS_DEPARTEMENTS_SEMINAIRES (`department_seminar`)

**Description Métier** : Table de liaison Many-to-Many entre les départements pédagogiques et les séminaires.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique de la liaison. |
| **identifiant_departement** | `department_id` | `INTEGER` | Non | - | `FK->departments(id)` | Référence vers le département pédagogique concerné. |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Non | - | `FK->seminars(id)` | Référence vers le séminaire de formation associé. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

## 📦 MODULE 4 : CANDIDATURES, DOSSIERS D'ADMISSION & GESTION DES PARTICIPANTS

### <a id="table-registrations"></a> 📄 INSCRIPTIONS_ET_CANDIDATURES (`registrations`)

**Description Métier** : Inscriptions et candidatures des participants aux séminaires avec circuit de décision et validation.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté de l'inscription. |
| **identifiant_utilisateur** | `user_id` | `INTEGER` | Non | - | `FK->users(id)` | Référence vers le compte du candidat postulant. |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Non | - | `FK->seminars(id)` | Référence vers le séminaire choisi. |
| **identifiant_theme** | `topic_id` | `INTEGER` | Oui | - | `FK->topics(id)` | Référence vers le thème spécifique sélectionné par le candidat. |
| **statut_decision** | `status` | `VARCHAR` | Non | 'pending' | - | État d'admission du dossier (pending=À Examiner, approved=Sélectionné, rejected=Refusé, waiting_list=Liste d'attente). |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de soumission de la candidature. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour de la décision. |

---

### <a id="table-candidate-profiles"></a> 📄 PROFILS_CANDIDATS_JICA (`candidate_profiles`)

**Description Métier** : Informations complètes du formulaire de candidature normalisé JICA (état civil, contact d'urgence, employeur, santé, logistique).

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du profil candidat. |
| **identifiant_utilisateur** | `user_id` | `INTEGER` | Non | - | `FK->users(id)` | Référence vers le compte utilisateur associé. |
| **prenoms** | `first_names` | `VARCHAR` | Oui | - | - | Prénom(s) du candidat selon les pièces d'identité. |
| **nom_famille** | `last_name` | `VARCHAR` | Oui | - | - | Nom de famille du candidat. |
| **date_naissance** | `birth_date` | `DATE` | Oui | - | - | Date de naissance du candidat. |
| **lieu_naissance** | `birth_place` | `VARCHAR` | Oui | - | - | Lieu ou ville de naissance. |
| **genre_sexe** | `gender` | `VARCHAR` | Oui | - | - | Genre / Sexe du candidat (M/F). |
| **nationalite** | `nationality` | `VARCHAR` | Oui | - | - | Nationalité / Pays d'origine. |
| **pays_residence** | `residence_country` | `VARCHAR` | Oui | - | - | Pays de résidence habituelle. |
| **photo_dossier** | `photo_path` | `VARCHAR` | Oui | - | - | Chemin vers la photo d'identité d'admission. |
| **situation_matrimoniale** | `family_status` | `VARCHAR` | Non | 'single' | - | Situation familiale (single=Célibataire, married=Marié, etc.). |
| **religion_confession** | `religion` | `VARCHAR` | Oui | - | - | Confession religieuse (pour les dispositions de restauration/séjour). |
| **adresse_postale** | `address` | `TEXT` | Oui | - | - | Adresse géographique complète de résidence. |
| **numero_telephone** | `phone` | `VARCHAR` | Oui | - | - | Numéro de téléphone portable direct. |
| **contact_urgence_nom** | `emergency_contact_name` | `VARCHAR` | Oui | - | - | Nom complet de la personne à prévenir en cas d'urgence. |
| **contact_urgence_telephone** | `emergency_contact_phone` | `VARCHAR` | Oui | - | - | Numéro téléphonique du contact d'urgence. |
| **contact_urgence_courriel** | `emergency_contact_email` | `VARCHAR` | Oui | - | - | Adresse courriel du contact d'urgence. |
| **contact_urgence_lien** | `emergency_contact_relation` | `VARCHAR` | Oui | - | - | Lien de parenté ou relation avec le contact d'urgence. |
| **diplome_plus_eleve** | `highest_degree` | `VARCHAR` | Oui | - | - | Plus haut diplôme académique ou technique obtenu. |
| **profession_actuelle** | `current_profession` | `VARCHAR` | Oui | - | - | Métier / Profession exercée actuellement. |
| **employeur_actuel** | `current_employer` | `VARCHAR` | Oui | - | - | Organisation, ministère ou entreprise employeur actuel. |
| **employeur_precedent** | `previous_employer` | `VARCHAR` | Oui | - | - | Précédent employeur. |
| **description_poste** | `job_description` | `TEXT` | Oui | - | - | Description détaillée des tâches professionnelles au quotidien. |
| **utilite_de_la_formation** | `training_utility` | `TEXT` | Oui | - | - | Motivation et impact attendu de la formation sur le poste. |
| **observations_medicales** | `health_record_obs` | `TEXT` | Oui | - | - | Remarques médicales, allergies ou antécédents particuliers. |
| **questionnaire_sante_json** | `health_questionnaire_json` | `TEXT` | Oui | - | - | Réponses détaillées au questionnaire médical JICA au format JSON. |
| **details_assurance** | `insurance_details` | `VARCHAR` | Oui | - | - | Détails et police de couverture d'assurance souscrite. |
| **prime_assurance** | `insurance_premium` | `NUMERIC` | Oui | - | - | Montant de la prime d'assurance en FCFA. |
| **preference_hebergement** | `accommodation_preference` | `VARCHAR` | Oui | - | - | Choix de formule d'hébergement au campus. |
| **matricule_agent** | `matricule` | `VARCHAR` | Oui | - | - | Numéro matricule officiel de la fonction publique ou entreprise. |
| **grade_echelon** | `grade` | `VARCHAR` | Oui | - | - | Grade ou échelon professionnel. |
| **fonction_occupee** | `job_function` | `VARCHAR` | Oui | - | - | Intitulé exact de la fonction occupée. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-application-documents"></a> 📄 DOCUMENTS_ET_PIECES_JUSTIFICATIVES (`application_documents`)

**Description Métier** : Pièces justificatives téléversées lors de la candidature (CV, diplômes, lettres de recommandation, passeport).

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du document. |
| **identifiant_utilisateur** | `user_id` | `INTEGER` | Non | - | `FK->users(id)` | Référence vers l'utilisateur propriétaire du document. |
| **type_piece_justificative** | `resource_type` | `VARCHAR` | Non | - | - | Type de document (cv, diploma, recommendation_letter, passport, id_card). |
| **chemin_fichier_stockage** | `file_path` | `VARCHAR` | Non | - | - | Chemin relatif sécurisé de stockage du fichier sur le serveur. |
| **nom_fichier_original** | `original_filename` | `VARCHAR` | Non | - | - | Nom d'origine du fichier lors du téléversement. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de téléversement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière modification. |

---

### <a id="table-educations"></a> 📄 PARCOURS_ACADEMIQUE_ET_DIPLOMES (`educations`)

**Description Métier** : Cursus universitaire, diplômes et formations antérieures suivies par les candidats.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique du parcours académique. |
| **identifiant_profil_candidat** | `candidate_profile_id` | `INTEGER` | Non | - | `FK->candidate_profiles(id)` | Référence vers le profil candidat rattaché. |
| **nom_etablissement** | `establishment_name` | `VARCHAR` | Non | - | - | Nom de l'université, école ou institut de formation. |
| **domaine_etudes** | `field_of_study` | `VARCHAR` | Non | - | - | Filière ou domaine d'études suivi. |
| **diplome_obtenu** | `degree_obtained` | `VARCHAR` | Non | - | - | Intitulé du diplôme ou certificat obtenu. |
| **mention_distinction** | `distinction` | `VARCHAR` | Oui | - | - | Mention académique obtenue (ex: Très bien, Assez bien). |
| **annee_obtention** | `graduation_year` | `INTEGER` | Non | - | - | Année de diplomation. |
| **niveau_etudes** | `level` | `VARCHAR` | Non | - | - | Niveau d'études (ex: BAC+2, Licence, Master, Ingénieur). |
| **chemin_justificatif** | `resource_path` | `VARCHAR` | Oui | - | - | Chemin vers la copie numérisée du diplôme. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-seminar-change-requests"></a> 📄 DEMANDES_CHANGEMENT_SEMINAIRE (`seminar_change_requests`)

**Description Métier** : Demandes de réaffectation de séminaire ou de session soumises par les candidats.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique de la demande de réorientation. |
| **identifiant_utilisateur** | `user_id` | `INTEGER` | Non | - | `FK->users(id)` | Référence vers l'utilisateur émetteur de la demande. |
| **identifiant_inscription** | `registration_id` | `INTEGER` | Non | - | `FK->registrations(id)` | Référence vers la candidature initiale concernée. |
| **identifiant_nouveau_seminaire** | `new_seminar_id` | `INTEGER` | Non | - | `FK->seminars(id)` | Référence vers le nouveau séminaire sollicité. |
| **motif_demande** | `reason` | `TEXT` | Oui | - | - | Explication et justification du motif de changement. |
| **statut_demande** | `status` | `VARCHAR` | Non | 'pending' | - | État de traitement (pending=En attente, approved=Accordé, rejected=Refusé). |
| **avis_administration** | `admin_feedback` | `TEXT` | Oui | - | - | Commentaires ou motifs de la décision administrative. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de formulation de la demande. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de traitement par l'administration. |

---

### <a id="table-profile-update-requests"></a> 📄 DEMANDES_MODIFICATION_PROFIL (`profile_update_requests`)

**Description Métier** : Demandes de modification de données personnelles soumises par les candidats ou formateurs.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique de la demande. |
| **identifiant_utilisateur** | `user_id` | `INTEGER` | Non | - | `FK->users(id)` | Référence vers l'utilisateur demandeur. |
| **modifications_sollicitees_json** | `requested_changes` | `TEXT` | Non | - | - | Détail des données modifiées au format JSON. |
| **statut_demande** | `status` | `VARCHAR` | Non | 'pending' | - | Statut de validation (pending, approved, rejected). |
| **avis_administration** | `admin_feedback` | `TEXT` | Oui | - | - | Commentaires de l'administrateur en charge. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de soumission. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de validation. |

---

## 📦 MODULE 5 : CORPS ENSEIGNANT & GESTION DES FORMATEURS

### <a id="table-trainers"></a> 📄 PROFILS_FORMATEURS (`trainers`)

**Description Métier** : Fiches profils et coordonnées complémentaires des formateurs et experts enseignants.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du formateur. |
| **nom_complet** | `name` | `VARCHAR` | Non | - | - | Nom et prénom(s) complets de l'enseignant / formateur. |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Non | - | `FK->seminars(id)` | Référence vers le séminaire principal d'affectation. |
| **montant_remuneration** | `remuneration` | `NUMERIC` | Non | 0 | - | Montant total des honoraires / vacations convenus en FCFA. |
| **statut_paiement** | `payment_status` | `VARCHAR` | Non | 'unpaid' | - | Statut de règlement financier (unpaid=Impayé, paid=Payé, partial=Partiel). |
| **adresse_courriel** | `email` | `VARCHAR` | Oui | - | - | Adresse email professionnelle. |
| **numero_telephone** | `phone` | `VARCHAR` | Oui | - | - | Numéro de téléphone professionnel. |
| **date_naissance** | `birth_date` | `DATE` | Oui | - | - | Date de naissance du formateur. |
| **lieu_naissance** | `birth_place` | `VARCHAR` | Oui | - | - | Lieu de naissance. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-trainer-sessions"></a> 📄 SESSIONS_ET_HEURES_FORMATEURS (`trainer_sessions`)

**Description Métier** : Enregistrement des cours et séances d'animation assurés par les formateurs.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique de la session d'animation. |
| **identifiant_formateur** | `trainer_id` | `INTEGER` | Non | - | `FK->trainers(id)` | Référence vers le formateur intervenant. |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Non | - | `FK->seminars(id)` | Référence vers le séminaire concerné. |
| **identifiant_theme** | `topic_id` | `INTEGER` | Oui | - | `FK->topics(id)` | Référence vers le thème dispensé. |
| **date_seance** | `session_date` | `DATE` | Non | - | - | Date de déroulement de la séance de cours. |
| **volume_heures_dispensees** | `hours_taught` | `INTEGER` | Non | 0 | - | Nombre d'heures effectives d'enseignement dispensées. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure d'enregistrement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-seminar-trainer"></a> 📄 AFFECTATIONS_FORMATEURS_SEMINAIRES (`seminar_trainer`)

**Description Métier** : Table de liaison Many-to-Many entre les séminaires et les formateurs avec suivi des thèmes, heures et rémunérations.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique de l'affectation. |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Non | - | `FK->seminars(id)` | Référence vers le séminaire de formation. |
| **identifiant_formateur** | `trainer_id` | `INTEGER` | Non | - | `FK->trainers(id)` | Référence vers le formateur assigné. |
| **montant_remuneration** | `remuneration` | `NUMERIC` | Non | 0 | - | Montant de vacation attribué pour cette mission. |
| **statut_paiement** | `payment_status` | `VARCHAR` | Non | 'unpaid' | - | État du paiement (unpaid, paid, partial). |
| **nombre_themes_assignes** | `themes_count` | `INTEGER` | Oui | - | - | Nombre total de thématiques confiées. |
| **quota_heures_prevu** | `hours_count` | `INTEGER` | Oui | - | - | Volume horaire contractuel assigné. |
| **themes_selectionnes_json** | `selected_topics` | `TEXT` | Oui | - | - | Liste des identifiants de thèmes sous format JSON. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création de l'affectation. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

## 📦 MODULE 6 : SUIVI PÉDAGOGIQUE, ÉMARGEMENTS & RESSOURCES (GED)

### <a id="table-attendances"></a> 📄 PRESENCES_ET_EMARGEMENTS (`attendances`)

**Description Métier** : Feuilles d'émargement et suivi journalier des présences/absences par participant et par séance.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté de l'émargement. |
| **identifiant_inscription** | `registration_id` | `INTEGER` | Non | - | `FK->registrations(id)` | Référence vers l'inscription du candidat présent/absent. |
| **identifiant_theme** | `topic_id` | `INTEGER` | Oui | - | `FK->topics(id)` | Référence vers le thème de cours concerné. |
| **identifiant_formateur** | `trainer_id` | `INTEGER` | Oui | - | `FK->trainers(id)` | Référence vers le formateur animateur. |
| **date_seance** | `session_date` | `DATE` | Non | - | - | Date de la séance d'enseignement. |
| **statut_presence** | `status` | `VARCHAR` | Non | - | - | État d'émargement (present=Présent, absent=Absent, justified=Absence justifiée, late=En retard). |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de saisie de l'émargement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière modification. |

---

### <a id="table-resources"></a> 📄 RESSOURCES_ET_SUPPORTS_PEDAGOGIQUES (`resources`)

**Description Métier** : Gestion Électronique des Documents (GED), supports de cours, polycopiés et guides téléversés.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique du document pédagogique. |
| **titre_document** | `title` | `VARCHAR` | Non | - | - | Titre officiel ou désignation du support pédagogique. |
| **chemin_fichier** | `file_path` | `VARCHAR` | Non | - | - | Chemin de stockage du fichier sur le serveur (PDF, ZIP, DOCX, etc.). |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Oui | - | `FK->seminars(id)` | Référence vers le séminaire auquel est adossée la ressource. |
| **visibilite_par_role** | `role_visibility` | `VARCHAR` | Non | 'all' | - | Visibilité et restriction d'accès (all=Tous, trainers=Formateurs uniquement, candidates=Candidats). |
| **est_archive** | `is_archived` | `TINYINT(1)` | Non | 0 | - | Indicateur d'archivage (1 = Archivé, 0 = Actif). |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de téléversement. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière modification. |

---

## 📦 MODULE 7 : GESTION FINANCIÈRE, BUDGETS & FACTURATION

### <a id="table-expenses"></a> 📄 DEPENSES_ET_DECAISSEMENTS (`expenses`)

**Description Métier** : Dépenses budgétaires, décaissements et achats opérationnels engagés pour les cohortes et séminaires.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté de la dépense. |
| **description_motif** | `description` | `VARCHAR` | Non | - | - | Description explicative et justification de la dépense. |
| **montant_depense** | `amount` | `NUMERIC` | Non | - | - | Montant financier décaissé en FCFA. |
| **identifiant_edition** | `cohort_id` | `INTEGER` | Oui | - | `FK->cohorts(id)` | Référence vers l'édition / cohorte budgétaire concernée. |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Oui | - | `FK->seminars(id)` | Référence vers le séminaire imputé (si applicable). |
| **identifiant_categorie** | `expense_category_id` | `INTEGER` | Oui | - | `FK->expense_categories(id)` | Référence vers la catégorie comptable de dépense. |
| **date_operation** | `expense_date` | `DATETIME` | Non | - | - | Date effective de paiement ou d'engagement de la dépense. |
| **chemin_justificatif_recu** | `receipt_path` | `VARCHAR` | Oui | - | - | Chemin vers la facture ou le reçu justificatif numérisé. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de saisie comptable. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

### <a id="table-expense-categories"></a> 📄 CATEGORIES_DE_DEPENSES (`expense_categories`)

**Description Métier** : Plan comptable et rubriques budgétaires (ex: Hébergement, Restauration, Honoraires, Matériel).

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique de la catégorie. |
| **nom_categorie** | `name` | `VARCHAR` | Non | - | - | Intitulé officiel de la catégorie budgétaire. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de modification. |

---

### <a id="table-invoices"></a> 📄 FACTURES_ET_PAIEMENTS (`invoices`)

**Description Métier** : Factures, reçus et titres de paiement générés pour les inscriptions ou prestations.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique de la facture. |
| **identifiant_inscription** | `registration_id` | `INTEGER` | Non | - | `FK->registrations(id)` | Référence vers l'inscription liée. |
| **montant_facture** | `amount` | `NUMERIC` | Non | - | - | Montant total de la facture en FCFA. |
| **statut_paiement** | `status` | `VARCHAR` | Non | 'pending' | - | État d'encaissement (pending=En attente, paid=Payé, canceled=Annulé). |
| **reference_transaction** | `transaction_id` | `VARCHAR` | Oui | - | - | Numéro ou identifiant de transaction bancaire / mobile money. |
| **date_reglement** | `payment_date` | `DATETIME` | Oui | - | - | Date et heure effectives du règlement. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure d'émission de la facture. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

## 📦 MODULE 8 : FORMULAIRES DYNAMIQUES & ENQUÊTES

### <a id="table-form-templates"></a> 📄 MODELES_DE_FORMULAIRES (`form_templates`)

**Description Métier** : Générateur de formulaires personnalisés créés par les administrateurs et partageables via un lien public.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du modèle. |
| **titre_formulaire** | `title` | `VARCHAR` | Non | - | - | Intitulé du formulaire d'évaluation ou d'enquête. |
| **description** | `description` | `TEXT` | Oui | - | - | Consignes et explications destinées aux répondants. |
| **slug_url** | `slug` | `VARCHAR` | Non | - | `UNIQUE` | Identifiant d'URL simplifié et unique. |
| **jeton_partage_public** | `share_token` | `VARCHAR` | Non | - | `UNIQUE` | Jeton cryptographique permettant l'accès sans authentification. |
| **est_actif** | `is_active` | `TINYINT(1)` | Non | 1 | - | Indicateur d'ouverture aux réponses (1 = Actif, 0 = Fermé). |
| **identifiant_seminaire** | `seminar_id` | `INTEGER` | Oui | - | `FK->seminars(id)` | Référence vers le séminaire ciblé par l'enquête. |
| **schema_champs_json** | `fields_json` | `TEXT` | Oui | - | - | Définition des questions, types et options sous format JSON. |
| **identifiant_createur** | `created_by` | `INTEGER` | Non | - | `FK->users(id)` | Référence vers l'administrateur créateur. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de création. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de modification. |

---

### <a id="table-form-submissions"></a> 📄 SOUMISSIONS_DE_FORMULAIRES (`form_submissions`)

**Description Métier** : Réponses soumises par les candidats, formateurs ou visiteurs aux formulaires en ligne.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique de la réponse soumise. |
| **identifiant_modele** | `form_template_id` | `INTEGER` | Non | - | `FK->form_templates(id)` | Référence vers le modèle de formulaire répondu. |
| **identifiant_inscription** | `registration_id` | `INTEGER` | Oui | - | `FK->registrations(id)` | Référence vers l'inscription du participant (si authentifié). |
| **reponses_json** | `answers_json` | `TEXT` | Non | - | - | Ensemble des réponses collectées au format JSON. |
| **adresse_ip_repondant** | `respondent_ip` | `VARCHAR` | Oui | - | - | Adresse IP de la machine ayant soumis le formulaire. |
| **date_soumission** | `submitted_at` | `DATETIME` | Non | CURRENT_TIMESTAMP | - | Date et heure exactes de la soumission. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure d'enregistrement en base. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

## 📦 MODULE 9 : COMMUNICATION, DIFFUSION & NOTIFICATIONS

### <a id="table-messages"></a> 📄 MESSAGES_ET_DIFFUSIONS (`messages`)

**Description Métier** : Communications internes, annonces et messages diffusés aux formateurs, candidats et départements.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du message. |
| **identifiant_emetteur** | `user_id` | `INTEGER` | Oui | - | `FK->users(id)` | Référence vers l'utilisateur expéditeur du message. |
| **objet_message** | `subject` | `VARCHAR` | Non | - | - | Objet ou sujet principal de la communication. |
| **corps_message** | `body` | `TEXT` | Non | - | - | Texte intégral et contenu du message. |
| **identifiant_formateur_dest** | `trainer_id` | `INTEGER` | Oui | - | `FK->trainers(id)` | Formateur spécifique ciblé (le cas échéant). |
| **identifiant_edition_dest** | `cohort_id` | `INTEGER` | Oui | - | `FK->cohorts(id)` | Édition / Cohorte ciblée pour la diffusion. |
| **identifiant_departement_dest** | `department_id` | `INTEGER` | Oui | - | `FK->departments(id)` | Département ciblé. |
| **identifiant_seminaire_dest** | `seminar_id` | `INTEGER` | Oui | - | `FK->seminars(id)` | Séminaire ciblé. |
| **roles_cibles_json** | `target_roles` | `TEXT` | Oui | - | - | Rôles ciblés sous format JSON (ex: ["trainer", "candidate"]). |
| **seminaires_cibles_json** | `target_seminars` | `TEXT` | Oui | - | - | Liste des identifiants de séminaires ciblés. |
| **departements_cibles_json** | `target_departments` | `TEXT` | Oui | - | - | Liste des départements ciblés. |
| **themes_cibles_json** | `target_topics` | `TEXT` | Oui | - | - | Liste des thèmes ciblés. |
| **chemin_piece_jointe** | `attachment_path` | `VARCHAR` | Oui | - | - | Chemin vers la pièce jointe téléversée. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure d'envoi du message. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière modification. |

---

### <a id="table-message-user"></a> 📄 MESSAGES_DESTINATAIRES_LECTURES (`message_user`)

**Description Métier** : Table pivot pour le suivi de réception et d'accusé de lecture des messages par utilisateur.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté de l'état de lecture. |
| **identifiant_message** | `message_id` | `INTEGER` | Non | - | `FK->messages(id)` | Référence vers le message diffusé. |
| **identifiant_destinataire** | `user_id` | `INTEGER` | Non | - | `FK->users(id)` | Référence vers l'utilisateur destinataire. |
| **date_lecture** | `read_at` | `DATETIME` | Oui | - | - | Date et heure de consultation du message par l'utilisateur (NULL si non lu). |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de mise à disposition. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de mise à jour. |

---

### <a id="table-notifications"></a> 📄 NOTIFICATIONS_SYSTEME (`notifications`)

**Description Métier** : Notifications instantanées, alertes d'action et rappels système envoyés aux utilisateurs.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant_uuid)** | `id` | `VARCHAR` | Non | - | **PK** | Identifiant unique universel (UUID) de la notification. |
| **type_classe_notification** | `type` | `VARCHAR` | Non | - | - | Nom complet de la classe de notification Laravel. |
| **type_entite_notifiable** | `notifiable_type` | `VARCHAR` | Non | - | - | Classe du modèle récepteur (ex: App\Models\User). |
| **identifiant_entite_notifiable** | `notifiable_id` | `INTEGER` | Non | - | - | Identifiant de l'entité destinataire. |
| **donnees_charge_utile_json** | `data` | `TEXT` | Non | - | - | Contenu textuel, icônes, liens et métadonnées au format JSON. |
| **date_lecture** | `read_at` | `DATETIME` | Oui | - | - | Date et heure de marquage comme lue. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Date et heure de déclenchement de l'alerte. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière modification. |

---

## 📦 MODULE 10 : AUDIT, JOURNALISATION & HISTORIQUE

### <a id="table-activity-logs"></a> 📄 JOURNAL_DES_ACTIVITES_AUDIT (`activity_logs`)

**Description Métier** : Journalisation et audit de toutes les actions et modifications effectuées dans le système.

| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |
|---|---|---|:---:|---|:---:|---|
| **id (identifiant)** | `id` | `INTEGER` | Non | - | **PK** | Identifiant unique auto-incrémenté du journal d'action. |
| **identifiant_utilisateur** | `user_id` | `INTEGER` | Non | - | `FK->users(id)` | Référence vers l'utilisateur auteur de l'opération. |
| **action_menee** | `action` | `VARCHAR` | Non | - | - | Libellé de l'action opérée (ex: Création séminaire, Validation dossier). |
| **description_details** | `description` | `TEXT` | Oui | - | - | Détails exhaustifs des paramètres et modifications effectuées. |
| **type_modele_affecte** | `model_type` | `VARCHAR` | Oui | - | - | Nom de la classe de l'entité concernée. |
| **identifiant_modele_affecte** | `model_id` | `INTEGER` | Oui | - | - | Identifiant clé de l'entité concernée. |
| **adresse_ip_source** | `ip_address` | `VARCHAR` | Oui | - | - | Adresse IP de l'utilisateur lors de l'action. |
| **date_creation** | `created_at` | `DATETIME` | Oui | - | - | Horodatage exact de l'action. |
| **date_modification** | `updated_at` | `DATETIME` | Oui | - | - | Date et heure de dernière mise à jour. |

---

