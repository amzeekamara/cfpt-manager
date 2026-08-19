# DICTIONNAIRE DES DONNÉES - SCHÉMA EXACT MYSQL (pfpt_db)

### Table : `activity_logs`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `activity_logs` |
| `user_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> users(id)** | Référence au compte utilisateur rattaché |
| `action` | `VARCHAR(255)` | Non | NULL | - | Action |
| `description` | `TEXT` | Oui | `NULL` | - | Description |
| `model_type` | `VARCHAR(255)` | Oui | `NULL` | - | Model type |
| `model_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | - | Model id |
| `ip_address` | `VARCHAR(255)` | Oui | `NULL` | - | Ip address |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `application_documents`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `application_documents` |
| `user_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> users(id)** | Référence au compte utilisateur rattaché |
| `resource_type` | `VARCHAR(255)` | Non | NULL | - | Resource type |
| `file_path` | `VARCHAR(255)` | Non | NULL | - | File path |
| `original_filename` | `VARCHAR(255)` | Non | NULL | - | Original filename |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `attendances`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `attendances` |
| `registration_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> registrations(id)** | Registration id |
| `session_date` | `DATE` | Non | NULL | - | Session date |
| `status` | `ENUM('PRESENT','ABSENT')` | Non | NULL | - | Statut de l'élément (ex: active, pending, approved, draft...) |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |
| `topic_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> topics(id)** | Topic id |
| `trainer_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> trainers(id)** | Trainer id |

### Table : `cache`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `key` | `VARCHAR(255)` | Non | NULL | **PK** (Auto-increment) | Key |
| `value` | `MEDIUMTEXT` | Non | NULL | - | Value |
| `expiration` | `INT(11)` | Non | NULL | INDEX | Expiration |

