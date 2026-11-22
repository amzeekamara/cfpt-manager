<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>PFPT CFPT - Administration</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" media="print" onload="this.media='all'"/>
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    </noscript>
    <script defer src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script id="tailwind-config">
        if (typeof tailwind !== 'undefined') {
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "brand-primary": "#4B6A95",
                        "brand-light": "#d2e4ff",
                        "secondary-container": "#d5e0f8",
                        "on-surface": "#191c1e",
                        "on-error-container": "#4B6A95",
                        "on-secondary-fixed-variant": "#3c475a",
                        "on-tertiary": "#ffffff",
                        "secondary": "#545f73",
                        "on-secondary-container": "#586377",
                        "outline-variant": "#c2c7d1",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#2d6197",
                        "on-primary-container": "#8ebdf9",
                        "on-primary-fixed-variant": "#07497d",
                        "tertiary-fixed-dim": "#ffb780",
                        "secondary-fixed": "#d8e3fb",
                        "primary-fixed-dim": "#a0c9ff",
                        "on-primary": "#ffffff",
                        "surface-bright": "#f7f9fb",
                        "secondary-fixed-dim": "#bcc7de",
                        "inverse-on-surface": "#eff1f3",
                        "surface-variant": "#e0e3e5",
                        "outline": "#727780",
                        "surface-container-low": "#f2f4f6",
                        "error-container": "#ffdad6",
                        "primary-fixed": "#d2e4ff",
                        "surface-container": "#eceef0",
                        "on-tertiary-fixed-variant": "#6f3800",
                        "inverse-surface": "#2d3133",
                        "primary": "#4B6A95",
                        "primary-container": "#0f4c81",
                        "surface-container-highest": "#e0e3e5",
                        "on-tertiary-fixed": "#2f1400",
                        "inverse-primary": "#a0c9ff",
                        "error": "#ba1a1a",
                        "surface-container-lowest": "#ffffff",
                        "surface": "#f7f9fb",
                        "on-primary-fixed": "#001c37",
                        "on-background": "#191c1e",
                        "tertiary": "#532800",
                        "background": "#f7f9fb",
                        "tertiary-container": "#743b00",
                        "on-tertiary-container": "#f9a767",
                        "tertiary-fixed": "#ffdcc4",
                        "surface-dim": "#d8dadc",
                        "on-error": "#ffffff",
                        "on-secondary-fixed": "#111c2d",
                        "surface-container-high": "#e6e8ea",
                        "on-surface-variant": "#42474f"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "sans": ["'Plus Jakarta Sans'", "Inter", "sans-serif"],
                        "headline": ["'Plus Jakarta Sans'", "sans-serif"],
                        "body": ["'Plus Jakarta Sans'", "Inter", "sans-serif"],
                        "label": ["Inter", "sans-serif"]
                    }
                }
            }
        };
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; }
        /* Hide scrollbar for sidebar when collapsed */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .sidebar-collapsed-hide { display: none; }
        
        /* Ombres ultra-douces sur toute la plateforme */
        .shadow-2xl, .shadow-xl, .shadow-lg, .shadow-md, .shadow {
            box-shadow: 0 2px 8px -2px rgba(15, 23, 42, 0.04), 0 1px 4px -1px rgba(15, 23, 42, 0.02) !important;
        }
        .shadow-sm, .shadow-xs, .shadow-2xs {
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03) !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#e9ebf0] text-slate-800 antialiased overflow-x-hidden" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))">

<!-- Barre de progression fine en haut de page (non bloquante, ignore les téléchargements) -->
<div id="top-nav-progress" class="fixed top-0 left-0 h-[3px] bg-[#4B6A95] z-[9999] transition-all duration-300 pointer-events-none opacity-0" style="width: 0%;"></div>

