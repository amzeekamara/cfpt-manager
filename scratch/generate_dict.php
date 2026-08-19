<?php

$data = [
    [
        'module' => "MODULE 1 : AUTHENTIFICATION, COMPTES & GESTION DES ACCÈS",
        'description_module' => "Gestion sécurisée des identités, des rôles applicatifs et de la récupération de compte.",
        'tables' => [
            [
                'name_fr' => "UTILISATEURS",
                'name_tech' => "users",
                'description' => "Comptes utilisateurs de la plateforme (Administrateurs, Coordinateurs, Formateurs, Candidats, Agents JICA).",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté de l'utilisateur."],
                    ['nom_fr' => 'nom_complet', 'col' => 'name', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Nom et prénom(s) complets du compte."],
                    ['nom_fr' => 'adresse_courriel', 'col' => 'email', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => 'UNIQUE', 'desc' => "Adresse email officielle / identifiant unique de connexion."],
                    ['nom_fr' => 'date_verification_courriel', 'col' => 'email_verified_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de confirmation de l'adresse courriel."],
                    ['nom_fr' => 'mot_de_passe_chiffre', 'col' => 'password', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Empreinte sécurisée du mot de passe (hachage Bcrypt)."],
                    ['nom_fr' => 'jeton_memorisation', 'col' => 'remember_token', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Jeton de persistance de session (Se souvenir de moi)."],
                    ['nom_fr' => 'identifiant_role', 'col' => 'role_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->roles(id)', 'desc' => "Rôle attribué à l'utilisateur définissant ses privilèges."],
                    ['nom_fr' => 'identifiant_google', 'col' => 'google_id', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Identifiant unique OAuth Google pour l'authentification tierce."],
                    ['nom_fr' => 'chemin_avatar', 'col' => 'avatar', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Chemin d'accès au fichier image de la photo de profil."],
                    ['nom_fr' => 'changement_mdp_requis', 'col' => 'must_change_password', 'type' => 'TINYINT(1)', 'null' => 'NON', 'def' => '1', 'cle' => '-', 'desc' => "Indicateur imposant le renouvellement du mot de passe temporaire à la première connexion."],
                    ['nom_fr' => 'specialite', 'col' => 'specialty', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Domaine d'expertise ou spécialité technique."],
                    ['nom_fr' => 'poste_fonction', 'col' => 'position', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Fonction administrative ou académique occupée."],
                    ['nom_fr' => 'superviseur', 'col' => 'supervisor', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Nom du responsable ou superviseur direct."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "ROLES_ET_PERMISSIONS",
                'name_tech' => "roles",
                'description' => "Niveaux d'accès et types de profils dans le système (Admin, Formateur, Candidat, etc.).",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du rôle."],
                    ['nom_fr' => 'libelle_role', 'col' => 'name', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Code / Nom du rôle (ex: admin, trainer, candidate, jica_agent)."],
                    ['nom_fr' => 'description', 'col' => 'description', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Description détaillée des prérogatives associées au rôle."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "JETONS_REINITIALISATION_MOT_DE_PASSE",
                'name_tech' => "password_reset_tokens",
                'description' => "Jetons sécurisés pour la procédure de réinitialisation de mot de passe par courriel.",
                'columns' => [
                    ['nom_fr' => 'adresse_courriel', 'col' => 'email', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Adresse email du compte demandeur de réinitialisation."],
                    ['nom_fr' => 'jeton_securite', 'col' => 'token', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Jeton cryptographique à usage unique."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de génération du jeton de réinitialisation."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 2 : GESTION DES ÉDITIONS & SESSIONS DU PROGRAMME",
        'description_module' => "Administration des promotions annuelles, cycles de formation et budgets globaux alloués.",
        'tables' => [
            [
                'name_fr' => "EDITIONS_ET_COHORTES",
                'name_tech' => "cohorts",
                'description' => "Cycles et promotions annuelles du programme de formation continue PFPT - CFPT / JICA.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté de l'édition / cohorte."],
                    ['nom_fr' => 'intitule_edition', 'col' => 'title', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Intitulé officiel de la session (ex: Édition 2026 – 2027)."],
                    ['nom_fr' => 'budget_total_alloue', 'col' => 'total_budget', 'type' => 'NUMERIC', 'null' => 'NON', 'def' => '0', 'cle' => '-', 'desc' => "Budget total d'exploitation alloué à l'édition en FCFA."],
                    ['nom_fr' => 'date_debut', 'col' => 'start_date', 'type' => 'DATETIME', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Date officielle de démarrage de la session de formation."],
                    ['nom_fr' => 'date_fin', 'col' => 'end_date', 'type' => 'DATETIME', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Date de clôture et fin des séminaires de la cohorte."],
                    ['nom_fr' => 'statut_edition', 'col' => 'status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'draft'", 'cle' => '-', 'desc' => "Statut opérationnel de l'édition (draft, active, completed, archived)."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 3 : STRUCTURE PÉDAGOGIQUE, SÉMINAIRES & THÈMES",
        'description_module' => "Organisation départementale, catalogues de séminaires et modules thématiques de spécialisation.",
        'tables' => [
            [
                'name_fr' => "DEPARTEMENTS_PEDAGOGIQUES",
                'name_tech' => "departments",
                'description' => "Départements et filières techniques du centre de formation (Électrique, Mécanique, Informatique, etc.).",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du département."],
                    ['nom_fr' => 'nom_departement', 'col' => 'name', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Nom ou libellé complet du département technique."],
                    ['nom_fr' => 'est_actif', 'col' => 'is_active', 'type' => 'TINYINT(1)', 'null' => 'NON', 'def' => '1', 'cle' => '-', 'desc' => "État d'activation du département (1 = Actif, 0 = Inactif)."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "SEMINAIRES_DE_FORMATION",
                'name_tech' => "seminars",
                'description' => "Séminaires de renforcement de capacités organisés au sein d'une édition.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du séminaire."],
                    ['nom_fr' => 'titre_seminaire', 'col' => 'title', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Intitulé complet du séminaire de formation."],
                    ['nom_fr' => 'description_objectifs', 'col' => 'description', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Description du programme, prérequis et objectifs pédagogiques."],
                    ['nom_fr' => 'date_debut', 'col' => 'start_date', 'type' => 'DATETIME', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Date de commencement de la session de séminaire."],
                    ['nom_fr' => 'date_fin', 'col' => 'end_date', 'type' => 'DATETIME', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Date de fin et clôture du séminaire."],
                    ['nom_fr' => 'capacite_max_places', 'col' => 'max_participants', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '30', 'cle' => '-', 'desc' => "Nombre maximum de participants autorisés (capacité d'accueil)."],
                    ['nom_fr' => 'statut_seminaire', 'col' => 'status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'draft'", 'cle' => '-', 'desc' => "État du séminaire (draft, active, completed, archived)."],
                    ['nom_fr' => 'image_couverture', 'col' => 'image', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Chemin vers l'image d'illustration ou bannière du séminaire."],
                    ['nom_fr' => 'identifiant_edition', 'col' => 'cohort_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->cohorts(id)', 'desc' => "Référence vers l'édition / cohorte de rattachement."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "THEMATIQUES_ET_MODULES",
                'name_tech' => "topics",
                'description' => "Modules thématiques et sous-domaines de spécialisation dispensés au sein d'un séminaire.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté de la thématique."],
                    ['nom_fr' => 'libelle_theme', 'col' => 'name', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Intitulé complet du thème de formation technique."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire auquel est rattaché le thème."],
                    ['nom_fr' => 'est_actif', 'col' => 'is_active', 'type' => 'TINYINT(1)', 'null' => 'NON', 'def' => '1', 'cle' => '-', 'desc' => "Indicateur d'activation du module (1 = Actif, 0 = Inactif)."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "LIAISONS_DEPARTEMENTS_SEMINAIRES",
                'name_tech' => "department_seminar",
                'description' => "Table de liaison Many-to-Many entre les départements pédagogiques et les séminaires.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique de la liaison."],
                    ['nom_fr' => 'identifiant_departement', 'col' => 'department_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->departments(id)', 'desc' => "Référence vers le département pédagogique concerné."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire de formation associé."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 4 : CANDIDATURES, DOSSIERS D'ADMISSION & GESTION DES PARTICIPANTS",
        'description_module' => "Traitement des demandes d'admission, profils normalisés JICA, documents justificatifs et suivi de statut.",
        'tables' => [
            [
                'name_fr' => "INSCRIPTIONS_ET_CANDIDATURES",
                'name_tech' => "registrations",
                'description' => "Inscriptions et candidatures des participants aux séminaires avec circuit de décision et validation.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté de l'inscription."],
                    ['nom_fr' => 'identifiant_utilisateur', 'col' => 'user_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers le compte du candidat postulant."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire choisi."],
                    ['nom_fr' => 'identifiant_theme', 'col' => 'topic_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->topics(id)', 'desc' => "Référence vers le thème spécifique sélectionné par le candidat."],
                    ['nom_fr' => 'statut_decision', 'col' => 'status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'pending'", 'cle' => '-', 'desc' => "État d'admission du dossier (pending=À Examiner, approved=Sélectionné, rejected=Refusé, waiting_list=Liste d'attente)."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de soumission de la candidature."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour de la décision."]
                ]
            ],
            [
                'name_fr' => "PROFILS_CANDIDATS_JICA",
                'name_tech' => "candidate_profiles",
                'description' => "Informations complètes du formulaire de candidature normalisé JICA (état civil, contact d'urgence, employeur, santé, logistique).",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du profil candidat."],
                    ['nom_fr' => 'identifiant_utilisateur', 'col' => 'user_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers le compte utilisateur associé."],
                    ['nom_fr' => 'prenoms', 'col' => 'first_names', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Prénom(s) du candidat selon les pièces d'identité."],
                    ['nom_fr' => 'nom_famille', 'col' => 'last_name', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Nom de famille du candidat."],
                    ['nom_fr' => 'date_naissance', 'col' => 'birth_date', 'type' => 'DATE', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date de naissance du candidat."],
                    ['nom_fr' => 'lieu_naissance', 'col' => 'birth_place', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Lieu ou ville de naissance."],
                    ['nom_fr' => 'genre_sexe', 'col' => 'gender', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Genre / Sexe du candidat (M/F)."],
                    ['nom_fr' => 'nationalite', 'col' => 'nationality', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Nationalité / Pays d'origine."],
                    ['nom_fr' => 'pays_residence', 'col' => 'residence_country', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Pays de résidence habituelle."],
                    ['nom_fr' => 'photo_dossier', 'col' => 'photo_path', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Chemin vers la photo d'identité d'admission."],
                    ['nom_fr' => 'situation_matrimoniale', 'col' => 'family_status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'single'", 'cle' => '-', 'desc' => "Situation familiale (single=Célibataire, married=Marié, etc.)."],
                    ['nom_fr' => 'religion_confession', 'col' => 'religion', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Confession religieuse (pour les dispositions de restauration/séjour)."],
                    ['nom_fr' => 'adresse_postale', 'col' => 'address', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Adresse géographique complète de résidence."],
                    ['nom_fr' => 'numero_telephone', 'col' => 'phone', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Numéro de téléphone portable direct."],
                    ['nom_fr' => 'contact_urgence_nom', 'col' => 'emergency_contact_name', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Nom complet de la personne à prévenir en cas d'urgence."],
                    ['nom_fr' => 'contact_urgence_telephone', 'col' => 'emergency_contact_phone', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Numéro téléphonique du contact d'urgence."],
                    ['nom_fr' => 'contact_urgence_courriel', 'col' => 'emergency_contact_email', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Adresse courriel du contact d'urgence."],
                    ['nom_fr' => 'contact_urgence_lien', 'col' => 'emergency_contact_relation', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Lien de parenté ou relation avec le contact d'urgence."],
                    ['nom_fr' => 'diplome_plus_eleve', 'col' => 'highest_degree', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Plus haut diplôme académique ou technique obtenu."],
                    ['nom_fr' => 'profession_actuelle', 'col' => 'current_profession', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Métier / Profession exercée actuellement."],
                    ['nom_fr' => 'employeur_actuel', 'col' => 'current_employer', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Organisation, ministère ou entreprise employeur actuel."],
                    ['nom_fr' => 'employeur_precedent', 'col' => 'previous_employer', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Précédent employeur."],
                    ['nom_fr' => 'description_poste', 'col' => 'job_description', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Description détaillée des tâches professionnelles au quotidien."],
                    ['nom_fr' => 'utilite_de_la_formation', 'col' => 'training_utility', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Motivation et impact attendu de la formation sur le poste."],
                    ['nom_fr' => 'observations_medicales', 'col' => 'health_record_obs', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Remarques médicales, allergies ou antécédents particuliers."],
                    ['nom_fr' => 'questionnaire_sante_json', 'col' => 'health_questionnaire_json', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Réponses détaillées au questionnaire médical JICA au format JSON."],
                    ['nom_fr' => 'details_assurance', 'col' => 'insurance_details', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Détails et police de couverture d'assurance souscrite."],
                    ['nom_fr' => 'prime_assurance', 'col' => 'insurance_premium', 'type' => 'NUMERIC', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Montant de la prime d'assurance en FCFA."],
                    ['nom_fr' => 'preference_hebergement', 'col' => 'accommodation_preference', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Choix de formule d'hébergement au campus."],
                    ['nom_fr' => 'matricule_agent', 'col' => 'matricule', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Numéro matricule officiel de la fonction publique ou entreprise."],
                    ['nom_fr' => 'grade_echelon', 'col' => 'grade', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Grade ou échelon professionnel."],
                    ['nom_fr' => 'fonction_occupee', 'col' => 'job_function', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Intitulé exact de la fonction occupée."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "DOCUMENTS_ET_PIECES_JUSTIFICATIVES",
                'name_tech' => "application_documents",
                'description' => "Pièces justificatives téléversées lors de la candidature (CV, diplômes, lettres de recommandation, passeport).",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du document."],
                    ['nom_fr' => 'identifiant_utilisateur', 'col' => 'user_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers l'utilisateur propriétaire du document."],
                    ['nom_fr' => 'type_piece_justificative', 'col' => 'resource_type', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Type de document (cv, diploma, recommendation_letter, passport, id_card)."],
                    ['nom_fr' => 'chemin_fichier_stockage', 'col' => 'file_path', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Chemin relatif sécurisé de stockage du fichier sur le serveur."],
                    ['nom_fr' => 'nom_fichier_original', 'col' => 'original_filename', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Nom d'origine du fichier lors du téléversement."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de téléversement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière modification."]
                ]
            ],
            [
                'name_fr' => "PARCOURS_ACADEMIQUE_ET_DIPLOMES",
                'name_tech' => "educations",
                'description' => "Cursus universitaire, diplômes et formations antérieures suivies par les candidats.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique du parcours académique."],
                    ['nom_fr' => 'identifiant_profil_candidat', 'col' => 'candidate_profile_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->candidate_profiles(id)', 'desc' => "Référence vers le profil candidat rattaché."],
                    ['nom_fr' => 'nom_etablissement', 'col' => 'establishment_name', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Nom de l'université, école ou institut de formation."],
                    ['nom_fr' => 'domaine_etudes', 'col' => 'field_of_study', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Filière ou domaine d'études suivi."],
                    ['nom_fr' => 'diplome_obtenu', 'col' => 'degree_obtained', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Intitulé du diplôme ou certificat obtenu."],
                    ['nom_fr' => 'mention_distinction', 'col' => 'distinction', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Mention académique obtenue (ex: Très bien, Assez bien)."],
                    ['nom_fr' => 'annee_obtention', 'col' => 'graduation_year', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Année de diplomation."],
                    ['nom_fr' => 'niveau_etudes', 'col' => 'level', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Niveau d'études (ex: BAC+2, Licence, Master, Ingénieur)."],
                    ['nom_fr' => 'chemin_justificatif', 'col' => 'resource_path', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Chemin vers la copie numérisée du diplôme."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "DEMANDES_CHANGEMENT_SEMINAIRE",
                'name_tech' => "seminar_change_requests",
                'description' => "Demandes de réaffectation de séminaire ou de session soumises par les candidats.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique de la demande de réorientation."],
                    ['nom_fr' => 'identifiant_utilisateur', 'col' => 'user_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers l'utilisateur émetteur de la demande."],
                    ['nom_fr' => 'identifiant_inscription', 'col' => 'registration_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->registrations(id)', 'desc' => "Référence vers la candidature initiale concernée."],
                    ['nom_fr' => 'identifiant_nouveau_seminaire', 'col' => 'new_seminar_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le nouveau séminaire sollicité."],
                    ['nom_fr' => 'motif_demande', 'col' => 'reason', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Explication et justification du motif de changement."],
                    ['nom_fr' => 'statut_demande', 'col' => 'status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'pending'", 'cle' => '-', 'desc' => "État de traitement (pending=En attente, approved=Accordé, rejected=Refusé)."],
                    ['nom_fr' => 'avis_administration', 'col' => 'admin_feedback', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Commentaires ou motifs de la décision administrative."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de formulation de la demande."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de traitement par l'administration."]
                ]
            ],
            [
                'name_fr' => "DEMANDES_MODIFICATION_PROFIL",
                'name_tech' => "profile_update_requests",
                'description' => "Demandes de modification de données personnelles soumises par les candidats ou formateurs.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique de la demande."],
                    ['nom_fr' => 'identifiant_utilisateur', 'col' => 'user_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers l'utilisateur demandeur."],
                    ['nom_fr' => 'modifications_sollicitees_json', 'col' => 'requested_changes', 'type' => 'TEXT', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Détail des données modifiées au format JSON."],
                    ['nom_fr' => 'statut_demande', 'col' => 'status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'pending'", 'cle' => '-', 'desc' => "Statut de validation (pending, approved, rejected)."],
                    ['nom_fr' => 'avis_administration', 'col' => 'admin_feedback', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Commentaires de l'administrateur en charge."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de soumission."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de validation."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 5 : CORPS ENSEIGNANT & GESTION DES FORMATEURS",
        'description_module' => "Gestion du corps professoral, vacations, volumes horaires et rémunérations.",
        'tables' => [
            [
                'name_fr' => "PROFILS_FORMATEURS",
                'name_tech' => "trainers",
                'description' => "Fiches profils et coordonnées complémentaires des formateurs et experts enseignants.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du formateur."],
                    ['nom_fr' => 'nom_complet', 'col' => 'name', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Nom et prénom(s) complets de l'enseignant / formateur."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire principal d'affectation."],
                    ['nom_fr' => 'montant_remuneration', 'col' => 'remuneration', 'type' => 'NUMERIC', 'null' => 'NON', 'def' => '0', 'cle' => '-', 'desc' => "Montant total des honoraires / vacations convenus en FCFA."],
                    ['nom_fr' => 'statut_paiement', 'col' => 'payment_status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'unpaid'", 'cle' => '-', 'desc' => "Statut de règlement financier (unpaid=Impayé, paid=Payé, partial=Partiel)."],
                    ['nom_fr' => 'adresse_courriel', 'col' => 'email', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Adresse email professionnelle."],
                    ['nom_fr' => 'numero_telephone', 'col' => 'phone', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Numéro de téléphone professionnel."],
                    ['nom_fr' => 'date_naissance', 'col' => 'birth_date', 'type' => 'DATE', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date de naissance du formateur."],
                    ['nom_fr' => 'lieu_naissance', 'col' => 'birth_place', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Lieu de naissance."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "SESSIONS_ET_HEURES_FORMATEURS",
                'name_tech' => "trainer_sessions",
                'description' => "Enregistrement des cours et séances d'animation assurés par les formateurs.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique de la session d'animation."],
                    ['nom_fr' => 'identifiant_formateur', 'col' => 'trainer_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->trainers(id)', 'desc' => "Référence vers le formateur intervenant."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire concerné."],
                    ['nom_fr' => 'identifiant_theme', 'col' => 'topic_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->topics(id)', 'desc' => "Référence vers le thème dispensé."],
                    ['nom_fr' => 'date_seance', 'col' => 'session_date', 'type' => 'DATE', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Date de déroulement de la séance de cours."],
                    ['nom_fr' => 'volume_heures_dispensees', 'col' => 'hours_taught', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '0', 'cle' => '-', 'desc' => "Nombre d'heures effectives d'enseignement dispensées."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure d'enregistrement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "AFFECTATIONS_FORMATEURS_SEMINAIRES",
                'name_tech' => "seminar_trainer",
                'description' => "Table de liaison Many-to-Many entre les séminaires et les formateurs avec suivi des thèmes, heures et rémunérations.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique de l'affectation."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire de formation."],
                    ['nom_fr' => 'identifiant_formateur', 'col' => 'trainer_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->trainers(id)', 'desc' => "Référence vers le formateur assigné."],
                    ['nom_fr' => 'montant_remuneration', 'col' => 'remuneration', 'type' => 'NUMERIC', 'null' => 'NON', 'def' => '0', 'cle' => '-', 'desc' => "Montant de vacation attribué pour cette mission."],
                    ['nom_fr' => 'statut_paiement', 'col' => 'payment_status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'unpaid'", 'cle' => '-', 'desc' => "État du paiement (unpaid, paid, partial)."],
                    ['nom_fr' => 'nombre_themes_assignes', 'col' => 'themes_count', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Nombre total de thématiques confiées."],
                    ['nom_fr' => 'quota_heures_prevu', 'col' => 'hours_count', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Volume horaire contractuel assigné."],
                    ['nom_fr' => 'themes_selectionnes_json', 'col' => 'selected_topics', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Liste des identifiants de thèmes sous format JSON."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création de l'affectation."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 6 : SUIVI PÉDAGOGIQUE, ÉMARGEMENTS & RESSOURCES (GED)",
        'description_module' => "Suivi de l'assiduité des apprenants et gestion électronique des supports pédagogiques.",
        'tables' => [
            [
                'name_fr' => "PRESENCES_ET_EMARGEMENTS",
                'name_tech' => "attendances",
                'description' => "Feuilles d'émargement et suivi journalier des présences/absences par participant et par séance.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté de l'émargement."],
                    ['nom_fr' => 'identifiant_inscription', 'col' => 'registration_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->registrations(id)', 'desc' => "Référence vers l'inscription du candidat présent/absent."],
                    ['nom_fr' => 'identifiant_theme', 'col' => 'topic_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->topics(id)', 'desc' => "Référence vers le thème de cours concerné."],
                    ['nom_fr' => 'identifiant_formateur', 'col' => 'trainer_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->trainers(id)', 'desc' => "Référence vers le formateur animateur."],
                    ['nom_fr' => 'date_seance', 'col' => 'session_date', 'type' => 'DATE', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Date de la séance d'enseignement."],
                    ['nom_fr' => 'statut_presence', 'col' => 'status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "État d'émargement (present=Présent, absent=Absent, justified=Absence justifiée, late=En retard)."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de saisie de l'émargement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière modification."]
                ]
            ],
            [
                'name_fr' => "RESSOURCES_ET_SUPPORTS_PEDAGOGIQUES",
                'name_tech' => "resources",
                'description' => "Gestion Électronique des Documents (GED), supports de cours, polycopiés et guides téléversés.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique du document pédagogique."],
                    ['nom_fr' => 'titre_document', 'col' => 'title', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Titre officiel ou désignation du support pédagogique."],
                    ['nom_fr' => 'chemin_fichier', 'col' => 'file_path', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Chemin de stockage du fichier sur le serveur (PDF, ZIP, DOCX, etc.)."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire auquel est adossée la ressource."],
                    ['nom_fr' => 'visibilite_par_role', 'col' => 'role_visibility', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'all'", 'cle' => '-', 'desc' => "Visibilité et restriction d'accès (all=Tous, trainers=Formateurs uniquement, candidates=Candidats)."],
                    ['nom_fr' => 'est_archive', 'col' => 'is_archived', 'type' => 'TINYINT(1)', 'null' => 'NON', 'def' => '0', 'cle' => '-', 'desc' => "Indicateur d'archivage (1 = Archivé, 0 = Actif)."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de téléversement."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière modification."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 7 : GESTION FINANCIÈRE, BUDGETS & FACTURATION",
        'description_module' => "Suivi analytique des dépenses, rubriques budgétaires et émission des ordres de facturation.",
        'tables' => [
            [
                'name_fr' => "DEPENSES_ET_DECAISSEMENTS",
                'name_tech' => "expenses",
                'description' => "Dépenses budgétaires, décaissements et achats opérationnels engagés pour les cohortes et séminaires.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté de la dépense."],
                    ['nom_fr' => 'description_motif', 'col' => 'description', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Description explicative et justification de la dépense."],
                    ['nom_fr' => 'montant_depense', 'col' => 'amount', 'type' => 'NUMERIC', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Montant financier décaissé en FCFA."],
                    ['nom_fr' => 'identifiant_edition', 'col' => 'cohort_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->cohorts(id)', 'desc' => "Référence vers l'édition / cohorte budgétaire concernée."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire imputé (si applicable)."],
                    ['nom_fr' => 'identifiant_categorie', 'col' => 'expense_category_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->expense_categories(id)', 'desc' => "Référence vers la catégorie comptable de dépense."],
                    ['nom_fr' => 'date_operation', 'col' => 'expense_date', 'type' => 'DATETIME', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Date effective de paiement ou d'engagement de la dépense."],
                    ['nom_fr' => 'chemin_justificatif_recu', 'col' => 'receipt_path', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Chemin vers la facture ou le reçu justificatif numérisé."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de saisie comptable."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ],
            [
                'name_fr' => "CATEGORIES_DE_DEPENSES",
                'name_tech' => "expense_categories",
                'description' => "Plan comptable et rubriques budgétaires (ex: Hébergement, Restauration, Honoraires, Matériel).",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique de la catégorie."],
                    ['nom_fr' => 'nom_categorie', 'col' => 'name', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Intitulé officiel de la catégorie budgétaire."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de modification."]
                ]
            ],
            [
                'name_fr' => "FACTURES_ET_PAIEMENTS",
                'name_tech' => "invoices",
                'description' => "Factures, reçus et titres de paiement générés pour les inscriptions ou prestations.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique de la facture."],
                    ['nom_fr' => 'identifiant_inscription', 'col' => 'registration_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->registrations(id)', 'desc' => "Référence vers l'inscription liée."],
                    ['nom_fr' => 'montant_facture', 'col' => 'amount', 'type' => 'NUMERIC', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Montant total de la facture en FCFA."],
                    ['nom_fr' => 'statut_paiement', 'col' => 'status', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => "'pending'", 'cle' => '-', 'desc' => "État d'encaissement (pending=En attente, paid=Payé, canceled=Annulé)."],
                    ['nom_fr' => 'reference_transaction', 'col' => 'transaction_id', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Numéro ou identifiant de transaction bancaire / mobile money."],
                    ['nom_fr' => 'date_reglement', 'col' => 'payment_date', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure effectives du règlement."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure d'émission de la facture."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 8 : FORMULAIRES DYNAMIQUES & ENQUÊTES",
        'description_module' => "Création de formulaires modulables, collectes de retours et évaluations de satisfaction.",
        'tables' => [
            [
                'name_fr' => "MODELES_DE_FORMULAIRES",
                'name_tech' => "form_templates",
                'description' => "Générateur de formulaires personnalisés créés par les administrateurs et partageables via un lien public.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du modèle."],
                    ['nom_fr' => 'titre_formulaire', 'col' => 'title', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Intitulé du formulaire d'évaluation ou d'enquête."],
                    ['nom_fr' => 'description', 'col' => 'description', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Consignes et explications destinées aux répondants."],
                    ['nom_fr' => 'slug_url', 'col' => 'slug', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => 'UNIQUE', 'desc' => "Identifiant d'URL simplifié et unique."],
                    ['nom_fr' => 'jeton_partage_public', 'col' => 'share_token', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => 'UNIQUE', 'desc' => "Jeton cryptographique permettant l'accès sans authentification."],
                    ['nom_fr' => 'est_actif', 'col' => 'is_active', 'type' => 'TINYINT(1)', 'null' => 'NON', 'def' => '1', 'cle' => '-', 'desc' => "Indicateur d'ouverture aux réponses (1 = Actif, 0 = Fermé)."],
                    ['nom_fr' => 'identifiant_seminaire', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Référence vers le séminaire ciblé par l'enquête."],
                    ['nom_fr' => 'schema_champs_json', 'col' => 'fields_json', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Définition des questions, types et options sous format JSON."],
                    ['nom_fr' => 'identifiant_createur', 'col' => 'created_by', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers l'administrateur créateur."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de création."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de modification."]
                ]
            ],
            [
                'name_fr' => "SOUMISSIONS_DE_FORMULAIRES",
                'name_tech' => "form_submissions",
                'description' => "Réponses soumises par les candidats, formateurs ou visiteurs aux formulaires en ligne.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique de la réponse soumise."],
                    ['nom_fr' => 'identifiant_modele', 'col' => 'form_template_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->form_templates(id)', 'desc' => "Référence vers le modèle de formulaire répondu."],
                    ['nom_fr' => 'identifiant_inscription', 'col' => 'registration_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->registrations(id)', 'desc' => "Référence vers l'inscription du participant (si authentifié)."],
                    ['nom_fr' => 'reponses_json', 'col' => 'answers_json', 'type' => 'TEXT', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Ensemble des réponses collectées au format JSON."],
                    ['nom_fr' => 'adresse_ip_repondant', 'col' => 'respondent_ip', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Adresse IP de la machine ayant soumis le formulaire."],
                    ['nom_fr' => 'date_soumission', 'col' => 'submitted_at', 'type' => 'DATETIME', 'null' => 'NON', 'def' => 'CURRENT_TIMESTAMP', 'cle' => '-', 'desc' => "Date et heure exactes de la soumission."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure d'enregistrement en base."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 9 : COMMUNICATION, DIFFUSION & NOTIFICATIONS",
        'description_module' => "Messagerie instantanée, diffusions ciblées multi-canaux et alertes système.",
        'tables' => [
            [
                'name_fr' => "MESSAGES_ET_DIFFUSIONS",
                'name_tech' => "messages",
                'description' => "Communications internes, annonces et messages diffusés aux formateurs, candidats et départements.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du message."],
                    ['nom_fr' => 'identifiant_emetteur', 'col' => 'user_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers l'utilisateur expéditeur du message."],
                    ['nom_fr' => 'objet_message', 'col' => 'subject', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Objet ou sujet principal de la communication."],
                    ['nom_fr' => 'corps_message', 'col' => 'body', 'type' => 'TEXT', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Texte intégral et contenu du message."],
                    ['nom_fr' => 'identifiant_formateur_dest', 'col' => 'trainer_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->trainers(id)', 'desc' => "Formateur spécifique ciblé (le cas échéant)."],
                    ['nom_fr' => 'identifiant_edition_dest', 'col' => 'cohort_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->cohorts(id)', 'desc' => "Édition / Cohorte ciblée pour la diffusion."],
                    ['nom_fr' => 'identifiant_departement_dest', 'col' => 'department_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->departments(id)', 'desc' => "Département ciblé."],
                    ['nom_fr' => 'identifiant_seminaire_dest', 'col' => 'seminar_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => 'FK->seminars(id)', 'desc' => "Séminaire ciblé."],
                    ['nom_fr' => 'roles_cibles_json', 'col' => 'target_roles', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Rôles ciblés sous format JSON (ex: [\"trainer\", \"candidate\"])."],
                    ['nom_fr' => 'seminaires_cibles_json', 'col' => 'target_seminars', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Liste des identifiants de séminaires ciblés."],
                    ['nom_fr' => 'departements_cibles_json', 'col' => 'target_departments', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Liste des départements ciblés."],
                    ['nom_fr' => 'themes_cibles_json', 'col' => 'target_topics', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Liste des thèmes ciblés."],
                    ['nom_fr' => 'chemin_piece_jointe', 'col' => 'attachment_path', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Chemin vers la pièce jointe téléversée."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure d'envoi du message."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière modification."]
                ]
            ],
            [
                'name_fr' => "MESSAGES_DESTINATAIRES_LECTURES",
                'name_tech' => "message_user",
                'description' => "Table pivot pour le suivi de réception et d'accusé de lecture des messages par utilisateur.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté de l'état de lecture."],
                    ['nom_fr' => 'identifiant_message', 'col' => 'message_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->messages(id)', 'desc' => "Référence vers le message diffusé."],
                    ['nom_fr' => 'identifiant_destinataire', 'col' => 'user_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers l'utilisateur destinataire."],
                    ['nom_fr' => 'date_lecture', 'col' => 'read_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de consultation du message par l'utilisateur (NULL si non lu)."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de mise à disposition."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de mise à jour."]
                ]
            ],
            [
                'name_fr' => "NOTIFICATIONS_SYSTEME",
                'name_tech' => "notifications",
                'description' => "Notifications instantanées, alertes d'action et rappels système envoyés aux utilisateurs.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant_uuid)', 'col' => 'id', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique universel (UUID) de la notification."],
                    ['nom_fr' => 'type_classe_notification', 'col' => 'type', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Nom complet de la classe de notification Laravel."],
                    ['nom_fr' => 'type_entite_notifiable', 'col' => 'notifiable_type', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Classe du modèle récepteur (ex: App\\Models\\User)."],
                    ['nom_fr' => 'identifiant_entite_notifiable', 'col' => 'notifiable_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Identifiant de l'entité destinataire."],
                    ['nom_fr' => 'donnees_charge_utile_json', 'col' => 'data', 'type' => 'TEXT', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Contenu textuel, icônes, liens et métadonnées au format JSON."],
                    ['nom_fr' => 'date_lecture', 'col' => 'read_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de marquage comme lue."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de déclenchement de l'alerte."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière modification."]
                ]
            ]
        ]
    ],
    [
        'module' => "MODULE 10 : AUDIT, JOURNALISATION & HISTORIQUE",
        'description_module' => "Traçabilité des opérations, historique des accès et conformité de sécurité.",
        'tables' => [
            [
                'name_fr' => "JOURNAL_DES_ACTIVITES_AUDIT",
                'name_tech' => "activity_logs",
                'description' => "Journalisation et audit de toutes les actions et modifications effectuées dans le système.",
                'columns' => [
                    ['nom_fr' => 'id (identifiant)', 'col' => 'id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'PK', 'desc' => "Identifiant unique auto-incrémenté du journal d'action."],
                    ['nom_fr' => 'identifiant_utilisateur', 'col' => 'user_id', 'type' => 'INTEGER', 'null' => 'NON', 'def' => '-', 'cle' => 'FK->users(id)', 'desc' => "Référence vers l'utilisateur auteur de l'opération."],
                    ['nom_fr' => 'action_menee', 'col' => 'action', 'type' => 'VARCHAR', 'null' => 'NON', 'def' => '-', 'cle' => '-', 'desc' => "Libellé de l'action opérée (ex: Création séminaire, Validation dossier)."],
                    ['nom_fr' => 'description_details', 'col' => 'description', 'type' => 'TEXT', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Détails exhaustifs des paramètres et modifications effectuées."],
                    ['nom_fr' => 'type_modele_affecte', 'col' => 'model_type', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Nom de la classe de l'entité concernée."],
                    ['nom_fr' => 'identifiant_modele_affecte', 'col' => 'model_id', 'type' => 'INTEGER', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Identifiant clé de l'entité concernée."],
                    ['nom_fr' => 'adresse_ip_source', 'col' => 'ip_address', 'type' => 'VARCHAR', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Adresse IP de l'utilisateur lors de l'action."],
                    ['nom_fr' => 'date_creation', 'col' => 'created_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Horodatage exact de l'action."],
                    ['nom_fr' => 'date_modification', 'col' => 'updated_at', 'type' => 'DATETIME', 'null' => 'OUI', 'def' => '-', 'cle' => '-', 'desc' => "Date et heure de dernière mise à jour."]
                ]
            ]
        ]
    ]
];

// 1. GENERATE TXT
$txt = "================================================================================\n";
$txt .= "          DICTIONNAIRE DES DONNEES - SYSTEME DE GESTION PFPT - CFPT\n";
$txt .= "================================================================================\n";
$txt .= "Date de generation : " . date('d/m/Y H:i:s') . "\n";
$txt .= "Organisation : Classement thematique par Modules fonctionnels\n";
$txt .= "Langue : Francais integral avec correspondance des champs techniques\n\n";

foreach ($data as $modIdx => $mod) {
    $txt .= "================================================================================\n";
    $txt .= " " . $mod['module'] . "\n";
    $txt .= " DESCRIPTION : " . $mod['description_module'] . "\n";
    $txt .= "================================================================================\n\n";

    foreach ($mod['tables'] as $tbl) {
        $txt .= "--------------------------------------------------------------------------------\n";
        $txt .= "TABLE : " . $tbl['name_fr'] . " (Nom technique : " . $tbl['name_tech'] . ")\n";
        $txt .= "DESCRIPTION : " . $tbl['description'] . "\n";
        $txt .= "--------------------------------------------------------------------------------\n";
        $txt .= sprintf("%-30s | %-24s | %-12s | %-8s | %-10s | %-22s | %s\n", 
            "ELEMENT (FRANCAIS)", "CHAMP TECHNIQUE", "TYPE", "NULLABLE", "DEFAUT", "CLE", "DESCRIPTION");
        $txt .= str_repeat("-", 140) . "\n";

        foreach ($tbl['columns'] as $col) {
            $txt .= sprintf("%-30s | %-24s | %-12s | %-8s | %-10s | %-22s | %s\n",
                $col['nom_fr'],
                $col['col'],
                $col['type'],
                $col['null'],
                $col['def'],
                $col['cle'],
                $col['desc']
            );
        }
        $txt .= "\n\n";
    }
}

file_put_contents(__DIR__ . '/../DICTIONNAIRE_DE_DONNEES.txt', $txt);

// 2. GENERATE MD
$md = "# 📚 DICTIONNAIRE DES DONNÉES - SYSTÈME DE GESTION PFPT • CFPT\n\n";
$md .= "> **Projet** : Plateforme de Formation Professionnelle & Technique (PFPT • CFPT / JICA)\n";
$md .= "> **Généré le** : " . date('d/m/Y H:i:s') . "\n";
$md .= "> **Organisation** : Structuré par **Modules Fonctionnels Métier**\n";
$md .= "> **Nomenclature** : Entités et éléments entièrement traduits en français avec correspondances techniques.\n\n";
$md .= "---\n\n";

$md .= "## 📌 SOMMAIRE DES MODULES & TABLES\n\n";

$totalTblCount = 0;
foreach ($data as $mod) {
    $md .= "### 🗂️ " . $mod['module'] . "\n";
    $md .= "*" . $mod['description_module'] . "*\n\n";
    $md .= "| # | Nom en Français | Table Technique | Description Métier | Nbr Colonnes |\n";
    $md .= "|---|---|---|---|:---:|\n";
    foreach ($mod['tables'] as $tbl) {
        $totalTblCount++;
        $anchor = "table-" . strtolower(str_replace('_', '-', $tbl['name_tech']));
        $md .= sprintf("| %d | [%s](#%s) | `%s` | %s | %d |\n",
            $totalTblCount,
            $tbl['name_fr'],
            $anchor,
            $tbl['name_tech'],
            $tbl['description'],
            count($tbl['columns'])
        );
    }
    $md .= "\n";
}

$md .= "---\n\n";
$md .= "## 🗄️ SPÉCIFICATION DÉTAILLÉE DES TABLES PAR MODULE\n\n";

foreach ($data as $mod) {
    $md .= "## 📦 " . $mod['module'] . "\n\n";
    
    foreach ($mod['tables'] as $tbl) {
        $anchor = "table-" . strtolower(str_replace('_', '-', $tbl['name_tech']));
        $md .= "### <a id=\"{$anchor}\"></a> 📄 " . $tbl['name_fr'] . " (`" . $tbl['name_tech'] . "`)\n\n";
        $md .= "**Description Métier** : " . $tbl['description'] . "\n\n";
        
        $md .= "| Élément (Français) | Champ Technique | Type de Donnée | Nullable | Valeur par Défaut | Clé | Description / Rôle Métier |\n";
        $md .= "|---|---|---|:---:|---|:---:|---|\n";
        
        foreach ($tbl['columns'] as $col) {
            $cleFormatted = $col['cle'] === 'PK' ? '**PK**' : ($col['cle'] !== '-' ? '`' . $col['cle'] . '`' : '-');
            $md .= sprintf("| **%s** | `%s` | `%s` | %s | %s | %s | %s |\n",
                $col['nom_fr'],
                $col['col'],
                $col['type'],
                $col['null'] === 'NON' ? 'Non' : 'Oui',
                $col['def'],
                $cleFormatted,
                $col['desc']
            );
        }
        $md .= "\n---\n\n";
    }
}

file_put_contents(__DIR__ . '/../DICTIONNAIRE_DE_DONNEES.md', $md);

echo "OK - Dictionnaire généré avec succès dans les 2 formats.\n";
