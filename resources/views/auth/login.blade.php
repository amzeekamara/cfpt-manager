<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portail d'Accès — CFPT Manager - CFPT Sénégal-Japon</title>
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
  
  <!-- Polices Google : Plus Jakarta Sans & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

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
      --shadow-modal: 0 25px 60px -12px rgba(22, 37, 54, 0.35), 0 18px 36px -18px rgba(0, 0, 0, 0.2);
      --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      --font-heading: 'Plus Jakarta Sans', var(--font-body);
    }

    *, *::before, *::after {
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
      min-height: 620px;
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
        url('{{ asset("images/centre.png") }}');
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
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
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
      padding: 34px 44px 32px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
    }

    /* Bouton Retour à l'accueil en haut à droite */
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
      margin: 10px auto auto;
    }

    .form-wrapper h1 {
      font-family: var(--font-heading);
      font-size: 26px;
      font-weight: 800;
      color: var(--text-main);
      text-align: center;
      letter-spacing: -0.5px;
      margin-bottom: 20px;
    }

    /* Onglets Connexion / Postuler */
    .account-tabs {
      display: flex;
      justify-content: center;
      position: relative;
      margin-bottom: 26px;
      border-bottom: 1px solid #e2e8f0;
    }

    .tab-btn {
      flex: 1;
      text-align: center;
      background: none;
      border: none;
      padding-bottom: 12px;
      font-size: 14px;
      font-weight: 700;
      color: #94a3b8;
      cursor: pointer;
      position: relative;
      transition: color 0.2s ease;
      font-family: var(--font-heading);
    }

    .tab-btn.active {
      color: var(--primary-blue);
    }

    .tab-btn::after {
      content: "";
      position: absolute;
      bottom: -1px;
      left: 0;
      right: 0;
      height: 2.5px;
      background: transparent;
      transition: background-color 0.25s ease;
    }

    .tab-btn.active::after {
      background-color: var(--primary-blue);
    }

    /* Alertes & Notifications */
    .alert-box {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 12.5px;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 8px;
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

    /* Icône de visibilité de mot de passe */
    .password-indicator-btn {
      position: absolute;
      right: 10px;
      background: none;
      border: none;
      cursor: pointer;
      color: #94a3b8;
      display: flex;
      align-items: center;
      padding: 4px;
      transition: color 0.2s;
    }

    .password-indicator-btn:hover {
      color: var(--primary-blue);
    }

    .form-options-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12px;
      margin-top: 6px;
      margin-bottom: 12px;
    }

    .remember-me-label {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--text-muted);
      cursor: pointer;
    }

    .forgot-link {
      color: var(--primary-blue);
      font-weight: 600;
      text-decoration: none;
    }

    .forgot-link:hover {
      text-decoration: underline;
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
      margin-top: 20px;
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

    /* Séparateur Ou */
    .auth-divider {
      position: relative;
      margin: 18px 0 16px;
      text-align: center;
    }

    .auth-divider::before {
      content: "";
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background-color: #e2e8f0;
      z-index: 1;
    }

    .auth-divider span {
      position: relative;
      background-color: #ffffff;
      padding: 0 10px;
      font-size: 11px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      z-index: 2;
    }

    /* Bouton SSO Google */
    .btn-google-sso {
      width: 100%;
      height: 42px;
      background-color: #ffffff;
      border: 1px solid #dce1e6;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #334155;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .btn-google-sso:hover {
      background-color: #f8fafc;
      border-color: #cbd5e1;
    }

    /* Panneau Candidat (Tab 2) */
    .candidate-info-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 16px;
      margin-bottom: 18px;
    }

    .candidate-info-card h4 {
      font-size: 13.5px;
      font-weight: 700;
      color: var(--primary-blue);
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .candidate-info-card p {
      font-size: 12px;
      color: var(--text-muted);
      line-height: 1.5;
    }

    .candidate-steps-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 12px;
    }

    .candidate-steps-list li {
      font-size: 12px;
      color: var(--text-main);
      display: flex;
      align-items: flex-start;
      gap: 8px;
      line-height: 1.4;
    }

    .step-mini-num {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: var(--primary-blue);
      color: #ffffff;
      font-size: 10px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }

    /* Stepper / Indicateur de progression */
    .stepper-dots {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
      position: relative;
    }

    .stepper-track {
      position: absolute;
      top: 50%;
      left: calc(50% - 36px);
      width: 72px;
      height: 1.5px;
      background-color: #e2e7ec;
      z-index: 1;
      transform: translateY(-50%);
    }

    .dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #ffffff;
      border: 1.5px solid #d2dbe2;
      z-index: 2;
      transition: all 0.25s ease;
    }

    .dot.active {
      background-color: var(--primary-blue);
      border-color: var(--primary-blue);
      box-shadow: 0 0 0 2px rgba(75, 106, 149, 0.25);
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
        font-size: 24px;
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
             alt="Logo CFPT Manager" 
             class="brand-logo-img">
        <div class="brand-logo-text">
          <span class="brand-logo-title">CFPT MANAGER</span>
          <span class="brand-logo-sub">CFPT Sénégal-Japon</span>
        </div>
      </a>

      <!-- TEXTE D'ACCUEIL & VALEUR AJOUTÉE -->
      <div class="sidebar-copy">
        <span class="sidebar-badge">Portail Centralisé</span>
        <h2>L'Excellence Technique Panafricaine.</h2>
        <p>Accédez à votre espace pour le suivi pédagogique, la gestion administrative et le dépôt officiel de candidature.</p>
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
              <rect width="300" height="600" fill="#00853f"/>
              <rect x="300" width="300" height="600" fill="#fdef42"/>
              <rect x="600" width="300" height="600" fill="#e31b23"/>
              <polygon points="450,210 477,294 566,294 494,346 521,430 450,378 379,430 406,346 334,294 423,294" fill="#00853f"/>
            </svg>
            <span>Sénégal</span>
          </div>

          <!-- Drapeau Japon -->
          <div class="flag-pill" title="Japon - Coopération JICA">
            <svg width="18" height="12" viewBox="0 0 900 600" style="border-radius: 2px; border: 1px solid rgba(255,255,255,0.3);">
              <rect width="900" height="600" fill="#ffffff"/>
              <circle cx="450" cy="300" r="180" fill="#bc002d"/>
            </svg>
            <span>Japon</span>
          </div>
        </div>
      </div>
    </aside>

    <!-- COLONNE DROITE (FORMULAIRE CONNEXION / CANDIDATURE) -->
    <section class="content-pane">
      
      <!-- BOUTON RETOUR VERS LA PAGE D'ACCUEIL -->
      <div class="header-back">
        <a href="{{ route('welcome') }}" class="btn-back-home" title="Retour au portail d'accueil">
          <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
          <span>Retour à l'accueil</span>
        </a>
      </div>

      <!-- CORPS DU FORMULAIRE -->
      <div class="form-wrapper">
        <h1 id="form-main-title">Espace Connexion</h1>

        <!-- ONGLETS : SE CONNECTER / POSTULER -->
        <div class="account-tabs">
          <button type="button" class="tab-btn active" id="tab-btn-login" onclick="setAuthTab('login')">
            Se connecter
          </button>
          <button type="button" class="tab-btn" id="tab-btn-register" onclick="setAuthTab('register')">
            Postuler / S'inscrire
          </button>
        </div>

        <!-- NOTIFICATIONS SESSION -->
        @if (session('success'))
          <div class="alert-box alert-success">
            <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        @if (session('error'))
          <div class="alert-box alert-error">
            <span class="material-symbols-outlined" style="font-size: 18px;">error</span>
            <span>{{ session('error') }}</span>
          </div>
        @endif

        <!-- ==================================================================
             ONGLET 1 : FORMULAIRE DE CONNEXION
             ================================================================== -->
        <div id="tab-content-login">
          <form action="{{ route('login') }}" method="POST" autocomplete="on">
            @csrf

            <!-- CHAMP EMAIL -->
            <div class="form-group">
              <label for="login-email">Adresse Email</label>
              <div class="input-wrapper @error('email') error @enderror">
                <input 
                  type="email" 
                  id="login-email" 
                  name="email" 
                  value="{{ old('email') }}" 
                  placeholder="nom@exemple.com" 
                  required 
                  autofocus 
                />
                <span class="material-symbols-outlined" style="position: absolute; right: 12px; font-size: 18px; color: #94a3b8; pointer-events: none;">mail</span>
              </div>
              @error('email')
                <div class="field-error-msg">
                  <span class="material-symbols-outlined" style="font-size: 14px;">info</span>
                  <span>{{ $message }}</span>
                </div>
              @enderror
            </div>

            <!-- CHAMP MOT DE PASSE -->
            <div class="form-group">
              <label for="login-password">Mot de Passe</label>
              <div class="input-wrapper @error('password') error @enderror">
                <input 
                  type="password" 
                  id="login-password" 
                  name="password" 
                  placeholder="Votre mot de passe" 
                  required 
                />
                <button type="button" class="password-indicator-btn" onclick="togglePassword('login-password', 'toggle-pwd-icon')" title="Afficher/Masquer le mot de passe">
                  <span class="material-symbols-outlined" id="toggle-pwd-icon" style="font-size: 18px;">visibility</span>
                </button>
              </div>
              @error('password')
                <div class="field-error-msg">
                  <span class="material-symbols-outlined" style="font-size: 14px;">info</span>
                  <span>{{ $message }}</span>
                </div>
              @enderror
            </div>

            <!-- OPTIONS REMEMBER ME & FORGOT -->
            <div class="form-options-row">
              <label class="remember-me-label">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="accent-color: var(--primary-blue); cursor: pointer;" />
                <span>Se souvenir de moi</span>
              </label>

              <a href="{{ route('password.request') }}" class="forgot-link">
                Mot de passe oublié ?
              </a>
            </div>

            <!-- BOUTON DE CONNEXION -->
            <button type="submit" class="btn-submit">
              <span>Se connecter</span>
              <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
            </button>
          </form>

          <!-- SÉPARATEUR GOOGLE SSO -->
          <div class="auth-divider">
            <span>Ou continuer avec</span>
          </div>

          <a href="{{ route('auth.google') }}" class="btn-google-sso" title="Se connecter avec un compte Google">
            <svg width="18" height="18" viewBox="0 0 24 24">
              <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
              <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
              <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
              <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            <span>Compte Google Institutionnel</span>
          </a>
        </div>

        <!-- ==================================================================
             ONGLET 2 : POSTULER / CANDIDATURE RAPIDE
             ================================================================== -->
        <div id="tab-content-register" style="display: none;">
          <div class="candidate-info-card">
            <h4>
              <span class="material-symbols-outlined" style="font-size: 18px; color: var(--primary-blue);">school</span>
              Sélection & Bourse JICA / Sénégal
            </h4>
            <p>
              Pour postuler aux séminaires internationaux d'excellence technique, suivez le circuit officiel :
            </p>
            <ul class="candidate-steps-list">
              <li>
                <span class="step-mini-num">1</span>
                <span><strong>Enregistrement en ligne :</strong> Choisissez votre spécialité et déposez votre dossier numérique.</span>
              </li>
              <li>
                <span class="step-mini-num">2</span>
                <span><strong>Validation Ministère :</strong> Endossement par votre Ministère de tutelle.</span>
              </li>
              <li>
                <span class="step-mini-num">3</span>
                <span><strong>Dépôt JICA :</strong> Dépôt officiel à l'Ambassade / Bureau JICA local.</span>
              </li>
            </ul>
          </div>

          <!-- BOUTON D'ACCÈS DIRECT AU FORMULAIRE DE CANDIDATURE -->
          <a href="{{ route('public.postuler') }}" class="btn-submit" style="margin-top: 12px;">
            <span>Voir les Séminaires & Postuler</span>
            <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
          </a>

          <div style="text-align: center; margin-top: 16px;">
            <a href="javascript:void(0)" onclick="setAuthTab('login')" class="forgot-link" style="font-size: 12.5px;">
              &larr; Déjà un compte ? Retour à la connexion
            </a>
          </div>
        </div>

        <!-- INDICATEUR D'ÉTAPE (STEPPER PAGINATION) -->
        <div class="stepper-dots">
          <div class="stepper-track"></div>
          <div class="dot active" id="dot-1" title="Étape 1 : Authentification / Candidature"></div>
          <div class="dot" id="dot-2" title="Étape 2 : Dossier Technique"></div>
          <div class="dot" id="dot-3" title="Étape 3 : Validation Ministérielle"></div>
          <div class="dot" id="dot-4" title="Étape 4 : Sélection Finale JICA"></div>
        </div>

      </div>

    </section>

  </main>

  <!-- LOGIQUE JAVASCRIPT -->
  <script>
    // Bascule de visibilité des mots de passe
    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (!input || !icon) return;
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerText = 'visibility_off';
      } else {
        input.type = 'password';
        icon.innerText = 'visibility';
      }
    }

    // Gestion des onglets Se connecter / Postuler
    function setAuthTab(tab) {
      const tabLogin = document.getElementById('tab-btn-login');
      const tabRegister = document.getElementById('tab-btn-register');
      const contentLogin = document.getElementById('tab-content-login');
      const contentRegister = document.getElementById('tab-content-register');
      const mainTitle = document.getElementById('form-main-title');
      const dot1 = document.getElementById('dot-1');
      const dot2 = document.getElementById('dot-2');

      if (tab === 'register') {
        tabLogin.classList.remove('active');
        tabRegister.classList.add('active');
        contentLogin.style.display = 'none';
        contentRegister.style.display = 'block';
        mainTitle.innerText = 'Espace Candidature';
        if (dot2) {
          dot1.classList.remove('active');
          dot2.classList.add('active');
        }
      } else {
        tabRegister.classList.remove('active');
        tabLogin.classList.add('active');
        contentRegister.style.display = 'none';
        contentLogin.style.display = 'block';
        mainTitle.innerText = 'Espace Connexion';
        if (dot1) {
          dot2.classList.remove('active');
          dot1.classList.add('active');
        }
      }
    }

    function toggleAuthTab() {
      const isLoginActive = document.getElementById('tab-btn-login').classList.contains('active');
      setAuthTab(isLoginActive ? 'register' : 'login');
    }

    // Vérifier l'URL ou paramètre ?tab=register à l'ouverture
    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const tabParam = urlParams.get('tab');
      if (tabParam === 'register' || tabParam === 'postuler' || window.location.hash === '#postuler') {
        setAuthTab('register');
      }
    });
  </script>
</body>
</html>