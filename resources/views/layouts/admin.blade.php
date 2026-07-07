<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>PFPT CFPT - Administration</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-container": "#d5e0f8",
                        "on-surface": "#191c1e",
                        "on-error-container": "#93000a",
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
                        "primary": "#00355f",
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
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-surface text-on-surface">

<aside class="h-screen w-64 fixed left-0 top-0 border-r-0 bg-slate-50 z-50">
    <div class="flex flex-col h-full py-8 px-4">
        <div class="flex flex-col items-center mb-10 px-2 text-center">
            <img src="{{ asset('images/logo-blue.png') }}" alt="Logo PFPT" class="h-20 w-auto mb-2 drop-shadow-sm">
            <div>
                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">Portail d'Administration PFPT</p>
            </div>
        </div>
        <nav class="flex-1 space-y-2 overflow-y-auto pr-2">
            <a class="flex items-center gap-3 py-2.5 transition-all duration-200 ease-in-out font-manrope text-sm tracking-tight {{ request()->routeIs('admin.dashboard') ? 'text-blue-900 font-bold border-l-4 border-blue-900 pl-4 bg-blue-50/50' : 'px-5 text-slate-500 font-medium hover:bg-slate-200/50 rounded-lg' }}" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined text-[20px]" {!! request()->routeIs('admin.dashboard') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>dashboard</span>
                <span>Tableau de bord</span>
            </a>

            <!-- Catalogue Formation -->
            <div x-data="{ open: {{ request()->routeIs('admin.editions.*') || request()->routeIs('admin.themes.*') || request()->routeIs('admin.departments.*') || request()->routeIs('admin.seminars') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between py-2.5 px-5 text-slate-600 font-bold hover:bg-slate-200/50 rounded-lg transition-all text-sm font-manrope">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                        <span>Catalogue</span>
                    </div>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-10 space-y-1 pb-1">
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.seminars') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.seminars') }}">Séminaires</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.editions.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.editions.index') }}">Éditions & Budgets</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.themes.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.themes.index') }}">Thèmes</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.departments.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.departments.index') }}">Départements</a>
                </div>
            </div>

            <!-- Scolarité & Suivi -->
            <div x-data="{ open: {{ request()->routeIs('admin.candidates') || request()->routeIs('admin.defenses.*') || request()->routeIs('admin.forms.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between py-2.5 px-5 text-slate-600 font-bold hover:bg-slate-200/50 rounded-lg transition-all text-sm font-manrope">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">person_book</span>
                        <span>Scolarité</span>
                    </div>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-10 space-y-1 pb-1">
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.candidates') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.candidates') }}">Candidats</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.defenses.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.defenses.index') }}">Soutenances</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.forms.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.forms.index') }}">Formulaires</a>
                </div>
            </div>

            <!-- GED & Archivage -->
            <div x-data="{ open: {{ request()->routeIs('admin.documents.*') || request()->routeIs('admin.archives') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between py-2.5 px-5 text-slate-600 font-bold hover:bg-slate-200/50 rounded-lg transition-all text-sm font-manrope">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">folder_open</span>
                        <span>Documents</span>
                    </div>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-10 space-y-1 pb-1">
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.documents.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.documents.index') }}">GED (Fichiers)</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.archives') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.archives') }}">Archivage</a>
                </div>
            </div>

            <!-- Administration -->
            <div x-data="{ open: {{ request()->routeIs('admin.finances') || request()->routeIs('admin.users.*') || request()->routeIs('admin.debug.emails') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between py-2.5 px-5 text-slate-600 font-bold hover:bg-slate-200/50 rounded-lg transition-all text-sm font-manrope">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                        <span>Administration</span>
                    </div>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-10 space-y-1 pb-1">
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.finances') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.finances') }}">Finances</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.debug.emails') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.debug.emails') }}">Debugger Emails</a>
                </div>
            </div>
        </nav>
        <div class="mt-auto pt-6 border-t border-slate-200/50">
            <button class="w-full py-3 px-4 bg-primary-container hover:opacity-90 text-white rounded-lg font-semibold text-sm transition-all flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);">
                <span class="material-symbols-outlined text-sm">add</span>
                Nouveau Séminaire
            </button>
        </div>
    </div>
</aside>

<header class="fixed top-0 right-0 w-[calc(100%-16rem)] z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/50 shadow-sm">
    <div class="flex justify-between items-center h-16 px-8 ml-0">
        <h2 class="font-manrope headline-sm font-semibold text-blue-800">@yield('header_title', 'Administration')</h2>
        <div class="flex items-center gap-6">
            <div class="relative hidden lg:block">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input class="bg-surface-container-low border-none rounded-full pl-10 pr-4 py-1.5 text-sm w-64 focus:ring-2 focus:ring-primary/20" placeholder="Rechercher..." type="text"/>
            </div>
            <div class="flex items-center gap-4 text-slate-500">
                <!-- Notifications Bell -->
                <div x-data="{ open: false }" class="relative cursor-pointer">
                    <button @click="open = !open" @click.outside="open = false" class="relative focus:outline-none flex items-center justify-center h-full">
                        <span class="material-symbols-outlined hover:text-blue-700 transition-colors">notifications</span>
                        @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95" 
                         class="absolute right-0 top-full mt-4 w-80 bg-white rounded-2xl shadow-xl border border-outline-variant overflow-hidden z-[60]" 
                         style="display: none;">
                        <div class="px-4 py-3 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
                            <span class="text-xs font-bold text-on-surface uppercase">Notifications</span>
                            @if($unreadCount > 0)
                                <a href="#" class="text-[10px] font-bold text-primary hover:underline">Tout marquer comme lu</a>
                            @endif
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <div class="px-4 py-3 hover:bg-surface-container-low transition-colors border-b border-outline-variant/30 flex gap-3 {{ $notification->read_at ? 'opacity-60' : 'bg-primary/5' }}">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">{{ $notification->data['icon'] ?? 'notifications' }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <p class="text-xs font-bold text-on-surface">{{ $notification->data['action'] }}</p>
                                            <span class="text-[9px] text-on-surface-variant italic">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-[11px] text-on-surface-variant line-clamp-2">{{ $notification->data['description'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-xs text-on-surface-variant italic">Aucune nouvelle notification</div>
                            @endforelse
                        </div>
                        <a href="#" class="block w-full text-center py-2 text-[10px] font-bold text-on-surface-variant hover:bg-surface-container-low border-t border-outline-variant">
                            Voir tout l'historique
                        </a>
                    </div>
                </div>

                <div class="h-8 w-[1px] bg-slate-200 mx-1"></div>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 cursor-pointer focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-blue-900 border border-slate-200 flex justify-center items-center text-white text-xs font-bold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
                        <span class="text-xs font-semibold text-on-surface">{{ auth()->user()->name ?? 'Administrateur' }}</span>
                        <span class="material-symbols-outlined text-[18px] text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                    </button>
                    
                    <!-- Menu dropdown (logout) hidden by default, shown on click -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-100 z-50"
                         style="display: none;">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 font-semibold hover:bg-slate-50 flex items-center gap-2 rounded-lg">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="ml-64 pt-16 min-h-screen bg-surface">
    @yield('content')
</main>

</body>
</html>