<script>
    (function() {
        const bar = document.getElementById('top-nav-progress');
        let timer = null;

        function startProgress() {
            if (!bar) return;
            clearTimeout(timer);
            bar.style.opacity = '1';
            bar.style.width = '35%';
            timer = setTimeout(() => { bar.style.width = '75%'; }, 180);
        }

        function resetProgress() {
            if (!bar) return;
            clearTimeout(timer);
            bar.style.width = '0%';
            bar.style.opacity = '0';
        }

        // Déclencher uniquement sur les vrais liens de navigation (jamais sur les téléchargements / exports / modales)
        document.addEventListener('click', (e) => {
            if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;
            const link = e.target.closest('a[href]');
            if (!link) return;
            const href = link.getAttribute('href') || '';
            if (
                link.hasAttribute('download') ||
                link.hasAttribute('data-no-loader') ||
                link.target === '_blank' ||
                href === '' ||
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                href.startsWith('mailto:') ||
                /\/(export|download|import-template)(\?|$|\/)/i.test(href)
            ) {
                return;
            }
            startProgress();
        });

        window.addEventListener('pageshow', resetProgress);
    })();
</script>

<!-- Mobile Menu Drawer Overlay -->
<!-- Mobile Menu Drawer Overlay -->
<div x-show="sidebarOpen" 
     x-transition.opacity 
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 lg:hidden" style="display: none;">
</div>

