<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mot de passe oublié — CFPT Manager - CFPT Sénégal-Japon</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Polices Google : Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        /* ==========================================================================
       VARIABLES & DESIGN TOKENS (CHARTE BLEUE OFFICIELLE PFPT : #4B6A95)
       ========================================================================== */
        :root {
            --primary-blue: #4B6A95;
            --primary-hover: #354e70;
            --primary-dark: #162536;
            --accent-blue-light: #f3f6fb;
            --accent-blue-border: #d2e4ff;
            --text-main: #0f172a;
            --text-muted: #526071;
            --text-label: #475569;
            --border-input: #cbd5e1;
            --border-focus: #4B6A95;
            --bg-input: #ffffff;
            --shadow-modal: 0 25px 60px -12px rgba(22, 37, 54, 0.25), 0 18px 36px -18px rgba(0, 0, 0, 0.15);
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-heading: 'Plus Jakarta Sans', var(--font-body);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 10% 20%, #354e70 0%, #162536 60%, #0c1622 100%);
            padding: 24px 16px;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ==========================================================================
       MODAL CARD CONTAINER (SPLIT-SCREEN)
       ========================================================================== */
        .app-window {
            width: 100%;
            max-width: 980px;
            min-height: 580px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: var(--shadow-modal);
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* ==========================================================================
       LEFT SIDEBAR (OFFICIAL PFPT BLUE HERO / IDENTITY)
       ========================================================================== */
        .sidebar-pane {
            width: 40%;
            position: relative;
            background-color: #162536;
            background-image:
                linear-gradient(180deg, rgba(75, 106, 149, 0.90) 0%, rgba(53, 78, 112, 0.94) 50%, rgba(22, 37, 54, 0.98) 100%),
                url('{{ asset('images/centre.png') }}');
            background-size: cover;
            background-position: center center;
            padding: 40px 34px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
            z-index: 1;
        }

        .sidebar-pane::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 60%, rgba(210, 228, 255, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
            text-decoration: none;
        }

        .brand-logo-img {
            height: 42px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .brand-logo-text {
            line-height: 1.2;
        }

        .brand-logo-title {
            font-family: var(--font-heading);
            font-size: 15px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.2px;
            display: block;
        }

        .brand-logo-sub {
            font-size: 10.5px;
            font-weight: 600;
            color: #d2e4ff;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .sidebar-copy {
            margin: auto 0 32px;
            position: relative;
            z-index: 2;
        }

        .sidebar-badge {
            display: inline-block;
            background: transparent;
            border: none;
            color: #d2e4ff;
            padding: 0;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .sidebar-copy h2 {
            font-family: var(--font-heading);
            font-size: 21px;
            font-weight: 700;
            line-height: 1.35;
            color: #ffffff;
            letter-spacing: -0.3px;
            margin-bottom: 12px;
        }

        .sidebar-copy p {
            font-size: 13px;
            font-weight: 400;
            color: #e2e8f0;
            line-height: 1.5;
        }

        .sidebar-footer {
            position: relative;
            z-index: 2;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 18px;
        }

        .sidebar-footer-links {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 11.5px;
            color: #cbd5e1;
            margin-bottom: 14px;
        }

        .sidebar-footer-links a {
            color: #e2e8f0;
            text-decoration: none;
            transition: color 0.2s;
        }

        .sidebar-footer-links a:hover {
            color: #ffffff;
        }

        .flags-container {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .flag-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: none;
            padding: 0;
            font-size: 11px;
            font-weight: 600;
            color: #e2e8f0;
        }

        /* ==========================================================================
       RIGHT CONTENT PANE (FORM & ACTIONS)
       ========================================================================== */
        .content-pane {
            width: 60%;
            background: #ffffff;
            padding: 38px 44px 34px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .header-back {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            background-color: #f1f5f9;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-back-home:hover {
            color: var(--primary-blue);
            background-color: #e2e8f0;
        }

        /* Form Container */
        .form-wrapper {
            max-width: 420px;
            width: 100%;
            margin: 15px auto auto;
        }

        .form-wrapper h1 {
            font-family: var(--font-heading);
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
            text-align: center;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.45;
        }

        /* Alertes & Notifications */
        .alert-box {
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            line-height: 1.4;
        }

        .alert-success {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Champs de saisie */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-label);
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper input {
            width: 100%;
            height: 44px;
            padding: 0 42px 0 14px;
            font-family: var(--font-body);
            font-size: 13.5px;
            color: var(--text-main);
            background-color: var(--bg-input);
            border: 1px solid var(--border-input);
            border-radius: 8px;
            outline: none;
            transition: all 0.2s ease;
        }

        .input-wrapper input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-wrapper input:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(75, 106, 149, 0.15);
        }

        .input-wrapper.error input {
            border-color: #ef4444;
        }

        .field-error-msg {
            font-size: 11.5px;
            color: #dc2626;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Bouton d'action principal */
        .btn-submit {
            width: 100%;
            height: 46px;
            background-color: var(--primary-blue);
            color: #ffffff;
            border: none;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            margin-top: 22px;
            box-shadow: 0 6px 16px -3px rgba(75, 106, 149, 0.4);
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -3px rgba(75, 106, 149, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .return-login-box {
            text-align: center;
            margin-top: 24px;
        }

        .return-login-link {
            font-size: 13px;
            color: var(--primary-blue);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .return-login-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* ==========================================================================
       RESPONSIVE BREAKPOINTS
       ========================================================================== */
        @media (max-width: 860px) {
            .app-window {
                flex-direction: column;
                max-width: 520px;
                min-height: auto;
            }

            .sidebar-pane {
                width: 100%;
                min-height: 200px;
                padding: 26px 22px;
            }

            .content-pane {
                width: 100%;
                padding: 28px 24px 30px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }

            .form-wrapper h1 {
                font-size: 22px;
            }

            .content-pane {
                padding: 22px 16px 24px;
            }
        }
    </style>
</head>

<body>

    <!-- CONTENEUR PRINCIPAL DE LA FENÊTRE (SPLIT-SCREEN) -->
    <main class="app-window">

        <!-- COLONNE GAUCHE (SIDEBAR CFPT SÉNÉGAL-JAPON) -->
        <aside class="sidebar-pane">

            <!-- LOGO OFFICIEL CFPT -->
            <a href="{{ route('welcome') }}" class="brand-logo-wrap" title="Retour au portail d'accueil">
                <img src="{{ file_exists(public_path('images/favicon2.png')) ? asset('images/favicon2.png') : (file_exists(public_path('images/favicon 2.png')) ? asset('images/favicon 2.png') : asset('images/favicon.png')) }}"
                    alt="Logo CFPT Manager" class="brand-logo-img">
                <div class="brand-logo-text">
                    <span class="brand-logo-title">CFPT MANAGER</span>
                    <span class="brand-logo-sub">CFPT Sénégal-Japon</span>
                </div>
            </a>

            <!-- TEXTE D'ACCUEIL & VALEUR AJOUTÉE -->
            <div class="sidebar-copy">
                <span class="sidebar-badge">Récupération de Compte</span>
                <h2>Votre Portail Centralisé d'Accès.</h2>
                <p>Découvrez notre plateforme de gestion dédiée aux candidats, étudiants, formateurs et à
                    l'administration du CFPT Sénégal-Japon.</p>
            </div>

            <!-- LIENS LÉGAUX ET DRAPEAUX SÉNÉGAL & JAPON -->
            <div class="sidebar-footer">
                <div class="sidebar-footer-links">
                    <span>&copy; 2026 CFPT-SJ</span>
                    <a href="{{ route('welcome') }}">Accueil</a>
                    <a href="{{ route('public.postuler') }}">Séminaires</a>
                    <a href="{{ route('welcome') }}#contact">Contact</a>
                </div>

                <div class="flags-container">
                    <!-- Drapeau Sénégal -->
                    <div class="flag-pill" title="Sénégal">
                        <svg width="18" height="12" viewBox="0 0 900 600" style="border-radius: 2px;">
                            <rect width="300" height="600" fill="#00853f" />
                            <rect x="300" width="300" height="600" fill="#fdef42" />
                            <rect x="600" width="300" height="600" fill="#e31b23" />
                            <polygon
                                points="450,210 477,294 566,294 494,346 521,430 450,378 379,430 406,346 334,294 423,294"
                                fill="#00853f" />
                        </svg>
                        <span>Sénégal</span>
                    </div>

                    <!-- Drapeau Japon -->
                    <div class="flag-pill" title="Japon - Coopération JICA">
                        <svg width="18" height="12" viewBox="0 0 900 600"
                            style="border-radius: 2px; border: 1px solid rgba(255,255,255,0.3);">
                            <rect width="900" height="600" fill="#ffffff" />
                            <circle cx="450" cy="300" r="180" fill="#bc002d" />
                        </svg>
                        <span>Japon</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- COLONNE DROITE (FORMULAIRE RÉCUPÉRATION MOT DE PASSE) -->
        <section class="content-pane">

            <!-- BOUTON RETOUR VERS LA PAGE DE CONNEXION -->
            <div class="header-back">
                <a href="{{ route('login') }}" class="btn-back-home" title="Retour à la connexion">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                    <span>Connexion</span>
                </a>
            </div>

            <!-- CORPS DU FORMULAIRE -->
            <div class="form-wrapper">
                <h1>Mot de passe oublié</h1>
                <p class="form-subtitle">Saisissez votre email ci-dessous pour recevoir le lien de réinitialisation
                    sécurisé.</p>

                <!-- NOTIFICATIONS SESSION -->
                @if (session('status'))
                    <div class="alert-box alert-success">
                        <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert-box alert-error">
                        <span class="material-symbols-outlined" style="font-size: 18px;">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- FORMULAIRE -->
                <form action="{{ route('password.email') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <div class="input-wrapper @error('email') error @enderror">
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="ex: nom@domaine.com" required autofocus />
                            <span class="material-symbols-outlined"
                                style="position: absolute; right: 12px; font-size: 19px; color: #94a3b8; pointer-events: none;">mail</span>
                        </div>
                        @error('email')
                            <div class="field-error-msg">
                                <span class="material-symbols-outlined" style="font-size: 14px;">error</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        <span class="material-symbols-outlined" style="font-size: 18px;">send</span>
                        <span>Envoyer le lien de réinitialisation</span>
                    </button>
                </form>

                <div class="return-login-box">
                    <a href="{{ route('login') }}" class="return-login-link">
                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                        <span>Retour à l'écran de connexion</span>
                    </a>
                </div>
            </div>

            <div></div>
        </section>

    </main>

</body>

</html>
