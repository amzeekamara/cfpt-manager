<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Programme d'Excellence CFPT Sénégal-Japon & JICA — Portail Officiel</title>
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
  
  <!-- Typographie Google Fonts : Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    /* ==========================================================================
       VARIABLES CSS & DESIGN SYSTEM (CHARTE BLEUE OFFICIELLE PFPT : #4B6A95)
       ========================================================================== */
    :root {
      --primary-dark: #4B6A95;       /* Bleu principal officiel PFPT */
      --primary-card: #354e70;       /* Bleu acier foncé */
      --primary-hover: #263a54;      /* Bleu nuit soutenu */
      --primary-deep: #162536;       /* Fond sombre institutionnel */
      --accent-blue: #4B6A95;
      --accent-light: #d2e4ff;       /* Bleu ciel pastel */
      --accent-bg: #f3f6fb;          /* Fond très doux */
      --accent-tag: #e4edf8;         /* Badges et tags */
      --text-dark: #0f172a;
      --text-muted: #526071;
      --text-light: #8da0b6;
      --bg-white: #ffffff;
      --bg-light: #f8fafc;
      --border-color: #e2e8f0;
      --border-subtle: #edf2f7;
      --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
      --shadow-md: 0 10px 25px -5px rgba(75, 106, 149, 0.12);
      --shadow-lg: 0 20px 35px -10px rgba(75, 106, 149, 0.20);
      --radius-sm: 8px;
      --radius-md: 16px;
      --radius-lg: 24px;
      --radius-xl: 32px;
      --radius-full: 9999px;
      --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-main);
      color: var(--text-dark);
      background-color: var(--bg-white);
      line-height: 1.55;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    a {
      color: inherit;
      text-decoration: none;
      transition: all 0.25s ease;
    }

    button {
      font-family: inherit;
      cursor: pointer;
      border: none;
      outline: none;
    }

    .container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    /* En-têtes de sections épurés (Texte pur sans fond ni boîte) */
    .section-eyebrow {
      display: block;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 1.4px;
      text-transform: uppercase;
      color: var(--primary-dark);
      margin-bottom: 12px;
      background: transparent;
      padding: 0;
    }

    /* Boutons */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 11px 22px;
      font-size: 13px;
      font-weight: 700;
      border-radius: var(--radius-full);
      transition: all 0.25s ease;
      cursor: pointer;
      text-decoration: none;
    }

    .btn-primary {
      background-color: var(--primary-dark);
      color: #ffffff;
    }

    .btn-primary:hover {
      background-color: var(--primary-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 14px rgba(75, 106, 149, 0.30);
    }

    .btn-outline {
      background: transparent;
      color: var(--text-dark);
      border: 1px solid var(--border-color);
    }

    .btn-outline:hover {
      background: var(--bg-light);
      border-color: #cbd5e1;
    }

    .btn-light {
      background: #ffffff;
      color: var(--primary-dark);
      box-shadow: var(--shadow-sm);
    }

    .btn-light:hover {
      background: #f1f5f9;
    }

    /* ==========================================================================
       TOP ANNOUNCEMENT BAR
       ========================================================================== */
    .top-banner {
      background-color: var(--accent-tag);
      color: var(--primary-dark);
      text-align: center;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 600;
      border-bottom: 1px solid rgba(75, 106, 149, 0.1);
    }

    .top-banner a {
      font-weight: 800;
      text-decoration: underline;
      margin-left: 6px;
    }

    /* ==========================================================================
       HEADER & NAVIGATION ÉPURÉE
       ========================================================================== */
    .site-header {
      background: #ffffff;
      padding: 14px 0;
      position: sticky;
      top: 0;
      z-index: 100;
      border-bottom: 1px solid var(--border-subtle);
      box-shadow: var(--shadow-sm);
    }

    .header-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }

    .brand-logo {
      display: flex;
      align-items: center;
      text-decoration: none;
    }

    .brand-logo-img {
      height: 48px;
      width: auto;
      max-width: 170px;
      object-fit: contain;
      display: block;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 28px;
      list-style: none;
    }

    .nav-links a {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-muted);
      transition: color 0.2s ease;
    }

    .nav-links a:hover {
      color: var(--primary-dark);
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .btn-login {
      font-size: 13px;
      font-weight: 700;
      color: var(--text-dark);
      padding: 8px 12px;
    }

    .btn-login:hover {
      color: var(--primary-dark);
    }

    .mobile-menu-btn {
      display: none;
      background: transparent;
      padding: 8px;
      border-radius: var(--radius-sm);
      color: var(--text-dark);
    }

    /* ==========================================================================
       1. HERO SECTION
       ========================================================================== */
    .hero-section {
      padding: 60px 0 50px;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1.15fr 0.85fr;
      align-items: center;
      gap: 50px;
    }

    .hero-content h1 {
      font-size: 46px;
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -1.2px;
      color: var(--primary-dark);
      margin-bottom: 20px;
    }

    .hero-content p {
      font-size: 16px;
      color: var(--text-muted);
      max-width: 500px;
      margin-bottom: 32px;
      line-height: 1.6;
    }

    .hero-cta {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-wrap: wrap;
    }

    .hero-visual {
      position: relative;
    }

    .hero-img-box {
      width: 100%;
      height: 430px;
      border-radius: var(--radius-xl);
      overflow: hidden;
      box-shadow: var(--shadow-md);
      position: relative;
      background-color: var(--primary-deep);
    }

    .hero-img-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hero-floating-image-box {
      position: absolute;
      bottom: -28px;
      left: -32px;
      width: 320px;
      height: 200px;
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-lg);
      border: 4px solid #ffffff;
      background-color: var(--primary-deep);
      transition: transform 0.3s ease;
    }

    .hero-floating-image-box:hover {
      transform: scale(1.02);
    }

    .hero-floating-image-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .hero-floating-image-badge {
      position: absolute;
      bottom: 8px;
      left: 8px;
      right: 8px;
      background: rgba(17, 29, 43, 0.88);
      backdrop-filter: blur(6px);
      color: #ffffff;
      padding: 6px 12px;
      border-radius: var(--radius-sm);
      font-size: 11px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Widget de Sélection & Liste Déroulante des Séminaires */
    .hero-seminar-picker-widget {
      position: absolute;
      bottom: -32px;
      right: -16px;
      left: -16px;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(16px);
      border: 2px solid #e2e8f0;
      border-radius: var(--radius-xl);
      padding: 16px 20px;
      box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.18);
      z-index: 25;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .hero-seminar-picker-widget:hover {
      box-shadow: 0 24px 50px -10px rgba(75, 106, 149, 0.25);
      border-color: #cbd5e1;
    }

    .hero-picker-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px;
    }

    .hero-picker-title {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--primary-dark);
    }

    .hero-picker-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 0;
      background: transparent;
      color: #059669;
      font-size: 11px;
      font-weight: 700;
      border: none;
    }

    .hero-picker-select-wrapper {
      position: relative;
    }

    .hero-picker-select {
      width: 100%;
      padding: 12px 42px 12px 14px;
      background-color: #f8fafc;
      border: 1.5px solid #cbd5e1;
      border-radius: var(--radius-md);
      font-size: 13px;
      font-weight: 600;
      color: var(--text-dark);
      outline: none;
      transition: all 0.2s ease;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
    }

    .hero-picker-select:hover {
      background-color: #ffffff;
      border-color: var(--primary-dark);
    }

    .hero-picker-select:focus {
      background-color: #ffffff;
      border-color: var(--primary-dark);
      box-shadow: 0 0 0 3px rgba(75, 106, 149, 0.15);
    }

    .hero-picker-select-icon {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: var(--primary-dark);
    }

    .hero-picker-dropdown-menu {
      position: absolute;
      bottom: calc(100% + 8px);
      left: 0;
      right: 0;
      background: #ffffff;
      border: 1.5px solid #cbd5e1;
      border-radius: var(--radius-lg);
      padding: 8px;
      box-shadow: 0 -12px 30px rgba(15, 23, 42, 0.15);
      max-height: 280px;
      overflow-y: auto;
      z-index: 50;
    }

    .hero-picker-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
      padding: 10px 12px;
      border-radius: var(--radius-sm);
      text-decoration: none;
      color: var(--text-dark);
      transition: all 0.15s ease;
      border-bottom: 1px solid #f1f5f9;
    }

    .hero-picker-item:last-child {
      border-bottom: none;
    }

    .hero-picker-item:hover {
      background-color: #f1f5f9;
      transform: translateX(3px);
    }

    .hero-picker-item-title {
      font-size: 13px;
      font-weight: 700;
      color: #0f172a;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .hero-picker-item-meta {
      font-size: 11px;
      color: #64748b;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    @media (max-width: 991px) {
      .hero-grid {
        grid-template-columns: 1fr;
        gap: 40px;
      }
      .hero-seminar-picker-widget {
        position: relative;
        bottom: 0;
        right: 0;
        left: 0;
        margin-top: 16px;
      }
    }

    /* ==========================================================================
       2. SECTION 3 CADRES PARTENAIRES INDIVIDUELS
       ========================================================================== */
    .partners-grid-section {
      padding: 20px 0 35px;
    }

    .partners-title-label {
      text-align: center;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 1.4px;
      text-transform: uppercase;
      color: var(--primary-dark);
      margin-bottom: 20px;
    }

    .partners-three-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 32px;
    }

    .partner-card-box {
      background: transparent;
      border: none;
      border-radius: 0;
      padding: 10px 12px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: none;
    }

    .partner-card-box:hover {
      transform: none;
      box-shadow: none;
    }

    .partner-card-img {
      height: 52px;
      width: auto;
      max-width: 90px;
      object-fit: contain;
      flex-shrink: 0;
    }

    .partner-card-info h4 {
      font-size: 15px;
      font-weight: 800;
      color: var(--primary-dark);
      line-height: 1.25;
      margin-bottom: 3px;
    }

    .partner-card-info p {
      font-size: 12px;
      font-weight: 600;
      color: var(--text-muted);
      line-height: 1.35;
    }

    /* ==========================================================================
       3. STATS BANNER
       ========================================================================== */
    .stats-section {
      padding: 10px 0 60px;
    }

    .stats-card {
      background-color: var(--primary-dark);
      color: #fff;
      border-radius: var(--radius-xl);
      padding: 46px 60px;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      position: relative;
      overflow: hidden;
    }

    .stats-card::before, .stats-card::after {
      content: "";
      position: absolute;
      border-radius: 50%;
      border: 1px solid rgba(255, 255, 255, 0.12);
      pointer-events: none;
    }

    .stats-card::before { right: -60px; top: -60px; width: 300px; height: 300px; }
    .stats-card::after { right: -10px; top: -10px; width: 180px; height: 180px; }

    .stat-item h3 {
      font-size: 38px;
      font-weight: 800;
      letter-spacing: -1px;
      margin-bottom: 6px;
    }

    .stat-item p {
      font-size: 14px;
      color: rgba(255, 255, 255, 0.85);
    }

    /* ==========================================================================
       4. ACTEURS DU PROGRAMME
       ========================================================================== */
    .actors-section {
      padding: 80px 0;
      background-color: #fafbfc;
    }

    .actors-grid {
      display: grid;
      grid-template-columns: 1fr 1.3fr;
      gap: 60px;
      align-items: center;
    }

    .actors-text h2 {
      font-size: 38px;
      font-weight: 800;
      color: var(--primary-dark);
      letter-spacing: -1px;
      line-height: 1.2;
      margin-bottom: 16px;
    }

    .actors-text p {
      font-size: 15px;
      color: var(--text-muted);
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .kaizen-intro-box {
      background: #ffffff;
      border-left: 4px solid var(--primary-dark);
      padding: 14px 18px;
      border-radius: 0 var(--radius-md) var(--radius-md) 0;
      margin-bottom: 24px;
      box-shadow: var(--shadow-sm);
      border-top: 1px solid var(--border-subtle);
      border-right: 1px solid var(--border-subtle);
      border-bottom: 1px solid var(--border-subtle);
    }

    .kaizen-intro-box p {
      font-size: 13px;
      color: var(--text-dark);
      line-height: 1.55;
      margin: 0;
      font-weight: 600;
    }

    .features-2x2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }

    .actor-box {
      display: flex;
      flex-direction: column;
      gap: 10px;
      background: #ffffff;
      padding: 24px;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-subtle);
      box-shadow: var(--shadow-sm);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .actor-box:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .actor-logo-wrap {
      height: 48px;
      display: flex;
      align-items: center;
      margin-bottom: 4px;
    }

    .actor-logo-img {
      height: 44px;
      width: auto;
      max-width: 110px;
      object-fit: contain;
      display: block;
    }

    .actor-box h4 {
      font-size: 16px;
      font-weight: 800;
      color: var(--text-dark);
    }

    .actor-box p {
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.55;
    }

    /* ==========================================================================
       5. LES 3 DÉPARTEMENTS TECHNIQUES AVEC PHOTOS RÉELLES & IMPACT AFRIQUE
       ========================================================================== */
    .departments-section {
      padding: 85px 0;
      background-color: #ffffff;
    }

    .departments-header {
      text-align: center;
      max-width: 780px;
      margin: 0 auto 50px;
    }

    .departments-header h2 {
      font-size: 36px;
      font-weight: 800;
      color: var(--primary-dark);
      letter-spacing: -1px;
      line-height: 1.25;
      margin-bottom: 16px;
    }

    .departments-header p {
      font-size: 15px;
      color: var(--text-muted);
      line-height: 1.65;
    }

    .departments-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
    }

    .dept-card {
      background: #ffffff;
      border: 1px solid var(--border-subtle);
      border-radius: var(--radius-xl);
      padding: 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: none;
      position: relative;
      overflow: hidden;
    }

    .dept-card:hover {
      transform: none;
      box-shadow: none;
      border-color: var(--border-subtle);
    }

    .dept-img-wrap {
      width: 100%;
      height: 190px;
      border-radius: var(--radius-lg);
      overflow: hidden;
      margin-bottom: 20px;
      background-color: var(--primary-deep);
      box-shadow: none;
      position: relative;
    }

    .dept-card-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .dept-card:hover .dept-card-img {
      transform: none;
    }

    .dept-card-top {
      margin-bottom: 20px;
    }

    .dept-badge-label {
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--primary-card);
      margin-bottom: 6px;
      display: block;
    }

    .dept-card h3 {
      font-size: 20px;
      font-weight: 800;
      color: var(--text-dark);
      line-height: 1.3;
      margin-bottom: 10px;
    }

    .dept-card p.dept-desc {
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.55;
      margin-bottom: 18px;
    }

    .dept-impact-box {
      background: #f8fafc;
      border-radius: var(--radius-md);
      padding: 16px 18px;
      border: none;
    }

    .dept-impact-title {
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--primary-dark);
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 8px;
    }

    .dept-impact-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .dept-impact-list li {
      font-size: 12px;
      color: var(--text-muted);
      line-height: 1.45;
      display: flex;
      align-items: flex-start;
      gap: 6px;
    }

    .dept-impact-list li::before {
      content: "—";
      color: var(--primary-dark);
      font-weight: bold;
      font-size: 13px;
      line-height: 1;
    }

    /* ==========================================================================
       6. DÉROULEMENT & MÉTHODOLOGIE 5S KAIZEN (GRAND FORMAT)
       ========================================================================== */
    .feature-section {
      padding: 85px 0;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      gap: 60px;
    }

    .feature-text h2 {
      font-size: 36px;
      font-weight: 800;
      color: var(--primary-dark);
      line-height: 1.2;
      letter-spacing: -0.8px;
      margin-bottom: 16px;
    }

    .feature-text p {
      font-size: 15px;
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .kaizen-points-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: 20px;
    }

    .kaizen-points-list li {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      font-weight: 700;
      color: var(--primary-dark);
    }

    .kaizen-points-list li svg {
      color: var(--primary-dark);
      flex-shrink: 0;
    }

    .kaizen-visual-wrap {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .kaizen-img-card {
      width: 100%;
      background: #ffffff;
      border-radius: var(--radius-xl);
      padding: 16px;
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--border-color);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .kaizen-img-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 24px 45px -10px rgba(75, 106, 149, 0.22);
    }

    .kaizen-img-fluid {
      width: 100%;
      height: auto;
      max-height: 380px;
      border-radius: var(--radius-lg);
      display: block;
      object-fit: contain;
      background: #ffffff;
    }

    /* ==========================================================================
       7. PARCOURS OFFICIEL & BOURSES D'EXCELLENCE (DESIGN UNIFIÉ 2 COLONNES)
       ========================================================================== */
    .modalities-section {
      padding: 85px 0;
      background-color: var(--accent-bg);
      border-top: 1px solid var(--border-subtle);
    }

    .section-header-center {
      max-width: 760px;
      margin: 0 auto 48px;
      text-align: center;
    }

    .section-header-center h2 {
      font-size: 36px;
      font-weight: 800;
      color: var(--primary-dark);
      letter-spacing: -1px;
      margin-bottom: 12px;
    }

    .section-header-center p {
      font-size: 15px;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .modalities-grid {
      display: grid;
      grid-template-columns: 1.05fr 0.95fr;
      gap: 28px;
      align-items: stretch;
    }

    .modality-box {
      background: #ffffff;
      border: 1px solid var(--border-color);
      border-radius: var(--radius-xl);
      padding: 36px 32px;
      box-shadow: var(--shadow-sm);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .modality-box:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }

    .modality-header {
      margin-bottom: 20px;
    }

    .modality-tag {
      display: inline-block;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--primary-card);
      margin-bottom: 6px;
    }

    .modality-header h3 {
      font-size: 22px;
      font-weight: 800;
      color: var(--text-dark);
      line-height: 1.3;
    }

    .modality-header p {
      font-size: 13.5px;
      color: var(--text-muted);
      margin-top: 6px;
      line-height: 1.5;
    }

    /* Stepper Timeline pour la sélection */
    .timeline-steps {
      display: flex;
      flex-direction: column;
      gap: 16px;
      position: relative;
      margin: 18px 0 20px;
    }

    .timeline-steps::before {
      content: "";
      position: absolute;
      left: 17px;
      top: 14px;
      bottom: 20px;
      width: 2px;
      background: var(--accent-tag);
      z-index: 0;
    }

    .timeline-step-item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      position: relative;
      z-index: 1;
    }

    .step-circle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #ffffff;
      border: 2px solid var(--primary-dark);
      color: var(--primary-dark);
      font-size: 13px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: var(--shadow-sm);
    }

    .step-details h4 {
      font-size: 14.5px;
      font-weight: 800;
      color: var(--text-dark);
      margin-bottom: 2px;
    }

    .step-details p {
      font-size: 12.5px;
      color: var(--text-muted);
      line-height: 1.45;
    }

    /* Notice officielle intégrée */
    .modalities-official-notice {
      background: var(--accent-bg);
      border-radius: var(--radius-md);
      padding: 14px 16px;
      border: 1px solid var(--border-subtle);
      font-size: 12px;
      color: var(--text-dark);
      line-height: 1.5;
      display: flex;
      gap: 10px;
      align-items: flex-start;
      margin-top: 10px;
    }

    .modalities-official-notice svg {
      color: var(--primary-dark);
      flex-shrink: 0;
      margin-top: 2px;
    }

    /* Carte des Bourses & Prise en charge */
    .scholarship-badge {
      display: inline-block;
      background: transparent;
      border: none;
      color: var(--primary-dark);
      padding: 0;
      font-size: 13.5px;
      font-weight: 800;
      margin-bottom: 18px;
    }

    .scholarship-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-bottom: 26px;
    }

    .scholarship-list li {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.45;
    }

    .scholarship-list li svg {
      color: var(--primary-dark);
      flex-shrink: 0;
      margin-top: 3px;
    }

    .scholarship-list li strong {
      color: var(--text-dark);
      display: block;
      font-size: 13.5px;
      margin-bottom: 2px;
    }

    /* ==========================================================================
       8. FAQ SECTION (ACCORDION)
       ========================================================================== */
    .faq-section {
      padding: 80px 0;
    }

    .faq-container {
      max-width: 760px;
      margin: 0 auto;
    }

    .faq-container h2 {
      font-size: 34px;
      font-weight: 800;
      text-align: center;
      color: var(--primary-dark);
      margin-bottom: 40px;
    }

    .faq-item {
      border-bottom: 1px solid var(--border-subtle);
      padding: 20px 0;
    }

    .faq-question {
      width: 100%;
      background: none;
      display: flex;
      justify-content: space-between;
      align-items: center;
      text-align: left;
      font-size: 16px;
      font-weight: 700;
      color: var(--text-dark);
      cursor: pointer;
    }

    .faq-question:hover {
      color: var(--primary-dark);
    }

    .faq-icon {
      font-size: 22px;
      font-weight: 400;
      transition: transform 0.25s ease;
      color: var(--text-muted);
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease, padding-top 0.3s ease;
      font-size: 14px;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .faq-item.active .faq-answer {
      max-height: 220px;
      padding-top: 12px;
    }

    .faq-item.active .faq-icon {
      transform: rotate(45deg);
    }

    /* ==========================================================================
       9. ACTUALITÉS & SÉMINAIRES EN COURS (AVANT CONTACT)
       ========================================================================== */
    .news-section {
      padding: 85px 0;
      background-color: var(--accent-bg);
      border-top: 1px solid var(--border-subtle);
    }

    .seminars-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
      gap: 28px;
      margin-top: 32px;
    }

    .seminar-card {
      background: #ffffff;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-color);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .seminar-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-md);
    }

    .seminar-card-img {
      height: 170px;
      width: 100%;
      background-size: cover;
      background-position: center;
      position: relative;
      background-color: var(--primary-deep);
    }

    .seminar-card-img::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(22, 37, 54, 0.8) 0%, rgba(22, 37, 54, 0.2) 60%, transparent 100%);
    }

    .seminar-tag-top {
      position: absolute;
      top: 14px;
      left: 14px;
      z-index: 2;
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(4px);
      color: var(--primary-dark);
      font-size: 11px;
      font-weight: 800;
      padding: 4px 10px;
      border-radius: var(--radius-sm);
    }

    .seminar-status-bottom {
      position: absolute;
      bottom: 12px;
      left: 14px;
      z-index: 2;
      background: var(--primary-dark);
      color: #ffffff;
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 3px 8px;
      border-radius: var(--radius-sm);
    }

    .seminar-body {
      padding: 22px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .seminar-dept {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--primary-card);
      margin-bottom: 6px;
    }

    .seminar-title {
      font-size: 16px;
      font-weight: 800;
      color: var(--text-dark);
      line-height: 1.35;
      margin-bottom: 8px;
    }

    .seminar-desc {
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.5;
      margin-bottom: 16px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .seminar-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-top: 1px solid var(--border-subtle);
      border-bottom: 1px solid var(--border-subtle);
      font-size: 12px;
      color: var(--text-muted);
      margin-bottom: 14px;
    }

    .seminar-progress {
      margin-bottom: 16px;
    }

    .progress-bar-bg {
      width: 100%;
      height: 6px;
      background: var(--bg-light);
      border-radius: var(--radius-full);
      overflow: hidden;
      margin-top: 4px;
    }

    .progress-bar-fill {
      height: 100%;
      background: var(--primary-dark);
      border-radius: var(--radius-full);
    }

    .topic-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-bottom: 16px;
    }

    .topic-pill {
      font-size: 11px;
      font-weight: 600;
      background: var(--accent-tag);
      color: var(--primary-dark);
      padding: 3px 8px;
      border-radius: var(--radius-sm);
    }

    /* ==========================================================================
       10. CONTACT & LOCALISATION (MISE EN FORME INTÉGRÉE & ÉPURÉE)
       ========================================================================== */
    .contact-section {
      padding: 85px 0;
      background: #ffffff;
    }

    .contact-split-container {
      display: grid;
      grid-template-columns: 400px 1fr;
      background: #ffffff;
      border: 1px solid var(--border-color);
      border-radius: var(--radius-xl);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
    }

    .contact-info-panel {
      padding: 38px 32px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background: #ffffff;
      border-right: 1px solid var(--border-subtle);
    }

    .contact-info-header h3 {
      font-size: 20px;
      font-weight: 800;
      color: var(--primary-dark);
      margin-bottom: 6px;
      letter-spacing: -0.3px;
    }

    .contact-info-header p {
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.5;
    }

    .contact-channels-list {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin: 26px 0;
    }

    .contact-channel-item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
    }

    .channel-icon-wrap {
      width: 40px;
      height: 40px;
      border-radius: var(--radius-md);
      background: var(--accent-bg);
      color: var(--primary-dark);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      border: 1px solid var(--border-subtle);
    }

    .channel-content {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .channel-label {
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--text-light);
    }

    .channel-content strong {
      font-size: 13.5px;
      font-weight: 700;
      color: var(--text-dark);
    }

    .channel-content strong a {
      color: var(--text-dark);
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .channel-content strong a:hover {
      color: var(--primary-dark);
    }

    .channel-sub {
      font-size: 12px;
      color: var(--text-muted);
    }

    .contact-panel-actions {
      margin-top: auto;
      padding-top: 18px;
      border-top: 1px solid var(--border-subtle);
    }

    .contact-map-panel {
      position: relative;
      width: 100%;
      min-height: 440px;
      background: var(--bg-light);
    }

    .contact-map-panel iframe {
      width: 100%;
      height: 100%;
      min-height: 440px;
      border: 0;
      display: block;
    }

    .map-overlay-badge {
      position: absolute;
      bottom: 18px;
      left: 18px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 9px 14px;
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-md);
      border: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      gap: 10px;
      z-index: 2;
    }

    .map-badge-icon {
      width: 30px;
      height: 30px;
      border-radius: var(--radius-sm);
      background: var(--accent-tag);
      color: var(--primary-dark);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .map-overlay-badge strong {
      font-size: 12px;
      color: var(--primary-dark);
      display: block;
      line-height: 1.2;
    }

    .map-overlay-badge span {
      font-size: 10.5px;
      color: var(--text-muted);
    }

    @media (max-width: 900px) {
      .contact-split-container {
        grid-template-columns: 1fr;
      }
      .contact-info-panel {
        border-right: none;
        border-bottom: 1px solid var(--border-subtle);
        padding: 28px 20px;
      }
      .contact-map-panel iframe {
        min-height: 320px;
      }
    }

    /* ==========================================================================
       11. CTA BANNER FINAL
       ========================================================================== */
    .cta-section {
      padding: 60px 0 90px;
    }

    .cta-card {
      background: var(--accent-bg);
      border-radius: var(--radius-xl);
      padding: 56px 64px;
      display: grid;
      grid-template-columns: 1.2fr 0.8fr;
      gap: 40px;
      align-items: center;
      position: relative;
      overflow: hidden;
      border: 1px solid var(--accent-tag);
    }

    .cta-card h2 {
      font-size: 38px;
      font-weight: 800;
      color: var(--primary-dark);
      line-height: 1.2;
      margin-bottom: 16px;
    }

    .cta-card p {
      font-size: 15px;
      color: var(--text-muted);
      margin-bottom: 28px;
      line-height: 1.6;
    }

    .cta-buttons {
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
    }

    /* ==========================================================================
       12. FOOTER
       ========================================================================== */
    .site-footer {
      background-color: #111d2b;
      color: #ffffff;
      padding: 70px 0 30px;
    }

    .footer-top {
      display: grid;
      grid-template-columns: 1.2fr 1fr 1fr 1.3fr;
      gap: 36px;
      margin-bottom: 50px;
    }

    .footer-col h5 {
      font-size: 13px;
      font-weight: 700;
      color: var(--accent-light);
      margin-bottom: 18px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .footer-col ul {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .footer-col ul a {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.7);
    }

    .footer-col ul a:hover {
      color: #fff;
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      padding-top: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.5);
    }

    .footer-bottom-links {
      display: flex;
      gap: 20px;
    }

    /* ==========================================================================
       RESPONSIVE DESIGN
       ========================================================================== */
    @media (max-width: 992px) {
      .hero-grid, .feature-grid, .actors-grid, .training-cards, .contact-grid, .departments-grid, .modalities-grid {
        grid-template-columns: 1fr;
        gap: 40px;
      }
      .partners-three-grid {
        grid-template-columns: 1fr;
      }
      .train-card-specialized {
        grid-template-columns: 1fr;
      }
      .program-features-list {
        border-left: none;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        padding-left: 0;
        padding-top: 24px;
      }
      .footer-top {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width: 768px) {
      .hero-content h1 { font-size: 32px; }
      .nav-links { display: none; }
      .mobile-menu-btn { display: inline-flex; }
      .stats-card { grid-template-columns: 1fr; gap: 20px; padding: 32px 24px; }
      .hero-floating-image-box { position: static; width: 100%; height: 220px; margin-top: 20px; }
      .features-2x2 { grid-template-columns: 1fr; }
      .cta-card { padding: 32px 20px; }
      .footer-top { grid-template-columns: 1fr; }
      .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
    }
  </style>
</head>
<body x-data="{ mobileMenu: false }">

  <!-- NAVIGATION HEADER ÉPURÉE -->
  <header class="site-header">
    <div class="container header-inner">
      <!-- Logo Officiel Bleu sans arrière-plan -->
      <a href="{{ route('welcome') }}" class="brand-logo">
        <img src="{{ asset('images/logo-blue.png') }}" 
             alt="Logo PFPT - CFPT Sénégal-Japon & JICA" 
             class="brand-logo-img"
             onerror="this.src='{{ asset('images/favicon.png') }}'">
      </a>

      <!-- Menu Liens Essentiels -->
      <ul class="nav-links">
        <li><a href="#accueil">Accueil</a></li>
        <li><a href="#acteurs">Les Acteurs</a></li>
        <li><a href="#departements">Départements & Pôles</a></li>
        <li><a href="#actualites">Actualités</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>

      <!-- Action Buttons -->
      <div class="nav-actions">
        @auth
          @php
              $user = auth()->user();
              $dashUrl = route('admin.dashboard');
              if (session()->has('portal_trainer_id')) {
                  $dashUrl = route('portal.trainer.dashboard');
              } elseif (session()->has('portal_candidate_id') || ($user->role && $user->role->name === 'Candidat')) {
                  $dashUrl = route('portal.candidate.dashboard');
              }
          @endphp
          <a href="{{ $dashUrl }}" class="btn btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            <span>Mon Espace</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="btn-login">Espace Connexion</a>
          <a href="{{ route('public.postuler') }}" class="btn btn-primary">Candidater</a>
        @endauth

        <!-- Mobile Menu Toggle Button -->
        <button @click="mobileMenu = !mobileMenu" class="mobile-menu-btn" aria-label="Menu Mobile">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu Drawer -->
    <div x-show="mobileMenu" x-cloak style="background: #ffffff; border-bottom: 1px solid var(--border-color); padding: 16px 24px;">
      <ul style="list-style: none; display: flex; flex-direction: column; gap: 12px; font-weight: 700; font-size: 14px;">
        <li><a href="#accueil" @click="mobileMenu = false">Accueil</a></li>
        <li><a href="#acteurs" @click="mobileMenu = false">Les Acteurs</a></li>
        <li><a href="#departements" @click="mobileMenu = false">Départements & Pôles</a></li>
        <li><a href="#actualites" @click="mobileMenu = false">Actualités & Séminaires</a></li>
        <li><a href="#contact" @click="mobileMenu = false">Contact</a></li>
        <li style="border-top: 1px solid var(--border-subtle); padding-top: 10px; display: flex; gap: 10px;">
          <a href="{{ route('login') }}" class="btn btn-outline" style="flex: 1;">Connexion</a>
          <a href="{{ route('public.postuler') }}" class="btn btn-primary" style="flex: 1;">Candidater</a>
        </li>
      </ul>
    </div>
  </header>

  <!-- 1. HERO SECTION -->
  <section class="hero-section" id="accueil">
    <div class="container hero-grid">
      <div class="hero-content">
        <!-- En-tête sobre sans cadre ni fond coloré -->
        <div class="section-eyebrow">
          Programme d'Excellence Technique (PFPT)
        </div>
        <h1>Formez-vous aux standards industriels du Japon à Dakar</h1>
        <p>Le Programme de Formation en Pays Tiers (PFPT) au CFPT Sénégal-Japon, soutenu par la JICA, prépare l'élite des techniciens, formateurs et ingénieurs de l'industrie sénégalaise et d'Afrique subsaharienne.</p>
        
        <div class="hero-cta">
          <a href="{{ route('public.postuler') }}" class="btn btn-primary">Déposer sa Candidature</a>
          <a href="#actualites" class="btn btn-outline">
            Voir les Séminaires
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
          </a>
        </div>
      </div>

      <!-- Hero Visual avec Liste Déroulante Interactive des Séminaires -->
      <div class="hero-visual" x-data="{ 
          dropdownOpen: false, 
          selectedSeminarId: '{{ $seminars->first()?->id ?? '' }}',
          seminars: [
            @foreach($seminars as $s)
              {
                id: '{{ $s->id }}',
                title: '{{ addslashes($s->title) }}',
                dates: '{{ $s->start_date ? \Carbon\Carbon::parse($s->start_date)->format('d/m/Y') : '' }} - {{ $s->end_date ? \Carbon\Carbon::parse($s->end_date)->format('d/m/Y') : '' }}',
                dept: '{{ $s->departments->isNotEmpty() ? addslashes($s->departments->pluck('name')->join(', ')) : 'Génie Technique' }}',
                url: '{{ route('public.postuler', ['seminar_id' => $s->id]) }}',
                isOpen: {{ $s->isOpenForApplication() ? 'true' : 'false' }}
              },
            @endforeach
          ]
      }">
        <div class="hero-img-box">
          <img src="{{ asset('images/centre.png') }}" alt="Campus et ateliers techniques CFPT Sénégal-Japon" onerror="this.src='https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=900&q=80'" />
        </div>

        <!-- Image flottante des formateurs en pleine formation en atelier -->
        <div class="hero-floating-image-box" style="display: none;">
          <img src="{{ asset('images/centre2.png') }}" alt="Formateurs et stagiaires en atelier de formation pratique CFPT Sénégal-Japon" />
          <div class="hero-floating-image-badge">
            <span>Ateliers Pratiques & Séminaires</span>
            <span style="color: var(--accent-light); font-weight: 800;">CFPT-SJ</span>
          </div>
        </div>

        <!-- Widget Liste Déroulante Interactive des Séminaires -->
        <div class="hero-seminar-picker-widget">
          <div class="hero-picker-header">
            <div class="hero-picker-title">
              <span>Sélectionnez un Séminaire</span>
            </div>
            <span class="hero-picker-badge">
              <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #059669; animation: pulse 2s infinite;"></span>
              {{ $seminars->count() }} Sessions Disponibles
            </span>
          </div>

          <div class="hero-picker-select-wrapper" style="position: relative;">
            <!-- Select standard interactif (avec redirection instantanée au clic/choix) -->
            <select class="hero-picker-select" 
                    id="hero-seminar-select"
                    onchange="if(this.value) { window.location.href = this.value; }">
              <option value="" disabled selected>-- Cliquez pour choisir un séminaire & postuler --</option>
              @foreach($seminars as $sem)
                <option value="{{ route('public.postuler', ['seminar_id' => $sem->id]) }}">
                  {{ $sem->title }} ({{ $sem->start_date ? \Carbon\Carbon::parse($sem->start_date)->format('d/m/Y') : '' }})
                </option>
              @endforeach
            </select>

            <div class="hero-picker-select-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
          </div>

          <!-- Bouton Menu Déroulant Détaillé (Toggle Alpine.js) -->
          <div style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
            <button type="button" 
                    @click="dropdownOpen = !dropdownOpen" 
                    style="background: transparent; border: none; color: var(--primary-dark); font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 4px; padding: 4px 0; cursor: pointer;">
              <span x-text="dropdownOpen ? '▲ Masquer la liste complète' : '▼ Voir la liste détaillée des séminaires'"></span>
            </button>

            <a :href="document.getElementById('hero-seminar-select')?.value || '{{ route('public.postuler') }}'" 
               class="btn btn-primary" 
               style="padding: 6px 14px; font-size: 11px; font-weight: 700; border-radius: var(--radius-full);">
              Postuler en direct →
            </a>
          </div>

          <!-- Menu Déroulant enrichi avec cartes cliquables -->
          <div x-show="dropdownOpen" 
               x-transition 
               @click.outside="dropdownOpen = false" 
               class="hero-picker-dropdown-menu custom-scrollbar" 
               style="display: none;">
            @foreach($seminars as $sem)
              <a href="{{ route('public.postuler', ['seminar_id' => $sem->id]) }}" 
                 class="hero-picker-item group">
                <div class="hero-picker-item-title">
                  <span style="font-weight: 800; color: #0f172a;">{{ $sem->title }}</span>
                  @if($sem->isOpenForApplication())
                    <span style="font-size: 10px; font-weight: 800; color: #059669;">Ouvert</span>
                  @else
                    <span style="font-size: 10px; font-weight: 800; color: #4B6A95;">Session PFPT</span>
                  @endif
                </div>
                <div class="hero-picker-item-meta">
                  <span>{{ $sem->start_date ? \Carbon\Carbon::parse($sem->start_date)->format('d/m/Y') : 'À venir' }} - {{ $sem->end_date ? \Carbon\Carbon::parse($sem->end_date)->format('d/m/Y') : '' }}</span>
                  <span>•</span>
                  <span>{{ $sem->departments->isNotEmpty() ? $sem->departments->pluck('name')->first() : 'Formation Technique' }}</span>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 2. SECTION 3 CADRES PARTENAIRES INDIVIDUELS -->
  <section class="partners-grid-section">
    <div class="container">
      <div class="partners-title-label">
        Partenaires Institutionnels & Coopération Bilatérale
      </div>

      <div class="partners-three-grid">
        <!-- Cadre 1 : CFPT Sénégal-Japon -->
        <div class="partner-card-box">
          <img src="{{ file_exists(public_path('images/logos/logo-cfpt.png')) ? asset('images/logos/logo-cfpt.png') : asset('images/favicon.png') }}" 
               alt="CFPT Sénégal-Japon" 
               class="partner-card-img">
          <div class="partner-card-info">
            <h4>CFPT Sénégal-Japon</h4>
            <p>Centre de Formation Professionnelle & Technique</p>
          </div>
        </div>

        <!-- Cadre 2 : JICA Japon -->
        <div class="partner-card-box">
          <img src="{{ file_exists(public_path('images/logos/logo-jica.png')) ? asset('images/logos/logo-jica.png') : asset('images/logos/drapeau-japon.svg') }}" 
               alt="JICA - Coopération Japonaise" 
               class="partner-card-img">
          <div class="partner-card-info">
            <h4>JICA (Japon)</h4>
            <p>Agence Japonaise de Coopération Internationale</p>
          </div>
        </div>

        <!-- Cadre 3 : MEFPT Sénégal -->
        <div class="partner-card-box">
          <img src="{{ file_exists(public_path('images/logos/logo-ministere.png')) ? asset('images/logos/logo-ministere.png') : asset('images/logos/drapeau-senegal.svg') }}" 
               alt="Ministère de l'Emploi, de la Formation Professionnelle et Technique" 
               class="partner-card-img">
          <div class="partner-card-info">
            <h4>MEFPT (Sénégal)</h4>
            <p>Ministère de l'Emploi, de la Formation Professionnelle et Technique</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. STATS BANNER -->
  <section class="stats-section">
    <div class="container">
      <div class="stats-card">
        <div class="stat-item">
          <h3>98.5%</h3>
          <p>Taux de satisfaction et impact professionnel</p>
        </div>
        <div class="stat-item">
          <h3>40+ Ans</h3>
          <p>De coopération exemplaire Sénégal-Japon</p>
        </div>
        <div class="stat-item">
          <h3>15+ Pays</h3>
          <p>Bénéficiaires formés aux standards mondiaux</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. ACTEURS DU PROGRAMME -->
  <section class="actors-section" id="acteurs" x-data="{ showKaizen: false }">
    <div class="container">
      <div class="actors-grid">
        <div class="actors-text">
          <div class="section-eyebrow">Gouvernance & Partenariat</div>
          <h2>Une alliance stratégique au service des compétences</h2>
          <p>Ce programme associe l'expertise technologique japonaise, l'engagement de l'État du Sénégal et les besoins concrets du secteur industriel en Afrique subsaharienne.</p>
          
          <!-- Phrase d'introduction à la méthode Kaizen -->
          <div class="kaizen-intro-box">
            <p>
              Le modèle d'apprentissage intègre la démarche d'amélioration continue <strong>Kaizen</strong> et la discipline <strong>5S</strong>, garantissant rigueur opérationnelle et efficacité industrielle.
            </p>
          </div>

          <button type="button" 
                  @click="showKaizen = !showKaizen" 
                  class="btn btn-primary" 
                  style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <span x-text="showKaizen ? 'Masquer la Méthode Kaizen ▲' : 'Découvrir la Méthode Kaizen ↗'"></span>
          </button>
        </div>

        <!-- 2x2 Grid Acteurs avec Vrais Logos -->
        <div class="features-2x2">
          <!-- 1. JICA -->
          <div class="actor-box">
            <div class="actor-logo-wrap">
              <img src="{{ file_exists(public_path('images/logos/logo-jica.png')) ? asset('images/logos/logo-jica.png') : asset('images/logos/drapeau-japon.svg') }}" 
                   alt="Logo JICA" 
                   class="actor-logo-img">
            </div>
            <h4>JICA (Coopération Japonaise)</h4>
            <p>Financement intégral des équipements de pointe, assistance technique et experts japonais résidents.</p>
          </div>

          <!-- 2. Le CFPT Sénégal-Japon -->
          <div class="actor-box">
            <div class="actor-logo-wrap">
              <img src="{{ file_exists(public_path('images/logos/logo-cfpt.png')) ? asset('images/logos/logo-cfpt.png') : asset('images/favicon.png') }}" 
                   alt="Logo CFPT Sénégal-Japon" 
                   class="actor-logo-img">
            </div>
            <h4>Le CFPT Sénégal-Japon</h4>
            <p>Centre de référence africain assurant l'encadrement pédagogique et la formation pratique en ateliers.</p>
          </div>

          <!-- 3. État du Sénégal / MEFPT -->
          <div class="actor-box">
            <div class="actor-logo-wrap">
              <img src="{{ file_exists(public_path('images/logos/logo-ministere.png')) ? asset('images/logos/logo-ministere.png') : asset('images/logos/drapeau-senegal.svg') }}" 
                   alt="Logo MEFPT Sénégal" 
                   class="actor-logo-img">
            </div>
            <h4>État du Sénégal (MEFPT)</h4>
            <p>Ministère de l'Emploi, de la Formation Professionnelle et Technique : accréditation officielle, bourses et coordination institutionnelle.</p>
          </div>

          <!-- 4. Pays Partenaires & Industries -->
          <div class="actor-box">
            <div class="actor-logo-wrap">
              <img src="{{ file_exists(public_path('images/africa_map.svg')) ? asset('images/africa_map.svg') : asset('images/logo-blue.png') }}" 
                   alt="Logo PFPT Afrique" 
                   class="actor-logo-img"
                   style="height: 40px;">
            </div>
            <h4>Pays Partenaires & Industries</h4>
            <p>Accueil des stagiaires, définition des référentiels métiers et perfectionnement des cadres régionaux.</p>
          </div>
        </div>
      </div>

      <!-- Conteneur Déroulant Méthodologie Japonaise 5S Kaizen -->
      <div x-show="showKaizen" 
           x-transition 
           x-cloak 
           style="display: none; margin-top: 36px; padding: 32px; background: #f8fafc; border: 1.5px solid var(--border-color); border-radius: var(--radius-xl); box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
        <div class="feature-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: center;">
          <div class="feature-text">
            <div class="section-eyebrow">Méthodologie Japonaise</div>
            <h3 style="font-size: 22px; font-weight: 800; color: var(--primary-dark); margin: 8px 0 12px 0;">70% de pratique et culture de la rigueur Kaizen</h3>
            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 16px;">
              Au-delà de l'expertise technologique, les apprenants s'imprègnent de la rigueur professionnelle nippone : discipline 5S, optimisation continue des processus (Kaizen) et immersion directe en ateliers de production.
            </p>
            
            <ul class="kaizen-points-list">
              <li>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>Seiri (Débarrasser)</strong> — Éliminer l'inutile pour maximiser l'espace</span>
              </li>
              <li>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>Seiton (Ranger)</strong> — Un emplacement précis pour chaque outil</span>
              </li>
              <li>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>Seiso (Nettoyer)</strong> — Maintenir les postes de travail irréprochables</span>
              </li>
              <li>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>Seiketsu (Standardiser)</strong> — Établir des règles de travail claires</span>
              </li>
              <li>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>Shitsuke (Pérenniser)</strong> — Rigueur, discipline et amélioration continue</span>
              </li>
            </ul>
          </div>

          <div class="kaizen-visual-wrap" style="text-align: center;">
            <div class="kaizen-img-card">
              <img src="{{ file_exists(public_path('images/5s-kaizen.jpg')) ? asset('images/5s-kaizen.jpg') : asset('images/5S KAYZEN.jpg') }}" 
                   alt="Méthodologie Kaizen 5S - Seiri, Seiton, Seiso, Seiketsu, Shitsuke" 
                   class="kaizen-img-fluid"
                   style="max-width: 100%; border-radius: var(--radius-lg);"
                   onerror="this.src='{{ asset('images/5S KAYZEN.jpg') }}'">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 5. LES 3 DÉPARTEMENTS TECHNIQUES AVEC PHOTOS RÉELLES & IMPACT AFRIQUE -->
  <section class="departments-section" id="departements">
    <div class="container">
      <div class="departments-header">
        <div class="section-eyebrow">Pôles d'Expertise & Départements Techniques</div>
        <h2>Trois piliers d'ingénierie au cœur de l'essor industriel africain</h2>
        <p>Les séminaires du PFPT sont structurés autour des 3 grands départements techniques du CFPT Sénégal-Japon. Chaque domaine répond aux priorités stratégiques de souveraineté technologique, d'industrialisation et de modernisation des infrastructures sur le continent africain.</p>
      </div>

      <div class="departments-grid">
        <!-- 1. DÉPARTEMENT ÉLECTRIQUE -->
        <div class="dept-card" x-data="{ showImpact: false }">
          <div class="dept-card-top">
            <div class="dept-img-wrap">
              <img src="{{ asset('images/dept-electrique.jpg') }}" alt="Laboratoire Département Électrique et Automates" class="dept-card-img">
            </div>
            <span class="dept-badge-label">Énergie & Automatisme</span>
            <h3>Département Électrique</h3>
            <p class="dept-desc">
              Maîtrise des systèmes de distribution électrique industrielle, automates programmables (API), électromécanique, régulation et efficacité énergétique.
            </p>

            <div style="margin-top: 14px;">
              <button type="button" 
                      @click="showImpact = !showImpact" 
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: transparent; border: 1.5px solid var(--border-color); border-radius: var(--radius-full); font-size: 12px; font-weight: 700; color: var(--primary-dark); cursor: pointer; transition: all 0.2s ease;">
                <span x-text="showImpact ? 'Masquer ▲' : 'Voir plus ▼'"></span>
              </button>
            </div>
          </div>

          <div class="dept-impact-box" x-show="showImpact" x-transition x-cloak style="display: none; margin-top: 14px;">
            <div class="dept-impact-title">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
              Enjeu Développement Afrique
            </div>
            <ul class="dept-impact-list">
              <li>Fiabilisation des réseaux électriques et électrification durable.</li>
              <li>Automatisation et productivité accrue des usines locales.</li>
              <li>Intégration et gestion des parcs d'énergies renouvelables.</li>
            </ul>
          </div>
        </div>

        <!-- 2. DÉPARTEMENT INFORMATIQUE -->
        <div class="dept-card" x-data="{ showImpact: false }">
          <div class="dept-card-top">
            <div class="dept-img-wrap">
              <img src="{{ asset('images/dept-informatique.jpg') }}" alt="Laboratoire Réseaux et Informatique Industrielle" class="dept-card-img">
            </div>
            <span class="dept-badge-label">Numérique & Télécoms</span>
            <h3>Département Informatique</h3>
            <p class="dept-desc">
              Infrastructures réseaux d'entreprise, sécurité des systèmes industriels, administration systèmes, cloud computing et télécommunications avancées.
            </p>

            <div style="margin-top: 14px;">
              <button type="button" 
                      @click="showImpact = !showImpact" 
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: transparent; border: 1.5px solid var(--border-color); border-radius: var(--radius-full); font-size: 12px; font-weight: 700; color: var(--primary-dark); cursor: pointer; transition: all 0.2s ease;">
                <span x-text="showImpact ? 'Masquer ▲' : 'Voir plus ▼'"></span>
              </button>
            </div>
          </div>

          <div class="dept-impact-box" x-show="showImpact" x-transition x-cloak style="display: none; margin-top: 14px;">
            <div class="dept-impact-title">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
              Enjeu Développement Afrique
            </div>
            <ul class="dept-impact-list">
              <li>Souveraineté numérique et sécurisation des données stratégiques.</li>
              <li>Connectivité haut débit et transformation digitale des services.</li>
              <li>Déploiement de solutions IoT pour la maintenance connectée.</li>
            </ul>
          </div>
        </div>

        <!-- 3. DÉPARTEMENT MÉCANIQUE -->
        <div class="dept-card" x-data="{ showImpact: false }">
          <div class="dept-card-top">
            <div class="dept-img-wrap">
              <img src="{{ asset('images/dept-mecanique.jpg') }}" alt="Atelier d'Usinage CNC et Maintenance Mécanique" class="dept-card-img">
            </div>
            <span class="dept-badge-label">Industrie & Maintenance</span>
            <h3>Département Mécanique</h3>
            <p class="dept-desc">
              Maintenance industrielle préventive et corrective, usinage de précision (conventionnel & CNC), hydraulique, pneumatique et fabrication mécanique.
            </p>

            <div style="margin-top: 14px;">
              <button type="button" 
                      @click="showImpact = !showImpact" 
                      style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: transparent; border: 1.5px solid var(--border-color); border-radius: var(--radius-full); font-size: 12px; font-weight: 700; color: var(--primary-dark); cursor: pointer; transition: all 0.2s ease;">
                <span x-text="showImpact ? 'Masquer ▲' : 'Voir plus ▼'"></span>
              </button>
            </div>
          </div>

          <div class="dept-impact-box" x-show="showImpact" x-transition x-cloak style="display: none; margin-top: 14px;">
            <div class="dept-impact-title">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
              Enjeu Développement Afrique
            </div>
            <ul class="dept-impact-list">
              <li>Pérennisation du parc de machines et réduction des pannes.</li>
              <li>Autonomie de fabrication locale de pièces de rechange.</li>
              <li>Compétitivité renforcée du tissu manufacturier sous-régional.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 7. PARCOURS OFFICIEL & MODALITÉS D'ADMISSION (JICA & MINISTÈRE) -->
  <section class="modalities-section" id="modalites">
    <div class="container">
      <div class="section-header-center">
        <div class="section-eyebrow">Parcours Officiel & Modalités d'Admission</div>
        <h2>Processus de Sélection & Bourses d'Excellence JICA</h2>
        <p>L'admission aux séminaires du PFPT est régie par un protocole officiel de coopération bilatérale. Découvrez les étapes indispensables pour valider définitivement votre candidature.</p>
      </div>

      <div class="modalities-grid">
        <!-- Colonne 1 : Circuit Officiel de Sélection -->
        <div class="modality-box">
          <div>
            <div class="modality-header">
              <span class="modality-tag">Protocole d'Admission</span>
              <h3>Circuit Officiel de Sélection</h3>
              <p>Chaque candidature suit un processus rigoureux et concerté en 3 étapes :</p>
            </div>

            <div class="timeline-steps">
              <!-- Étape 1 -->
              <div class="timeline-step-item">
                <div class="step-circle">01</div>
                <div class="step-details">
                  <h4>Pré-candidature en ligne</h4>
                  <p>Enregistrement du profil et dépôt du dossier numérique (CV, diplômes, lettre de motivation) sur ce portail officiel du PFPT.</p>
                </div>
              </div>

              <!-- Étape 2 -->
              <div class="timeline-step-item">
                <div class="step-circle">02</div>
                <div class="step-details">
                  <h4>Endossement par le Ministère de Tutelle</h4>
                  <p>Validation et accord hiérarchique auprès du Ministère compétent de votre pays d'origine (Formation Professionnelle, Emploi, Industrie).</p>
                </div>
              </div>

              <!-- Étape 3 -->
              <div class="timeline-step-item">
                <div class="step-circle">03</div>
                <div class="step-details">
                  <h4>Dépôt officiel & Agrément JICA</h4>
                  <p>Dépôt formel des documents physiques validés auprès de l'Ambassade du Japon ou du Bureau Résident de la JICA de votre pays pour la sélection finale.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="modalities-official-notice">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span><strong>Rappel institutionnel :</strong> La postulation en ligne est la 1ère étape obligatoire, mais ne vaut pas admission définitive sans l'accord ministériel et le dépôt auprès de la JICA locale.</span>
          </div>
        </div>

        <!-- Colonne 2 : Prise en charge & Bourse d'Excellence JICA -->
        <div class="modality-box">
          <div>
            <div class="modality-header">
              <span class="modality-tag">Financement & Accompagnement</span>
              <h3>Bourses & Prise en Charge</h3>
              <p>Le partenariat JICA & État du Sénégal garantit une couverture complète des auditeurs retenus :</p>
            </div>

            <div class="scholarship-badge">
              <span>100% Prise en Charge — Bourse JICA / Sénégal</span>
            </div>

            <ul class="scholarship-list">
              <li>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <div>
                  <strong>Séjour & Billetterie Complète</strong>
                  <span>Prise en charge intégrale des billets d'avion A/R, de l'hébergement et de la restauration à Dakar.</span>
                </div>
              </li>
              <li>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <div>
                  <strong>Plateaux Techniques & Équipements du Japon</strong>
                  <span>Accès illimité aux bancs d'essai industriels, automates et laboratoires de pointe du CFPT-SJ.</span>
                </div>
              </li>
              <li>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <div>
                  <strong>Certificat & Attestation d'Excellence</strong>
                  <span>Délivrance d'une attestation conjointe co-signée par le CFPT Sénégal-Japon et la JICA.</span>
                </div>
              </li>
            </ul>
          </div>

          <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 16px;">
            <a href="{{ route('public.postuler') }}" class="btn btn-primary" style="width: 100%;">
              Déposer une Pré-Candidature
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
            </a>
            <a href="#contact" class="btn btn-outline" style="width: 100%;">
              Contacter la Direction
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 8. FAQ SECTION -->
  <section class="faq-section" id="faq">
    <div class="container faq-container">
      <h2>Foire Aux Questions</h2>

      <div class="faq-accordion">
        <div class="faq-item active">
          <button class="faq-question" type="button">
            <span>Quelles sont les conditions et étapes pour être sélectionné au programme PFPT ?</span>
            <span class="faq-icon">+</span>
          </button>
          <div class="faq-answer">
            Les séminaires du PFPT s'adressent aux formateurs, techniciens et cadres des pays partenaires d'Afrique subsaharienne et du Sénégal. La procédure comporte trois volets obligatoires : 1) La pré-inscription en ligne sur ce portail, 2) La validation et l'endossement de votre candidature par le Ministère de tutelle de votre pays, et 3) Le dépôt officiel du dossier auprès du Bureau Résident de la JICA ou de l'Ambassade du Japon de votre pays.
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question" type="button">
            <span>Quel est le rôle exact de la JICA dans cette coopération ?</span>
            <span class="faq-icon">+</span>
          </button>
          <div class="faq-answer">
            L'Agence Japonaise de Coopération Internationale (JICA) cofinance les bourses des participants, dote les laboratoires en équipements industriels modernes et dépêche des experts japonais pour le transfert continu d'expertise.
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question" type="button">
            <span>À quoi sert la plateforme numérique de gestion ?</span>
            <span class="faq-icon">+</span>
          </button>
          <div class="faq-answer">
            Elle centralise tout le cycle de vie du séminaire : soumission en ligne du dossier, validation par la commission, gestion des plannings, suivi des évaluations, partage des supports de cours et délivrance des attestations.
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-question" type="button">
            <span>Les certificats et attestations sont-ils reconnus à l'international ?</span>
            <span class="faq-icon">+</span>
          </button>
          <div class="faq-answer">
            Oui, les attestations délivrées portent le sceau du Centre de Formation Professionnelle et Technique (CFPT) Sénégal-Japon ainsi que le label de qualité de la coopération japonaise JICA, hautement reconnus par les industriels en Afrique.
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 9. SECTION ACTUALITÉS & SÉMINAIRES EN COURS (JUSTE AVANT CONTACT) -->
  <section class="news-section" id="actualites">
    <div class="container">
      <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
        <div>
          <div class="section-eyebrow">Actualités</div>
          <h2 style="font-size: 34px; font-weight: 800; color: var(--primary-dark); letter-spacing: -0.8px;">Séminaires d'Appui & Thèmes en Cours</h2>
          <p style="font-size: 15px; color: var(--text-muted); margin-top: 4px;">Découvrez les sessions actuellement ouvertes et postulez aux modules programmés.</p>
        </div>
        <div>
          <a href="{{ route('public.postuler') }}" class="btn btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
            Formulaire de Candidature
          </a>
        </div>
      </div>

      @if($seminars->isEmpty())
        <div style="background: #ffffff; border-radius: var(--radius-lg); padding: 48px 24px; text-align: center; border: 1px dashed var(--border-color); margin-top: 24px;">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--text-light)" stroke-width="1.5" style="margin: 0 auto 12px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
          <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Aucun séminaire actif actuellement</h3>
          <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Les prochaines programmations seront publiées sous peu par l'administration.</p>
        </div>
      @else
        <div class="seminars-grid">
          @foreach($seminars as $seminar)
            @php
                $enrolledCount = $seminar->registrations ? $seminar->registrations->where('status', 'approved')->count() : 0;
                $percent = $seminar->max_participants > 0 ? min(100, round(($enrolledCount / $seminar->max_participants) * 100)) : 0;
                $imageUrl = $seminar->image ? asset('storage/' . $seminar->image) : asset('images/centre.png');
            @endphp
            <div class="seminar-card" x-data="{ showDetails: false }">
              <div class="seminar-card-img" style="background-image: url('{{ $imageUrl }}');">
                @if($seminar->cohort)
                  <span class="seminar-tag-top">{{ $seminar->cohort->title }}</span>
                @endif
                @if($seminar->isOpenForApplication())
                  <span class="seminar-status-bottom">Inscriptions Ouvertes</span>
                @elseif($seminar->isOngoing())
                  <span class="seminar-status-bottom" style="background: #ea580c;">En cours</span>
                @else
                  <span class="seminar-status-bottom" style="background: #64748b;">Terminé</span>
                @endif
              </div>

              <div class="seminar-body">
                <div>
                  <div class="seminar-dept">
                    {{ $seminar->departments->isNotEmpty() ? $seminar->departments->pluck('name')->join(', ') : 'Génie Industriel & Technique' }}
                  </div>
                  <h3 class="seminar-title">{{ $seminar->title }}</h3>
                  <p class="seminar-desc">
                    {{ $seminar->description ?: 'Formation technique de perfectionnement destinée aux cadres, techniciens et formateurs aux normes du Japon.' }}
                  </p>

                  <!-- Bouton Voir plus juste après la description -->
                  <div style="margin: 8px 0 10px 0;">
                    <button type="button" 
                            @click="showDetails = !showDetails" 
                            style="display: inline-flex; align-items: center; gap: 4px; background: transparent; border: none; padding: 0; color: var(--primary-dark); font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: underline;">
                      <span x-text="showDetails ? 'Masquer les détails ▲' : 'Voir plus de détails ▼'"></span>
                    </button>
                  </div>

                  <!-- Détails complets du séminaire -->
                  <div x-show="showDetails" x-transition x-cloak style="display: none; padding: 12px; background: #f8fafc; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 12px; font-size: 12px; color: var(--text-dark); line-height: 1.5;">
                    @if($seminar->cohort)
                      <div style="margin-bottom: 4px;"><strong>Édition :</strong> {{ $seminar->cohort->title }}</div>
                    @endif
                    <div style="margin-bottom: 4px;"><strong>Dates complètes :</strong> Du {{ $seminar->start_date ? \Carbon\Carbon::parse($seminar->start_date)->format('d/m/Y') : 'À venir' }} au {{ $seminar->end_date ? \Carbon\Carbon::parse($seminar->end_date)->format('d/m/Y') : 'Non spécifié' }}</div>
                    <div style="margin-bottom: 4px;"><strong>Capacité :</strong> {{ $seminar->max_participants ?: 30 }} participants ({{ $enrolledCount }} validés)</div>
                    @if($seminar->departments->isNotEmpty())
                      <div style="margin-bottom: 4px;"><strong>Pôle(s) :</strong> {{ $seminar->departments->pluck('name')->join(', ') }}</div>
                    @endif
                    @if($seminar->topics->isNotEmpty())
                      <div>
                        <strong>Thèmes de spécialisation :</strong>
                        <ul style="margin: 4px 0 0 16px; padding: 0; list-style-type: disc;">
                          @foreach($seminar->topics as $t)
                            <li>{{ $t->name }}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif
                  </div>
                </div>

                <div>
                  <div class="seminar-meta">
                    <span style="display: flex; align-items: center; gap: 6px;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                      {{ $seminar->start_date ? \Carbon\Carbon::parse($seminar->start_date)->format('d M') : 'À venir' }} - {{ $seminar->end_date ? \Carbon\Carbon::parse($seminar->end_date)->format('d M Y') : '' }}
                    </span>
                    <span style="font-weight: 700;">
                      {{ $enrolledCount }} / {{ $seminar->max_participants ?: 30 }} places
                    </span>
                  </div>

                  <div class="seminar-progress">
                    <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 700; color: var(--text-muted);">
                      <span>Remplissage</span>
                      <span>{{ $percent }}%</span>
                    </div>
                    <div class="progress-bar-bg">
                      <div class="progress-bar-fill" style="width: {{ $percent }}%;"></div>
                    </div>
                  </div>

                  @if($seminar->topics->isNotEmpty())
                    <div class="topic-pills">
                      @foreach($seminar->topics->take(3) as $topic)
                        <span class="topic-pill">{{ $topic->name }}</span>
                      @endforeach
                    </div>
                  @endif

                  @if($seminar->isOpenForApplication())
                    <a href="{{ route('public.postuler', ['seminar_id' => $seminar->id]) }}" class="btn btn-primary" style="width: 100%; font-size: 13px; padding: 10px;">
                      Postuler à ce Séminaire
                    </a>
                  @elseif($seminar->isOngoing())
                    <button type="button" disabled class="btn" style="width: 100%; font-size: 13px; padding: 10px; background: #f1f5f9; color: #64748b; cursor: not-allowed; border: 1px solid #cbd5e1;">
                      Inscriptions Closes (En cours)
                    </button>
                  @else
                    <button type="button" disabled class="btn" style="width: 100%; font-size: 13px; padding: 10px; background: #f1f5f9; color: #64748b; cursor: not-allowed; border: 1px solid #cbd5e1;">
                      Session Clôturée
                    </button>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  <!-- 10. CONTACT & LOCALISATION À SUD FOIRE -->
  <section class="contact-section" id="contact">
    <div class="container">
      <div class="section-header-center" style="margin-bottom: 36px;">
        <div class="section-eyebrow">Nous Retrouver</div>
        <h2>Contact & Localisation</h2>
        <p>Centre de Formation Professionnelle et Technique Sénégal-Japon, Sud Foire, Dakar.</p>
      </div>

      <div class="contact-split-container">
        <!-- Volet Coordonnées & Accompagnement -->
        <div class="contact-info-panel">
          <div>
            <div class="contact-info-header">
              <h3>Campus CFPT-SJ</h3>
              <p>Retrouvez l'ensemble des coordonnées officielles pour nous joindre ou visiter nos installations à Dakar.</p>
            </div>

            <div class="contact-channels-list">
              <!-- Adresse -->
              <div class="contact-channel-item">
                <div class="channel-icon-wrap">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <div class="channel-content">
                  <span class="channel-label">Adresse du Campus</span>
                  <strong>Sud Foire, Dakar</strong>
                  <span class="channel-sub">Boulevard du Centenaire prolongé</span>
                </div>
              </div>

              <!-- Téléphone -->
              <div class="contact-channel-item">
                <div class="channel-icon-wrap">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </div>
                <div class="channel-content">
                  <span class="channel-label">Téléphone Direct</span>
                  <strong><a href="tel:+221338647800">+221 33 864 78 00</a></strong>
                  <span class="channel-sub">Du Lundi au Vendredi (8h - 17h)</span>
                </div>
              </div>

              <!-- Courriel -->
              <div class="contact-channel-item">
                <div class="channel-icon-wrap">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <div class="channel-content">
                  <span class="channel-label">Courriel Officiel</span>
                  <strong><a href="mailto:contact@cfpt.sn">contact@cfpt.sn</a></strong>
                  <span class="channel-sub">Réponse rapide sous 48h ouvrées</span>
                </div>
              </div>
            </div>
          </div>

          <div class="contact-panel-actions">
            <a href="https://maps.google.com/maps?q=CFPT%20Senegal-Japon%20Sud%20Foire%20Dakar" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="width: 100%; justify-content: center; font-size: 13px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
              Itinéraire Google Maps
            </a>
          </div>
        </div>

        <!-- Volet Carte Interactive Intégrée -->
        <div class="contact-map-panel">
          <iframe 
            title="Localisation CFPT Sénégal-Japon Sud Foire"
            src="https://maps.google.com/maps?q=CFPT%20Senegal-Japon%20Sud%20Foire%20Dakar&t=&z=16&ie=UTF8&iwloc=&output=embed" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>

          <div class="map-overlay-badge">
            <div class="map-badge-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
            </div>
            <div>
              <strong>CFPT Sénégal-Japon</strong>
              <span>Campus Sud Foire, Dakar</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- 12. FOOTER -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-top">
        <div class="footer-col">
          <h5>CFPT Sénégal - Japon</h5>
          <p style="font-size: 13px; color: rgba(255, 255, 255, 0.7); line-height: 1.6; margin-bottom: 14px;">
            Centre de Formation Professionnelle et Technique Sénégal-Japon, Dakar, Sénégal.
          </p>
          <span style="font-size: 12px; color: var(--accent-light); font-weight: 700;">Partenariat stratégique JICA & MEFPT</span>
        </div>

        <div class="footer-col">
          <h5>Départements Techniques</h5>
          <ul>
            <li><a href="#departements">Département Électrique</a></li>
            <li><a href="#departements">Département Informatique</a></li>
            <li><a href="#departements">Département Mécanique</a></li>
            <li><a href="#acteurs">Gouvernance & Kaizen</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h5>Portail & Espaces</h5>
          <ul>
            <li><a href="{{ route('public.postuler') }}">Espace Candidat</a></li>
            <li><a href="{{ route('login') }}">Espace Formateur</a></li>
            <li><a href="{{ route('login') }}">Espace Administration</a></li>
            <li><a href="#actualites">Séminaires & Actualités</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h5>Contact & Assistance</h5>
          <ul>
            <li><a href="#contact">Sud Foire, Dakar, Sénégal</a></li>
            <li><a href="mailto:contact@cfpt.sn">contact@cfpt.sn</a></li>
            <li><a href="tel:+221338647800">+221 33 864 78 00</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <div style="display: flex; align-items: center; gap: 10px;">
          <strong>CFPT Sénégal-Japon &times; JICA</strong>
          <span>&copy; {{ date('Y') }} Tous droits réservés.</span>
        </div>

        <div class="footer-bottom-links">
          <a href="#accueil">Haut de page</a>
          <a href="{{ route('public.postuler') }}">Candidater</a>
          <a href="{{ route('login') }}">Connexion</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- JAVASCRIPT ACCORDION & ICONS LOGIC -->
  <script>
    // Accordéon FAQ
    document.querySelectorAll('.faq-question').forEach(button => {
      button.addEventListener('click', () => {
        const item = button.parentElement;
        const isActive = item.classList.contains('active');

        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('active'));

        if (!isActive) {
          item.classList.add('active');
        }
      });
    });

    // Initialisation Lucide icons
    document.addEventListener('DOMContentLoaded', () => {
      if (window.lucide) {
        lucide.createIcons();
      }
    });
  </script>

  <!-- Widget Chatbot Flottant n8n -->
  <x-n8n-chat />
</body>
</html>