<!-- ============================================================== -->
<!-- 1. VERTICAL DOCK SIDEBAR AVEC LISTES DÉROULANTES DÉTAILLÉES (STYLE FINEXY) -->
<!-- ============================================================== -->
<aside class="fixed inset-y-0 left-0 z-50 w-20 bg-[#e9ebf0] flex flex-col items-center justify-between py-6 px-3 transition-transform duration-300 ease-in-out"
       :class="{'translate-x-0': sidebarOpen, '-translate-x-full lg:translate-x-0': !sidebarOpen}"
       x-data="{ activeFlyout: null }">
    
    <!-- Top: Theme Toggle Pill (Sun / Moon) -->
    <div class="flex flex-col items-center gap-1 bg-white/70 backdrop-blur-xs rounded-full p-1 border border-slate-200/80 shadow-2xs">
        <button type="button" class="w-8 h-8 rounded-full bg-white text-slate-800 shadow-2xs flex items-center justify-center transition" title="Mode Clair">
            <i data-lucide="sun" class="w-4 h-4 text-amber-500"></i>
        </button>
        <button type="button" class="w-8 h-8 rounded-full text-slate-400 hover:text-slate-700 flex items-center justify-center transition" title="Mode Sombre">
            <i data-lucide="moon" class="w-4 h-4"></i>
        </button>
    </div>

    <!-- Center: Floating White Nav Pill Dock avec Popovers au clic -->
    <div class="bg-white rounded-[32px] p-2 shadow-sm border border-slate-200/90 flex flex-col items-center gap-2.5 my-auto" @click.outside="activeFlyout = null">
        
        <!-- 1. Tableau de bord -->
        <div class="relative">
            <a href="{{ route('admin.dashboard') }}" 
               class="w-11 h-11 rounded-full flex items-center justify-center transition group {{ request()->routeIs('admin.dashboard') ? 'bg-[#4B6A95] text-white shadow-xs' : 'text-slate-400 hover:text-slate-800 hover:bg-slate-100' }}"
               title="Tableau de bord">
                <i data-lucide="layout-grid" class="w-5 h-5"></i>
            </a>
        </div>

        <!-- 2. Catalogue & Séminaires (Liste déroulante) -->
        <div class="relative">
            <button @click="activeFlyout = activeFlyout === 'catalogue' ? null : 'catalogue'" 
                    class="w-11 h-11 rounded-full flex items-center justify-center transition {{ (request()->routeIs('admin.seminars*') || request()->routeIs('admin.cohorts.*') || request()->routeIs('admin.topics.*') || request()->routeIs('admin.departments.*')) ? 'bg-[#4B6A95] text-white shadow-xs' : 'text-slate-400 hover:text-slate-800 hover:bg-slate-100' }}"
                    title="Catalogue & Séminaires">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </button>

            <!-- Flyout Dropdown -->
            <div x-show="activeFlyout === 'catalogue'" 
                 x-transition 
                 class="absolute left-full top-0 ml-3 w-60 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50"
                 style="display: none;">
                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                    <p class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Catalogue & Formations</p>
                </div>
                <div class="space-y-0.5 text-xs">
                    <a href="{{ route('admin.seminars') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.seminars') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="book-open" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Séminaires</span>
                    </a>
                    <a href="{{ route('admin.seminars.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>+ Nouveau séminaire</span>
                    </a>
                    <a href="{{ route('admin.cohorts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.cohorts.*') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="layers" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Éditions & Budgets</span>
                    </a>
                    <a href="{{ route('admin.topics.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.topics.*') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="tag" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Thèmes</span>
                    </a>
                    @if(auth()->user()->role->name !== 'Agent JICA')
                    <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.departments.*') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="building" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Départements</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. Scolarité & Candidatures (Liste déroulante) -->
        <div class="relative">
            <button @click="activeFlyout = activeFlyout === 'scolarite' ? null : 'scolarite'" 
                    class="w-11 h-11 rounded-full flex items-center justify-center transition {{ (request()->routeIs('admin.candidates*') || request()->routeIs('admin.attendances.*') || request()->routeIs('admin.forms.*')) ? 'bg-[#4B6A95] text-white shadow-xs' : 'text-slate-400 hover:text-slate-800 hover:bg-slate-100' }}"
                    title="Scolarité & Candidatures">
                <i data-lucide="users" class="w-5 h-5"></i>
            </button>

            <!-- Flyout Dropdown -->
            <div x-show="activeFlyout === 'scolarite'" 
                 x-transition 
                 class="absolute left-full top-0 ml-3 w-60 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50"
                 style="display: none;">
                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                    <p class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Scolarité & Candidats</p>
                </div>
                <div class="space-y-0.5 text-xs">
                    <a href="{{ route('admin.candidates') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.candidates') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="user-check" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Tous les Candidats</span>
                    </a>
                    @if(auth()->user()->role->name !== 'Agent JICA')
                    <a href="{{ route('admin.attendances.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.attendances.index') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="clipboard-check" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Feuilles de Présence</span>
                    </a>
                    <a href="{{ route('admin.forms.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.forms.*') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Formulaires d'inscription</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- 4. Documents & Ressources (Liste déroulante) -->
        <div class="relative">
            <button @click="activeFlyout = activeFlyout === 'ged' ? null : 'ged'" 
                    class="w-11 h-11 rounded-full flex items-center justify-center transition {{ (request()->routeIs('admin.resources.*') || request()->routeIs('admin.archives') || request()->routeIs('admin.badges.*')) ? 'bg-[#4B6A95] text-white shadow-xs' : 'text-slate-400 hover:text-slate-800 hover:bg-slate-100' }}"
                    title="Documents, Cartes & Attestations">
                <i data-lucide="folder-open" class="w-5 h-5"></i>
            </button>

            <!-- Flyout Dropdown -->
            <div x-show="activeFlyout === 'ged'" 
                 x-transition 
                 class="absolute left-full top-0 ml-3 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50"
                 style="display: none;">
                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                    <p class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Documents & Studio PDF</p>
                </div>
                <div class="space-y-0.5 text-xs">
                    <a href="{{ route('admin.badges.generator') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.badges.*') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="id-card" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Cartes, Badges & Attestations</span>
                    </a>
                    <a href="{{ route('admin.resources.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.resources.*') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="files" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Documents (Fichiers & Guides)</span>
                    </a>
                    <a href="{{ route('admin.archives') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.archives') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="archive" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Archivage & Historique</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. Administration & Paramètres (Liste déroulante) -->
        <div class="relative">
            <button @click="activeFlyout = activeFlyout === 'admin' ? null : 'admin'" 
                    class="w-11 h-11 rounded-full flex items-center justify-center transition {{ (request()->routeIs('admin.finances*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.messages.*') || request()->routeIs('admin.notifications.*') || request()->routeIs('admin.debug.*')) ? 'bg-[#4B6A95] text-white shadow-xs' : 'text-slate-400 hover:text-slate-800 hover:bg-slate-100' }}"
                    title="Administration & Outils">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </button>

            <!-- Flyout Dropdown -->
            <div x-show="activeFlyout === 'admin'" 
                 x-transition 
                 class="absolute left-full bottom-0 ml-3 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50"
                 style="display: none;">
                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                    <p class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Administration</p>
                </div>
                <div class="space-y-0.5 text-xs">
                    <a href="{{ route('admin.finances') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.finances') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="wallet" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Finances & Recettes</span>
                    </a>
                    @if(auth()->user()->role->name !== 'Agent JICA')
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.users.*') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="shield" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Utilisateurs & Rôles</span>
                    </a>
                    <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.messages.*') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="mail" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Messages & Diffusion</span>
                    </a>
                    @if(Route::has('admin.debug.emails'))
                    <a href="{{ route('admin.debug.emails') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.debug.emails') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="bug" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Debugger Emails</span>
                    </a>
                    @endif
                    @endif
                    <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 hover:text-[#4B6A95] hover:bg-blue-50/50 font-semibold transition {{ request()->routeIs('admin.notifications.index') ? 'bg-slate-100 font-bold text-[#4B6A95]' : '' }}">
                        <i data-lucide="bell" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Historique Notifications</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom: Actions Dock (Messagerie + Portail Public) -->
    <div class="flex flex-col items-center gap-2">
        <!-- Bouton Messagerie / Nouvelle Alerte -->
        @if(auth()->user()->role->name !== 'Agent JICA')
        <a href="{{ route('admin.messages.create') }}" 
           class="w-10 h-10 rounded-full bg-white text-[#4B6A95] hover:bg-[#4B6A95] hover:text-white border border-slate-200/80 shadow-2xs flex items-center justify-center transition group relative" 
           title="Nouvelle Alerte / Message">
            <i data-lucide="mail" class="w-4 h-4"></i>
            <span class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-[11px] font-bold rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none z-50 shadow-md">
                Nouvelle Alerte / Message
            </span>
        </a>
        @endif

        <!-- Bouton Portail Public -->
        <a href="{{ route('welcome') }}" target="_blank" 
           class="w-10 h-10 rounded-full bg-white text-slate-500 hover:text-[#4B6A95] border border-slate-200/80 shadow-2xs flex items-center justify-center transition group relative" 
           title="Portail Public">
            <i data-lucide="globe" class="w-4 h-4"></i>
            <span class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-[11px] font-bold rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none z-50 shadow-md">
                Portail Public
            </span>
        </a>
    </div>

</aside>

<!-- ============================================================== -->
<!-- 2. TOP HORIZONTAL NAVBAR (STYLE FINEXY - LOGO FAVICON & COULEUR BLEUE PRINCIPALE) -->
<!-- ============================================================== -->
<header class="fixed top-0 right-0 left-0 lg:left-20 z-40 bg-[#e9ebf0]/95 backdrop-blur-md transition-all duration-300 ease-in-out">
    <div class="flex items-center justify-between h-20 px-4 md:px-8 gap-4">
        
        <!-- Left: Logo Area (Utilise le même logo que le Favicon) -->
        <div class="flex items-center gap-3">
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-white border border-slate-200 lg:hidden text-slate-700 focus:outline-none shadow-2xs">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            <!-- Brand Logo Pill -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 bg-white px-3.5 py-1.5 rounded-full border border-slate-200/90 shadow-2xs hover:shadow-xs transition group">
                <img src="{{ asset('images/favicon.png') }}" alt="Logo PFPT" class="h-8 w-8 object-contain rounded-full">
                <div class="hidden sm:flex flex-col">
                    <span class="text-xs font-black text-slate-900 leading-none tracking-tight group-hover:text-[#4B6A95] transition">PFPT - CFPT</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-0.5">
                        {{ (auth()->check() && auth()->user()->role && auth()->user()->role->name === 'Agent JICA') ? 'Portail Agent JICA' : 'Portail Administrateur' }}
                    </span>
                </div>
            </a>
        </div>

        <!-- Center: Floating Navigation Pill (Bleu Principal) -->
        <nav class="hidden xl:flex items-center bg-white rounded-full p-1.5 border border-slate-200/90 shadow-2xs text-xs font-semibold text-slate-600 gap-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-5 py-2 rounded-full font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#4B6A95] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                Vue d'ensemble
            </a>
            
            <a href="{{ route('admin.candidates') }}" 
               class="px-4 py-2 rounded-full transition {{ request()->routeIs('admin.candidates*') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                Candidatures
            </a>

            <a href="{{ route('admin.seminars') }}" 
               class="px-4 py-2 rounded-full transition {{ request()->routeIs('admin.seminars*') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                Séminaires
            </a>

            <a href="{{ route('admin.cohorts.index') }}" 
               class="px-4 py-2 rounded-full transition {{ request()->routeIs('admin.cohorts*') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                Éditions
            </a>

            <a href="{{ route('admin.finances') }}" 
               class="px-4 py-2 rounded-full transition {{ request()->routeIs('admin.finances*') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                Finances
            </a>

            <a href="{{ route('admin.resources.index') }}" 
               class="px-4 py-2 rounded-full transition {{ request()->routeIs('admin.resources*') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                Documents
            </a>

            <a href="{{ route('admin.forms.index') }}" 
               class="px-4 py-2 rounded-full transition {{ request()->routeIs('admin.forms.*') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                Formulaires
            </a>

            <a href="{{ route('admin.badges.generator') }}" 
               class="px-4 py-2 rounded-full transition {{ request()->routeIs('admin.badges.*') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                Cartes & Attestations
            </a>
        </nav>

        <!-- Right: Actions & Profile -->
        <div class="flex items-center gap-2 sm:gap-3">
            
            <!-- Search Button -->
            <a href="{{ route('admin.candidates') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200/90 text-slate-600 hover:text-slate-900 hover:bg-slate-50 shadow-2xs flex items-center justify-center transition" title="Rechercher">
                <i data-lucide="search" class="w-4 h-4"></i>
            </a>

            <!-- Notifications Bell -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" 
                        class="w-10 h-10 rounded-full bg-white border border-slate-200/90 text-slate-600 hover:text-slate-900 hover:bg-slate-50 shadow-2xs flex items-center justify-center transition relative focus:outline-none">
                    <i data-lucide="bell" class="w-4 h-4"></i>
                    @php
                        $unreadCount = auth()->user() ? auth()->user()->unreadNotifications()->count() : 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-600 rounded-full ring-2 ring-white"></span>
                    @endif
                </button>

                <!-- Notifications Dropdown -->
                <div x-show="open" 
                     x-transition 
                     class="absolute right-0 top-full mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-[60]" 
                     style="display: none;">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Notifications</span>
                        <span class="text-[10px] font-bold text-slate-400">{{ $unreadCount }} non lues</span>
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                        @if(auth()->user())
                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <a href="{{ $notification->data['link'] ?? '#' }}" class="block px-4 py-3 hover:bg-slate-50 transition text-left {{ $notification->read_at ? 'opacity-60' : 'bg-red-50/30' }}">
                                    <p class="text-xs font-bold text-slate-900">{{ $notification->data['action'] ?? ($notification->data['subject'] ?? 'Notification') }}</p>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $notification->data['description'] ?? ($notification->data['body'] ?? '') }}</p>
                                    <span class="text-[9px] text-slate-400 font-medium block mt-1">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-xs text-slate-400">Aucune nouvelle notification</div>
                            @endforelse
                        @endif
                    </div>
                    <a href="{{ route('admin.notifications.index') }}" class="block w-full text-center py-2.5 text-[11px] font-bold text-slate-600 hover:text-slate-900 bg-slate-50/60 border-t border-slate-100">
                        Voir tout l'historique →
                    </a>
                </div>
            </div>

            <!-- Info / Guide Icon -->
            <a href="{{ route('admin.cohorts.index') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200/90 text-slate-600 hover:text-slate-900 hover:bg-slate-50 shadow-2xs hidden sm:flex items-center justify-center transition" title="Documentation & Info">
                <i data-lucide="info" class="w-4 h-4"></i>
            </a>

            <!-- User Profile Pill -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" 
                        class="bg-white rounded-full p-1 sm:pr-3.5 border border-slate-200/90 shadow-2xs flex items-center gap-2.5 hover:border-slate-300 transition focus:outline-none cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 font-extrabold text-xs flex items-center justify-center border border-red-200/80 overflow-hidden shrink-0">
                        @if(auth()->user() && auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        @endif
                    </div>
                    <div class="hidden sm:flex flex-col text-left">
                        <span class="text-xs font-bold text-slate-900 leading-tight line-clamp-1">{{ auth()->user()->name ?? 'Administrateur' }}</span>
                        <span class="text-[10px] text-slate-400 font-medium leading-tight line-clamp-1">{{ auth()->user()->email ?? 'admin@pfpt.sn' }}</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 hidden sm:block"></i>
                </button>

                <!-- Profile Dropdown -->
                <div x-show="open" 
                     x-transition 
                     class="absolute right-0 top-full mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-[60]" 
                     style="display: none;">
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                        <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name ?? 'Administrateur' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-50 text-red-700 text-[9px] font-extrabold rounded-md uppercase tracking-wider">
                            {{ auth()->user()->role->name ?? 'Admin' }}
                        </span>
                    </div>
                    
                    <div class="p-1 text-xs text-slate-700">
                        <a href="{{ route('password.change') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50 transition font-semibold">
                            <i data-lucide="key" class="w-4 h-4 text-slate-400"></i>
                            Modifier mot de passe
                        </a>
                        <a href="{{ route('welcome') }}" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50 transition font-semibold">
                            <i data-lucide="external-link" class="w-4 h-4 text-slate-400"></i>
                            Voir Portail Public
                        </a>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-1 border-t border-slate-100">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-xs text-red-600 font-bold hover:bg-red-50 rounded-xl flex items-center gap-2 transition">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</header>

<!-- ============================================================== -->
<!-- 3. MAIN CONTENT AREA -->
<!-- ============================================================== -->
<main class="pt-20 min-h-screen bg-[#e9ebf0] transition-all duration-300 ease-in-out lg:ml-20">
    <!-- Global Notifications & Flash Banners -->
    <div class="max-w-[1600px] mx-auto px-4 lg:px-7 pt-4 empty:hidden">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="mb-5 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shrink-0">
                        <span class="material-symbols-outlined text-lg">check</span>
                    </span>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-emerald-900 leading-snug">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-emerald-700 hover:text-emerald-950 p-1.5 rounded-lg hover:bg-emerald-500/10 transition">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="mb-5 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold shrink-0">
                        <span class="material-symbols-outlined text-lg">error</span>
                    </span>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-rose-900 leading-snug">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-rose-700 hover:text-rose-950 p-1.5 rounded-lg hover:bg-rose-500/10 transition">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="mb-5 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shrink-0">
                        <span class="material-symbols-outlined text-lg">warning</span>
                    </span>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-amber-900 leading-snug">{{ session('warning') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-amber-700 hover:text-amber-950 p-1.5 rounded-lg hover:bg-amber-500/10 transition">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="mb-5 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-900 shadow-xs">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <span class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-lg">priority_high</span>
                        </span>
                        <div>
                            <p class="text-xs sm:text-sm font-bold text-rose-950 mb-1">Veuillez corriger les erreurs suivantes :</p>
                            <ul class="list-disc list-inside text-xs text-rose-800 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" @click="show = false" class="text-rose-700 hover:text-rose-950 p-1.5 rounded-lg hover:bg-rose-500/10 transition">
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

    @yield('content')
</main>


<!-- SweetAlert2 for beautiful confirmation popups (non-bloquant) -->
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Override all forms with native confirm()
    document.querySelectorAll('form[onsubmit*="confirm("]').forEach(form => {
        let message = 'Êtes-vous sûr de vouloir effectuer cette action ?';
        let match = form.getAttribute('onsubmit').match(/confirm\(['"](.*?)['"]\)/);
        if (match && match[1]) {
            message = match[1];
        }
        
        form.removeAttribute('onsubmit');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (typeof Swal === 'undefined') {
                if (window.confirm(message)) form.submit();
                return;
            }
            Swal.fire({
                title: 'Confirmation',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[20px] shadow-2xl border border-gray-100 font-body',
                    title: 'font-headline text-xl text-gray-900',
                    confirmButton: 'bg-rose-500 hover:bg-rose-600 text-white font-semibold py-2.5 px-5 rounded-lg transition-colors ml-3 shadow-sm',
                    cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-5 rounded-lg transition-colors border border-gray-200'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>
@stack('scripts')
</body>
</html>