### Table : `candidate_profiles`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `candidate_profiles` |
| `user_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> users(id)** | Référence au compte utilisateur rattaché |
| `matricule` | `VARCHAR(255)` | Oui | `NULL` | - | Matricule |
| `grade` | `VARCHAR(255)` | Oui | `NULL` | - | Grade |
| `first_names` | `VARCHAR(255)` | Oui | `NULL` | - | First names |
| `last_name` | `VARCHAR(255)` | Oui | `NULL` | - | Last name |
| `birth_date` | `DATE` | Oui | `NULL` | - | Birth date |
| `birth_place` | `VARCHAR(255)` | Oui | `NULL` | - | Birth place |
| `gender` | `VARCHAR(255)` | Oui | `NULL` | - | Gender |
| `nationality` | `VARCHAR(255)` | Oui | `NULL` | - | Nationality |
| `residence_country` | `VARCHAR(255)` | Oui | `NULL` | - | Residence country |
| `photo_path` | `VARCHAR(255)` | Oui | `NULL` | - | Photo path |
| `family_status` | `ENUM('SINGLE','MARRIED','DIVORCED','WIDOWED')` | Non | `'single'` | - | Family status |
| `religion` | `VARCHAR(255)` | Oui | `NULL` | - | Religion |
| `address` | `TEXT` | Oui | `NULL` | - | Address |
| `phone` | `VARCHAR(255)` | Oui | `NULL` | - | Phone |
| `emergency_contact_name` | `VARCHAR(255)` | Oui | `NULL` | - | Emergency contact name |
| `emergency_contact_phone` | `VARCHAR(255)` | Oui | `NULL` | - | Emergency contact phone |
| `emergency_contact_email` | `VARCHAR(255)` | Oui | `NULL` | - | Emergency contact email |
| `emergency_contact_relation` | `VARCHAR(255)` | Oui | `NULL` | - | Emergency contact relation |
| `highest_degree` | `VARCHAR(255)` | Oui | `NULL` | - | Highest degree |
| `current_profession` | `VARCHAR(255)` | Oui | `NULL` | - | Current profession |
| `current_employer` | `VARCHAR(255)` | Oui | `NULL` | - | Current employer |
| `previous_employer` | `VARCHAR(255)` | Oui | `NULL` | - | Previous employer |
| `job_description` | `TEXT` | Oui | `NULL` | - | Job description |
| `job_function` | `VARCHAR(255)` | Oui | `NULL` | - | Job function |
| `training_utility` | `TEXT` | Oui | `NULL` | - | Training utility |
| `health_record_obs` | `TEXT` | Oui | `NULL` | - | Health record obs |
| `health_questionnaire_json` | `TEXT` | Oui | `NULL` | - | Health questionnaire json |
| `insurance_details` | `VARCHAR(255)` | Oui | `NULL` | - | Insurance details |
| `insurance_premium` | `DECIMAL(15,2)` | Oui | `NULL` | - | Insurance premium |
| `accommodation_preference` | `ENUM('HOTEL','ALLOCATION','NONE')` | Oui | `NULL` | - | Accommodation preference |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `cohorts`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `cohorts` |
| `title` | `VARCHAR(255)` | Non | NULL | - | Titre officiel |
| `total_budget` | `DECIMAL(15,2)` | Non | `0.00` | - | Total budget |
| `start_date` | `DATETIME` | Non | NULL | - | Start date |
| `end_date` | `DATETIME` | Non | NULL | - | End date |
| `status` | `ENUM('DRAFT','ACTIVE','COMPLETED')` | Non | `'draft'` | - | Statut de l'élément (ex: active, pending, approved, draft...) |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `departments`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `departments` |
| `name` | `VARCHAR(255)` | Non | NULL | - | Nom ou intitulé |
| `is_active` | `TINYINT(1)` | Non | `1` | - | Is active |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `department_seminar`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `department_seminar` |
| `department_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> departments(id)** | Department id |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `educations`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `educations` |
| `candidate_profile_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> candidate_profiles(id)** | Candidate profile id |
| `establishment_name` | `VARCHAR(255)` | Non | NULL | - | Establishment name |
| `field_of_study` | `VARCHAR(255)` | Non | NULL | - | Field of study |
| `degree_obtained` | `VARCHAR(255)` | Non | NULL | - | Degree obtained |
| `distinction` | `VARCHAR(255)` | Oui | `NULL` | - | Distinction |
| `graduation_year` | `YEAR(4)` | Non | NULL | - | Graduation year |
| `resource_path` | `VARCHAR(255)` | Oui | `NULL` | - | Resource path |
| `level` | `ENUM('SECONDARY','UNIVERSITY')` | Non | NULL | - | Level |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `expenses`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `expenses` |
| `description` | `VARCHAR(255)` | Non | NULL | - | Description |
| `amount` | `DECIMAL(15,2)` | Non | NULL | - | Amount |
| `cohort_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> cohorts(id)** | Référence à la cohorte / édition annuelle rattachée |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `expense_date` | `DATETIME` | Non | NULL | - | Expense date |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |
| `expense_category_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> expense_categories(id)** | Expense category id |
| `receipt_path` | `VARCHAR(255)` | Oui | `NULL` | - | Receipt path |

### Table : `expense_categories`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `expense_categories` |
| `name` | `VARCHAR(255)` | Non | NULL | - | Nom ou intitulé |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `failed_jobs`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `failed_jobs` |
| `uuid` | `VARCHAR(255)` | Non | NULL | UNIQUE | Uuid |
| `connection` | `TEXT` | Non | NULL | - | Connection |
| `queue` | `TEXT` | Non | NULL | - | Queue |
| `payload` | `LONGTEXT` | Non | NULL | - | Payload |
| `exception` | `LONGTEXT` | Non | NULL | - | Exception |
| `failed_at` | `TIMESTAMP` | Non | `current_timestamp()` | - | Failed at |

### Table : `form_submissions`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `form_submissions` |
| `form_template_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> form_templates(id)** | Form template id |
| `answers_json` | `LONGTEXT` | Non | NULL | - | Answers json |
| `respondent_ip` | `VARCHAR(45)` | Oui | `NULL` | - | Respondent ip |
| `registration_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> registrations(id)** | Registration id |
| `submitted_at` | `TIMESTAMP` | Non | `current_timestamp()` | - | Submitted at |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `form_templates`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `form_templates` |
| `title` | `VARCHAR(255)` | Non | NULL | - | Titre officiel |
| `description` | `TEXT` | Oui | `NULL` | - | Description |
| `slug` | `VARCHAR(255)` | Non | NULL | UNIQUE | Slug |
| `share_token` | `VARCHAR(32)` | Non | NULL | UNIQUE | Share token |
| `is_active` | `TINYINT(1)` | Non | `1` | - | Is active |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `fields_json` | `LONGTEXT` | Oui | `NULL` | - | Fields json |
| `created_by` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> users(id)** | Created by |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `invoices`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `invoices` |
| `registration_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> registrations(id)** | Registration id |
| `amount` | `DECIMAL(8,2)` | Non | NULL | - | Amount |
| `status` | `ENUM('PENDING','PAID','REFUNDED')` | Non | `'pending'` | - | Statut de l'élément (ex: active, pending, approved, draft...) |
| `transaction_id` | `VARCHAR(255)` | Oui | `NULL` | - | Transaction id |
| `payment_date` | `DATETIME` | Oui | `NULL` | - | Payment date |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `jobs`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `jobs` |
| `queue` | `VARCHAR(255)` | Non | NULL | INDEX | Queue |
| `payload` | `LONGTEXT` | Non | NULL | - | Payload |
| `attempts` | `TINYINT(3) UNSIGNED` | Non | NULL | - | Attempts |
| `reserved_at` | `INT(10) UNSIGNED` | Oui | `NULL` | - | Reserved at |
| `available_at` | `INT(10) UNSIGNED` | Non | NULL | - | Available at |
| `created_at` | `INT(10) UNSIGNED` | Non | NULL | - | Date et heure de création de l'enregistrement |

