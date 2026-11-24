# FICHE DE SYNTHÈSE EXHAUSTIVE DU MÉMOIRE DE FIN D'ÉTUDES
## Projet : PFPT Manager – Plateforme de Gestion des Séminaires Internationaux PFPT au CFPT S-J
**Auteur :** Hamadou KAMARA  
**Encadreur Académique :** Monsieur Mlassane BALDÉ  
**Institution :** Institut Supérieur de l'Informatique (ISI Dakar) – Département Génie Informatique (Licence 3 Génie Logiciel)  
**Organisme d'Accueil & Partenaire :** CFPT Sénégal-Japon / JICA (Agence Japonaise de Coopération Internationale)  
**Année Académique :** 2025 / 2026  

---

## 🎯 VUE D'ENSEMBLE DU PROJET (EXECUTIVE SUMMARY)

| Rubrique | Synthèse des Éléments Clés |
| :--- | :--- |
| **Intitulé du Thème** | Conception et Réalisation d'une Application Web pour la Gestion des Séminaires PFPT au CFPT S-J |
| **Contexte** | Le Programme de Formation en Pays Tiers (PFPT), initié en 1999 au CFPT Sénégal-Japon sous l'égide de la JICA, accueille chaque année ~100 participants venus de 18 pays africains pour des séminaires techniques de 4 à 5 semaines. |
| **Problématique** | Gestion manuelle fragmentée (papier, tableurs Excel disparates, e-mails), dispersion des candidatures internationales, lourdeur du pointage des présences, risques d'erreurs d'imputation budgétaire et complexité de reporting vis-à-vis de la JICA. |
| **Solution Développée** | **PFPT Manager** : Une application web full-stack centralisée, sécurisée, modulaire et responsive, pilotant l'intégralité du cycle de vie des séminaires (candidatures, émargement, GED, finances, enquêtes, reporting). |
| **Stack Technique** | **Backend :** PHP 8.2+, Framework Laravel (MVC, Eloquent ORM, RBAC, Validation FormRequests)  <br>**Frontend :** Blade Templates, Tailwind CSS, Vite, JavaScript Vanilla  <br>**Base de Données :** MySQL 8.0 (InnoDB, Clés étrangères, Indexation, Transactions ACID)  <br>**Assurance Qualité :** PHPUnit (Tests unitaires & Feature tests), Git/GitHub |
| **Architecture** | 3 Tiers modulaire (Présentation, Logique Métier/Services, Persistance ORM) + Couche transversale de sécurité (RBAC 6 rôles, CSRF, XSS Sanitization, Guards d'autorisation). |

---

## 📖 RÉSUMÉ STRUCTURÉ PAR CHAPITRE AVEC LES POINTS PHARES

---

### 🔹 INTRODUCTION GÉNÉRALE

* **Contexte Global :** Rôle central de l'enseignement technique et de la formation professionnelle (ETFP) en Afrique, soutenu par la coopération bilatérale nippo-sénégalaise (JICA et CFPT S-J) dans le cadre de la coopération Sud-Sud.
* **Justification du Projet :** Nécessité d'une transition numérique impérative pour dépasser les goulets d'étranglement administratifs liés à la montée en charge du programme (18 pays bénéficiaires).
* **Objectif Global :** Fournir un outil numérique unifié, performant et pérenne assurant la traçabilité des données pédagogiques, financières et documentaires.
* **Articulation du Document :** 4 chapitres ordonnés selon le cycle de génie logiciel (Présentation & Cadrage $\rightarrow$ Modélisation UML $\rightarrow$ Réalisation Technique $\rightarrow$ Validation & Perspectives).

---

### 🔹 CHAPITRE I : PRÉSENTATION DU PROJET

#### 1. Contexte Institutionnel & Évolution du PFPT
* **Le CFPT Sénégal-Japon :** Centre d'excellence sous tutelle du Ministère de la Formation Professionnelle, financé à sa création par la JICA, formant l'élite technique sous-régionale.
* **Le Programme PFPT :** Initié en 1999, plus de deux décennies d'existence, dispensant des sessions intensives de 4 à 5 semaines aux formateurs et techniciens d'Afrique francophone et anglophone.
* **Enjeux Stratégiques :** Transfert de technologies, montée en compétences pédagogiques des formateurs africains, renforcement des synergies régionales.

#### 2. Étude de l'Existant & Limites
* **Processus Manuel Actuel :** 
  * Appels à candidatures relayés par voies diplomatiques et e-mails informels.
  * Collecte et tri manuels des dossiers (passeports, diplômes, autorisations ministérielles).
  * Feuilles d'émargement papier vulnérables aux pertes et altérations.
  * Budgétisation et liquidation des dépenses sur fichiers Excel sans contrôle d'intégrité strict.
* **Exigences Spécifiques JICA :** Auditabilité intégrale, justification ligne par ligne des allocations budgétaires et production de bilans d'activités certifiés.
* **Limites Constatées :** Surcharge administrative, perte d'informations, absence d'accès en temps réel aux indicateurs pour les bailleurs, délais importants de consolidation des rapports.

#### 3. Problématique & Objectifs du Projet
* **Question Centrale :** *« Comment concevoir et développer une application web évolutive permettant de piloter l'intégralité du cycle de vie des séminaires PFPT – de la candidature en ligne jusqu'au suivi financier détaillé – tout en garantissant la sécurité des données et en unifiant la gestion dans un outil unique et accessible sur tous les terminaux ? »*
* **Objectifs Spécifiques :**
  1. **Portail Candidat :** Dématérialisation complète des dépôts de candidature avec vérification documentaire.
  2. **Centralisation Administrative :** Gestion unifiée des départements, thèmes, cohortes et séminaires.
  3. **Émargement Numérique :** Pointage par séance (Matin/Après-midi) avec calcul automatisé des taux de présence et d'assiduité.
  4. **Contrôle Budgétaire & Financier :** Suivi analytique avec distinction stricte entre budget d'édition (Cohorte) et dépenses propres à un séminaire.
  5. **GED & Formulaires Dynamiques :** Stockage sécurisé des supports pédagogiques et création d'enquêtes d'évaluation à chaud/à froid.

#### 4. Démarche Méthodologique de Conduite de Projet
* **Approche Retenue :** Méthode **Agile, Itérative et Incrémentale**.
* **Cycle en 5 Phases par Incrément :**
  1. *Analyse & Cadrage* (étude des fiches métiers, formalisation du cahier des charges).
  2. *Conception & Prototypage* (Modélisation UML sous StarUML, Wireframes Figma).
  3. *Développement Full-Stack* (Laravel MVC, Tailwind CSS, Clean Code SOLID).
  4. *Assurance Qualité & Tests Automatisés* (PHPUnit, Git/GitHub versioning).
  5. *Recette & Validation* (vérification de conformité et passage à l'incrément suivant).
* **Découpage en Incréments :** Core & Authentification $\rightarrow$ Gestion Pédagogique $\rightarrow$ Candidatures & Émargement $\rightarrow$ Finances & GED $\rightarrow$ Tableaux de Bord & Reporting.

---

### 🔹 CHAPITRE II : MODÉLISATION DE L'APPLICATION WEB

#### 1. Analyse des Besoins & Acteurs
* **Typologie des 6 Rôles Utilisateurs (RBAC) :**
  1. **Super Administrateur / Administrateur :** Gouvernance globale, gestion des accès, configuration des cohortes/séminaires, contrôle budgétaire total.
  2. **Coordinateur PFPT :** Planification logistique, gestion des intervenants, validation des dossiers de candidatures.
  3. **Formateur :** Saisie des présences journalières, consultation des listes d'apprenants, dépôt des supports de cours.
  4. **Candidat / Séminariste :** Soumission du dossier en ligne, suivi du statut (soumis, retenu, rejeté), consultation de l'emploi du temps, émargement, téléchargement des attestations.
  5. **JICA (Bailleur / Partenaire) :** Consultation en lecture seule des tableaux de bord financiers, taux d'exécution et rapports consolidés.
  6. **Visiteur / Invité (Public) :** Consultation de la vitrine institutionnelle, catalogue des séminaires ouverts et formulaire d'inscription.
* **Exigences Non-Fonctionnelles Clés :** Temps de réponse $< 1.5$s, haute disponibilité, responsive design (Mobile/Tablette/PC), sécurité conforme aux standards OWASP (protection CSRF, hachage bcrypt, isolation des fichiers privés).

#### 2. Conception & Modélisation UML vs Merise
* **Choix d'UML (Unified Modeling Language) :** Retenu face à Merise pour son adéquation naturelle avec la programmation orientée objet (POO), le framework Laravel et l'ORM Eloquent.
* **Dictionnaire de Données & MCD :** Plus de 20 entités relationnelles modélisées (`users`, `cohorts`, `seminars`, `departments`, `topics`, `candidate_profiles`, `registrations`, `attendances`, `expenses`, `documents`, `forms`, etc.).
* **Diagrammes de Cas d'Utilisation :**
  * *Diagramme Global :* Vue transverse des interactions entre les 6 acteurs.
  * *Diagrammes Détaillés par Package :* Gestion des utilisateurs, Inscriptions & Candidatures, Émargement & Assiduité, Suivi Financier, GED & Enquêtes.
* **Diagramme de Classes & Règles Métier Sensibles :**
  * **Règle d'Imputation Budgétaire Exclusive :** Une dépense (`Expense`) est rattachée soit au budget global d'une Cohorte (`cohort_id`), soit à un Séminaire précis (`seminar_id`), garantissant l'étanchéité des lignes comptables.
  * **Cycle de Vie Multi-Sessions du Candidat :** Découplage entre l'identité pérenne (`CandidateProfile` lié à `User`) et la participation à une édition donnée (`Registration`), permettant à un candidat de postuler à de futures sessions sans recréer son profil.
  * **Émargement Journalier & Intégrité Pédagogique :** Contrainte d'unicité `(registration_id, date, session_type)` empêchant les doublons de pointage matin/après-midi.

#### 3. Architecture Conceptuelle 3 Tiers & Sécurité
* **Couche Présentation (Vue) :** Blade Engine, Tailwind CSS, composants UI réutilisables, validation côté client.
* **Couche Logique Métier (Contrôleur / Services) :** Contrôleurs Laravel minces, FormRequests pour la validation stricte, Services dédiés (calculs d'assiduité, bilans financiers).
* **Couche Accès aux Données (Persistance) :** Eloquent ORM, relations polymorphiques, requêtes optimisées contre le problème N+1 (`eager loading`).
* **Couche Transverse Sécurité :** Middleware d'authentification (`auth`), contrôle d'accès basé sur les rôles (`RoleMiddleware`), protection CSRF et Sanitization XSS.

#### 4. Conception Ergonomique UI/UX (Maquettes Figma)
* Approche axée sur la simplicité d'usage pour des utilisateurs hétérogènes (africains et bailleurs japonais).
* Définition d'un Design System harmonieux (bleu institutionnel CFPT, vert succès, gris neutre, badges de statuts explicites).
* Wireframes validés pour chaque interface : Tableau de bord Admin, Fiche Candidat multi-étapes, Interface tactile d'émargement Formateur.

---

### 🔹 CHAPITRE III : RÉALISATION DE L'APPLICATION WEB

#### 1. Environnement & Choix Technologiques
* **Langages :** PHP 8.2+, SQL (MySQL 8.0 InnoDB), HTML5 / CSS3, JavaScript ES6.
* **Frameworks & Outils :** Laravel 10/11, Tailwind CSS, Vite (bundler moderne ultra-rapide), Composer, NPM.
* **Environnement de Travail :** Visual Studio Code / Antigravity IDE, Git/GitHub, Laragon / PHP CLI, StarUML, Figma.

#### 2. Développement Backend & Modules Clés
* **Cycle de Vie d'une Requête :** `index.php` $\rightarrow$ `HTTP Kernel` (Middlewares) $\rightarrow$ `Route Dispatcher` $\rightarrow$ `FormRequest Validation` $\rightarrow$ `Controller / Service` $\rightarrow$ `Eloquent ORM` $\rightarrow$ `Blade Response`.
* **Les 10 Modules Fonctionnels Implémentés :**
  1. *Module 1 : Authentification & Sécurité RBAC* (Gestion sécurisée des sessions, permissions strictes par rôle).
  2. *Module 2 : Cohortes & Éditions* (Cycle de vie des promotions annuelles, dates limites).
  3. *Module 3 : Structure Pédagogique & Départements* (Organisation arborescente : Départements $\rightarrow$ Thèmes $\rightarrow$ Séminaires).
  4. *Module 4 : Portail de Candidature en Ligne* (Formulaire multi-étapes, upload sécurisé des pièces jointes, validation par le coordinateur).
  5. *Module 5 : Émargement Numérique & Suivi Pédagogique* (Feuilles de présence quotidiennes matin/soir, calcul automatique du taux d'assiduité).
  6. *Module 6 : Gestion Financière & Dépenses* (Suivi des décaissements, imputation par cohorte/séminaire, ventilation des lignes JICA).
  7. *Module 7 : Gestion Électronique des Documents (GED)* (Téléversement, catégorisation et téléchargement sécurisé des supports de formation).
  8. *Module 8 : Formulaires Dynamiques & Évaluations* (Générateur d'enquêtes pour mesurer la satisfaction des séminaristes).
  9. *Module 9 : Notifications & Communication* (Alertes par e-mail et notifications in-app lors du changement de statut des candidatures).
  10. *Module 10 : Tableaux de Bord & Reporting Décisionnel* (Statistiques consolidées pour l'administration et vue dédiée pour la JICA).
* **Assurance Qualité & Tests Backend :**
  * Tests automatisés avec **PHPUnit** (Tests unitaires de calculs financiers et tests fonctionnels d'authentification et de soumission de formulaires publics).
  * Fiabilisation contre les vulnérabilités de masse (`$fillable` whitelisté, hachage fort, validations de types MIME).

#### 3. Développement Frontend & Expérience Utilisateur
* **Architecture Blade Modulaire :** Layouts maîtres (`app.blade.php`, `admin.blade.php`, `guest.blade.php`), inclusion de composants réutilisables (`navbar`, `sidebar`, `badge`, `modal`, `stat-card`).
* **Design System Tailwind CSS :** Compilé avec **Vite**, garantissant un bundle CSS allégé et des performances d'affichage instantanées.
* **Interactivité JavaScript :** Filtrage dynamique des tables sans rechargement, calculs instantanés de montants, confirmation modale et prévisualisation des fichiers téléversés.

---

### 🔹 CHAPITRE IV : ENVIRONNEMENT D'EXÉCUTION, VALIDATION ET PERSPECTIVES DE DÉPLOIEMENT

#### 1. Environnement d'Exécution Actuel & Bilan des Tests
* Validation réussie en environnement de développement local (PHP 8.2, MySQL 8.0 InnoDB, Vite HMR).
* Tests unitaires et d'intégration validés à 100% sans régression.
* Recette fonctionnelle concluante sur l'ensemble des parcours utilisateurs (Admin, Coordinateur, Formateur, Candidat, JICA).

#### 2. Stratégie & Procédure de Déploiement Futur (Production)
* **Comparatif des Solutions d'Hébergement :**
  * *Hébergement Mutualisé :* Rejeté car restrictif (absence d'accès SSH, limitations de processus de background/queues).
  * *PaaS Cloud (AWS/GCP) :* Coûteux et complexe pour les besoins opérationnels du centre.
  * **Solution Retenue : Serveur Dédié Virtuel (VPS Linux Ubuntu Server)** $\rightarrow$ Meilleur compromis performance / sécurité / maîtrise des coûts.
* **Architecture Technique de Déploiement :**
  * Système d'exploitation : Ubuntu 22.04 LTS.
  * Serveur Web : Nginx configuré en reverse proxy vers PHP-FPM 8.2.
  * Sécurité : Certificat SSL Let's Encrypt (HTTPS), Pare-feu UFW, Fail2ban.
  * Automatisation CI/CD : Déploiement automatisé via GitHub Actions lors des pushs sur la branche `main`.

#### 3. Validation des Objectifs & Impact Opérationnel
* **Gains de Productivité :** Réduction de plus de 80% du temps de traitement des dossiers de candidature.
* **Zéro Perte de Données :** Remplacement des feuilles volantes par une base de données centralisée avec sauvegardes automatiques.
* **Transparence Financière :** Visibilité immédiate des dépenses pour les auditeurs et bailleurs de la JICA.

#### 4. Évaluation Financière & Coût Global de Possession (TCO)
* **Estimation des Coûts Annuels d'Exploitation :**
  * Hébergement VPS (4 vCPU, 8 Go RAM, 100 Go NVMe) : ~300 000 FCFA / an.
  * Nom de Domaine institutionnel (`.sn` ou `.org`) : ~20 000 FCFA / an.
  * Certificat SSL et Sécurité : 30 000 FCFA / an.
  * Sauvegardes automatisées distantes : 60 000 FCFA / an.
  * **Coût Global Annuel Estimé : ~410 000 FCFA / an** (un investissement minime au regard des économies logistiques et de papier réalisées).

---

### 🔹 CONCLUSION GÉNÉRALE & PERSPECTIVES

#### 1. Bilan des Réalisations
* Aboutissement d'une solution logicielle robuste, complète et directement exploitable par le CFPT Sénégal-Japon pour ses 10 modules.
* Le système répond rigoureusement à l'ensemble des spécifications du cahier des charges initial.

#### 2. Apports Méthodologiques & Techniques
* Maîtrise du cycle de vie complet d'un projet d'ingénierie logicielle (de l'analyse métier à la validation technique).
* Application rigoureuse des concepts avancés du génie logiciel : Modélisation UML, Architecture MVC, Patron Service/Repository, Clean Code, Sécurité RBAC, Automatisation des tests.

#### 3. Perspectives d'Évolutions Futures
1. **Application Mobile / PWA :** Permettre l'émargement hors-ligne des formateurs via QR Code sécurisé.
2. **Intelligence Artificielle & Automatisation :** Pré-filtrage automatisé des dossiers de candidature (OCR et scoring de conformité des diplômes).
3. **Signature Électronique Certifiée :** Génération et signature numérique des attestations et certificats de fin de formation vérifiables en ligne.
4. **Intégration de Passerelles de Paiement :** Gestion dématérialisée des per diem et frais de séjour pour les séminaristes internationaux.

---

### 🔹 ANNEXES & CAHIER DES CHARGES
* **Annexe 1 :** Cahier des charges exhaustif du projet PFPT Manager (Périmètre, spécifications fonctionnelles et non fonctionnelles, matrice de traçabilité, planning prévisionnel de 11 semaines).
* **Annexe 2 & Suivantes :** Grilles de validation et maquettes structurelles détaillées.

---

## 🎤 CONSEILS STRATÉGIQUES POUR VOTRE PRÉSENTATION DE SOUTENANCE

1. **Accroche (1 min) :** Partez de la réalité du CFPT-SJ et du partenariat historique avec la JICA depuis 1999 (18 pays, 100 participants/an) et de la difficulté concrète de la gestion manuelle.
2. **Problématique & Solution (2 min) :** Présentez le défi du décloisonnement administratif et introduisez *PFPT Manager* comme la réponse technologique intégrée.
3. **Conception & Modélisation (3 min) :** Montrez le diagramme de classes et insistez sur les règles métier sensibles (imputation budgétaire exclusive Cohorte/Séminaire, gestion du profil candidat multi-sessions, contraintes d'émargement).
4. **Démonstration / Réalisation Technique (5 min) :** Mettez en avant le portail candidat, la validation par l'admin, la grille d'émargement et le tableau de bord financier JICA sous Laravel/Tailwind.
5. **Déploiement & Bilan (2 min) :** Expliquez la stratégie VPS/Nginx/CI-CD, la couverture de tests PHPUnit et le budget prévisionnel (410 000 FCFA/an).
6. **Conclusion & Perspectives (1 min) :** Terminez avec assurance sur l'impact opérationnel pour le CFPT et l'ouverture vers l'émargement QR-Code et l'IA.