### Table : `messages`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `messages` |
| `trainer_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> trainers(id)** | Trainer id |
| `cohort_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> cohorts(id)** | Référence à la cohorte / édition annuelle rattachée |
| `department_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> departments(id)** | Department id |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `subject` | `VARCHAR(255)` | Non | NULL | - | Subject |
| `body` | `TEXT` | Non | NULL | - | Body |
| `attachment_path` | `VARCHAR(255)` | Oui | `NULL` | - | Attachment path |
| `target_roles` | `LONGTEXT` | Oui | `NULL` | - | Target roles |
| `target_seminars` | `LONGTEXT` | Oui | `NULL` | - | Target seminars |
| `target_departments` | `LONGTEXT` | Oui | `NULL` | - | Target departments |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |
| `user_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> users(id)** | Référence au compte utilisateur rattaché |
| `target_topics` | `LONGTEXT` | Oui | `NULL` | - | Target topics |

### Table : `message_user`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `message_user` |
| `message_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> messages(id)** | Message id |
| `user_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> users(id)** | Référence au compte utilisateur rattaché |
| `read_at` | `TIMESTAMP` | Oui | `NULL` | - | Read at |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `notifications`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `CHAR(36)` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `notifications` |
| `type` | `VARCHAR(255)` | Non | NULL | - | Type |
| `notifiable_type` | `VARCHAR(255)` | Non | NULL | INDEX | Notifiable type |
| `notifiable_id` | `BIGINT(20) UNSIGNED` | Non | NULL | - | Notifiable id |
| `data` | `TEXT` | Non | NULL | - | Data |
| `read_at` | `TIMESTAMP` | Oui | `NULL` | - | Read at |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `password_reset_tokens`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `email` | `VARCHAR(255)` | Non | NULL | **PK** (Auto-increment) | Adresse de messagerie électronique (unique) |
| `token` | `VARCHAR(255)` | Non | NULL | - | Token |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |

### Table : `profile_update_requests`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `profile_update_requests` |
| `user_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> users(id)** | Référence au compte utilisateur rattaché |
| `requested_changes` | `LONGTEXT` | Non | NULL | - | Requested changes |
| `status` | `ENUM('PENDING','APPROVED','REJECTED')` | Non | `'pending'` | - | Statut de l'élément (ex: active, pending, approved, draft...) |
| `admin_feedback` | `TEXT` | Oui | `NULL` | - | Admin feedback |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `registrations`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `registrations` |
| `user_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> users(id)** | Référence au compte utilisateur rattaché |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `status` | `ENUM('PENDING','APPROVED','REJECTED','WAITING_LIST')` | Non | `'pending'` | - | Statut de l'élément (ex: active, pending, approved, draft...) |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |
| `topic_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> topics(id)** | Topic id |

### Table : `resources`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `resources` |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `title` | `VARCHAR(255)` | Non | NULL | - | Titre officiel |
| `file_path` | `VARCHAR(255)` | Non | NULL | - | File path |
| `role_visibility` | `ENUM('ALL','FORMATEUR','CANDIDAT','COORDINATEUR')` | Non | `'all'` | - | Role visibility |
| `is_archived` | `TINYINT(1)` | Non | `0` | - | Is archived |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `roles`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `roles` |
| `name` | `VARCHAR(255)` | Non | NULL | UNIQUE | Nom ou intitulé |
| `description` | `TEXT` | Oui | `NULL` | - | Description |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `seminars`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `seminars` |
| `title` | `VARCHAR(255)` | Non | NULL | - | Titre officiel |
| `description` | `TEXT` | Oui | `NULL` | - | Description |
| `image` | `VARCHAR(255)` | Oui | `NULL` | - | Image |
| `start_date` | `DATETIME` | Non | NULL | - | Start date |
| `end_date` | `DATETIME` | Non | NULL | - | End date |
| `max_participants` | `INT(11)` | Non | `30` | - | Max participants |
| `status` | `ENUM('DRAFT','ACTIVE','COMPLETED')` | Non | `'draft'` | - | Statut de l'élément (ex: active, pending, approved, draft...) |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |
| `cohort_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> cohorts(id)** | Référence à la cohorte / édition annuelle rattachée |

### Table : `seminar_change_requests`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `seminar_change_requests` |
| `user_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> users(id)** | Référence au compte utilisateur rattaché |
| `registration_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> registrations(id)** | Registration id |
| `new_seminar_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> seminars(id)** | New seminar id |
| `reason` | `TEXT` | Oui | `NULL` | - | Reason |
| `status` | `ENUM('PENDING','APPROVED','REJECTED')` | Non | `'pending'` | - | Statut de l'élément (ex: active, pending, approved, draft...) |
| `admin_feedback` | `TEXT` | Oui | `NULL` | - | Admin feedback |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `seminar_trainer`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `seminar_trainer` |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `trainer_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> trainers(id)** | Trainer id |
| `remuneration` | `DECIMAL(10,2)` | Non | `0.00` | - | Remuneration |
| `themes_count` | `INT(11)` | Oui | `NULL` | - | Themes count |
| `selected_topics` | `LONGTEXT` | Oui | `NULL` | - | Selected topics |
| `hours_count` | `INT(11)` | Oui | `NULL` | - | Hours count |
| `payment_status` | `VARCHAR(255)` | Non | `'unpaid'` | - | Payment status |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `sessions`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `VARCHAR(255)` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `sessions` |
| `user_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | INDEX | Référence au compte utilisateur rattaché |
| `ip_address` | `VARCHAR(45)` | Oui | `NULL` | - | Ip address |
| `user_agent` | `TEXT` | Oui | `NULL` | - | User agent |
| `payload` | `LONGTEXT` | Non | NULL | - | Payload |
| `last_activity` | `INT(11)` | Non | NULL | INDEX | Last activity |

### Table : `topics`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `topics` |
| `name` | `VARCHAR(255)` | Non | NULL | - | Nom ou intitulé |
| `is_active` | `TINYINT(1)` | Non | `1` | - | Is active |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |

### Table : `trainers`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `trainers` |
| `name` | `VARCHAR(255)` | Non | NULL | - | Nom ou intitulé |
| `email` | `VARCHAR(255)` | Oui | `NULL` | - | Adresse de messagerie électronique (unique) |
| `phone` | `VARCHAR(255)` | Oui | `NULL` | - | Phone |
| `birth_date` | `DATE` | Oui | `NULL` | - | Birth date |
| `birth_place` | `VARCHAR(255)` | Oui | `NULL` | - | Birth place |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `remuneration` | `DECIMAL(15,2)` | Non | `0.00` | - | Remuneration |
| `payment_status` | `ENUM('UNPAID','PAID')` | Non | `'unpaid'` | - | Payment status |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `trainer_sessions`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `trainer_sessions` |
| `trainer_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> trainers(id)** | Trainer id |
| `seminar_id` | `BIGINT(20) UNSIGNED` | Non | NULL | **FK -> seminars(id)** | Référence au séminaire de formation rattaché |
| `topic_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> topics(id)** | Topic id |
| `session_date` | `DATE` | Non | NULL | - | Session date |
| `hours_taught` | `INT(11)` | Non | `0` | - | Hours taught |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |

### Table : `users`

| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT(20) UNSIGNED` | Non | NULL | **PK** (Auto-increment) | Identifiant unique de la table `users` |
| `name` | `VARCHAR(255)` | Non | NULL | - | Nom ou intitulé |
| `email` | `VARCHAR(255)` | Non | NULL | UNIQUE | Adresse de messagerie électronique (unique) |
| `google_id` | `VARCHAR(255)` | Oui | `NULL` | UNIQUE | Google id |
| `avatar` | `VARCHAR(255)` | Oui | `NULL` | - | Avatar |
| `email_verified_at` | `TIMESTAMP` | Oui | `NULL` | - | Email verified at |
| `password` | `VARCHAR(255)` | Non | NULL | - | Mot de passe haché en Bcrypt |
| `remember_token` | `VARCHAR(100)` | Oui | `NULL` | - | Remember token |
| `created_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de création de l'enregistrement |
| `updated_at` | `TIMESTAMP` | Oui | `NULL` | - | Date et heure de dernière modification |
| `role_id` | `BIGINT(20) UNSIGNED` | Oui | `NULL` | **FK -> roles(id)** | Référence au rôle attribué à l'utilisateur |
| `specialty` | `VARCHAR(255)` | Oui | `NULL` | - | Specialty |
| `position` | `VARCHAR(255)` | Oui | `NULL` | - | Position |
| `supervisor` | `VARCHAR(255)` | Oui | `NULL` | - | Supervisor |
| `must_change_password` | `TINYINT(1)` | Non | `1` | - | Must change password |

