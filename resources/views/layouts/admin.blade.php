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
                        "brand-primary": "#00355f",
                        "brand-light": "#d2e4ff",
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
        /* Hide scrollbar for sidebar when collapsed */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .sidebar-collapsed-hide { display: none; }
    </style>
</head>
<body class="bg-surface text-on-surface overflow-x-hidden" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))">

<!-- Mobile Overlay -->
<div x-show="sidebarOpen" 
     x-transition.opacity 
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden" style="display: none;">
</div>

<aside class="fixed inset-y-0 left-0 z-50 bg-slate-50 border-r border-slate-200/50 transition-all duration-300 ease-in-out flex flex-col h-screen"
       :class="{'translate-x-0': sidebarOpen, '-translate-x-full lg:translate-x-0': !sidebarOpen, 'w-20': sidebarCollapsed, 'w-64': !sidebarCollapsed}">
    <div class="flex flex-col h-full py-8 transition-all duration-300" :class="{'px-2': sidebarCollapsed, 'px-4': !sidebarCollapsed}">
        
        <!-- Logo Area -->
        <div class="flex flex-col items-center mb-10 text-center">
            <img src="{{ asset('images/logo-blue.png') }}" alt="Logo PFPT" class="w-auto mb-2 drop-shadow-sm transition-all duration-300" :class="{'h-8': sidebarCollapsed, 'h-20': !sidebarCollapsed}">
            <div x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms>
                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">Portail d'Administration PFPT</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-2 overflow-y-auto sidebar-scroll transition-all duration-300" :class="{'pr-0': sidebarCollapsed, 'pr-2': !sidebarCollapsed}">
            
            <a class="flex items-center py-2.5 transition-all duration-200 ease-in-out font-manrope text-sm tracking-tight group relative"
               :class="{
                   'justify-center px-0': sidebarCollapsed, 
                   'gap-3 px-5': !sidebarCollapsed,
                   'text-blue-900 font-bold bg-blue-50/50': {{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }},
                   'border-l-4 border-blue-900': {{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }} && !sidebarCollapsed,
                   'text-slate-500 font-medium hover:bg-slate-200/50 rounded-lg': {{ request()->routeIs('admin.dashboard') ? 'false' : 'true' }}
               }" 
               href="{{ route('admin.dashboard') }}" title="Tableau de bord">
                <span class="material-symbols-outlined text-[20px]" {!! request()->routeIs('admin.dashboard') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>dashboard</span>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap transition-opacity duration-300">Tableau de bord</span>
            </a>

            <!-- Catalogue Formation -->
            <div x-data="{ open: {{ request()->routeIs('admin.editions.*') || request()->routeIs('admin.themes.*') || request()->routeIs('admin.departments.*') || request()->routeIs('admin.seminars') ? 'true' : 'false' }} }" class="space-y-1 relative group">
                <button @click="if(!sidebarCollapsed) open = !open" class="w-full flex items-center py-2.5 text-slate-600 font-bold hover:bg-slate-200/50 transition-all text-sm font-manrope rounded-lg"
                        :class="{'justify-center px-0': sidebarCollapsed, 'justify-between px-5': !sidebarCollapsed}" title="Catalogue">
                    <div class="flex items-center" :class="{'gap-0': sidebarCollapsed, 'gap-3': !sidebarCollapsed}">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                        <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Catalogue</span>
                    </div>
                    <span x-show="!sidebarCollapsed" class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-10 space-y-1 pb-1">
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.seminars') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.seminars') }}">Séminaires</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.editions.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.editions.index') }}">Éditions & Budgets</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.themes.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.themes.index') }}">Thèmes</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.departments.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.departments.index') }}">Départements</a>
                </div>
            </div>

            <!-- Scolarité & Suivi -->
            <div x-data="{ open: {{ request()->routeIs('admin.candidates') || request()->routeIs('admin.defenses.*') || request()->routeIs('admin.forms.*') ? 'true' : 'false' }} }" class="space-y-1 relative group">
                <button @click="if(!sidebarCollapsed) open = !open" class="w-full flex items-center py-2.5 text-slate-600 font-bold hover:bg-slate-200/50 transition-all text-sm font-manrope rounded-lg"
                        :class="{'justify-center px-0': sidebarCollapsed, 'justify-between px-5': !sidebarCollapsed}" title="Scolarité">
                    <div class="flex items-center" :class="{'gap-0': sidebarCollapsed, 'gap-3': !sidebarCollapsed}">
                        <span class="material-symbols-outlined text-[20px]">person_book</span>
                        <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Scolarité</span>
                    </div>
                    <span x-show="!sidebarCollapsed" class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-10 space-y-1 pb-1">
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.candidates') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.candidates') }}">Candidats</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.defenses.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.defenses.index') }}">Soutenances</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.forms.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.forms.index') }}">Formulaires</a>
                </div>
            </div>

            <!-- GED & Archivage -->
            <div x-data="{ open: {{ request()->routeIs('admin.documents.*') || request()->routeIs('admin.archives') ? 'true' : 'false' }} }" class="space-y-1 relative group">
                <button @click="if(!sidebarCollapsed) open = !open" class="w-full flex items-center py-2.5 text-slate-600 font-bold hover:bg-slate-200/50 transition-all text-sm font-manrope rounded-lg"
                        :class="{'justify-center px-0': sidebarCollapsed, 'justify-between px-5': !sidebarCollapsed}" title="Documents">
                    <div class="flex items-center" :class="{'gap-0': sidebarCollapsed, 'gap-3': !sidebarCollapsed}">
                        <span class="material-symbols-outlined text-[20px]">folder_open</span>
                        <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Documents</span>
                    </div>
                    <span x-show="!sidebarCollapsed" class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-10 space-y-1 pb-1">
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.documents.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.documents.index') }}">GED (Fichiers)</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.archives') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.archives') }}">Archivage</a>
                </div>
            </div>

            <!-- Administration -->
            <div x-data="{ open: {{ request()->routeIs('admin.finances') || request()->routeIs('admin.users.*') || request()->routeIs('admin.debug.emails') || request()->routeIs('admin.notifications.index') ? 'true' : 'false' }} }" class="space-y-1 relative group">
                <button @click="if(!sidebarCollapsed) open = !open" class="w-full flex items-center py-2.5 text-slate-600 font-bold hover:bg-slate-200/50 transition-all text-sm font-manrope rounded-lg"
                        :class="{'justify-center px-0': sidebarCollapsed, 'justify-between px-5': !sidebarCollapsed}" title="Administration">
                    <div class="flex items-center" :class="{'gap-0': sidebarCollapsed, 'gap-3': !sidebarCollapsed}">
                        <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                        <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Administration</span>
                    </div>
                    <span x-show="!sidebarCollapsed" class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-10 space-y-1 pb-1">
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.finances') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.finances') }}">Finances</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.notifications.index') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.notifications.index') }}">Historique</a>
                    <a class="block py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.debug.emails') ? 'text-blue-900 font-bold' : 'text-slate-500 hover:text-blue-700' }}" href="{{ route('admin.debug.emails') }}">Debugger Emails</a>
                </div>
            </div>
        </nav>

        <div class="mt-auto pt-6 border-t border-slate-200/50 transition-all duration-300">
            <button class="w-full py-3 bg-primary-container hover:opacity-90 text-white rounded-lg font-semibold text-sm transition-all flex items-center justify-center gap-2" 
                    :class="{'px-0': sidebarCollapsed, 'px-4': !sidebarCollapsed}"
                    style="background: linear-gradient(135deg, #0f4c81 0%, #00355f 100%);" title="Nouveau Séminaire">
                <span class="material-symbols-outlined text-sm">add</span>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Nouveau Séminaire</span>
            </button>
        </div>
    </div>
</aside>

<header class="fixed top-0 right-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/50 shadow-sm transition-all duration-300 ease-in-out"
        :class="{'w-full': true, 'lg:w-[calc(100%-5rem)]': sidebarCollapsed, 'lg:w-[calc(100%-16rem)]': !sidebarCollapsed}">
    <div class="flex justify-between items-center h-20 px-4 md:px-8 ml-0 gap-4">
        
        <!-- Left: Toggle & Title -->
        <div class="flex-1 flex items-center gap-3 min-w-0 pr-4">
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = true" class="p-2 -ml-2 rounded-lg hover:bg-gray-100 lg:hidden focus:outline-none text-slate-600">
                <span class="material-symbols-outlined text-[24px]">menu</span>
            </button>
            <!-- Desktop Menu Toggle -->
            <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 -ml-2 rounded-lg hover:bg-gray-100 hidden lg:block focus:outline-none text-slate-600 transition-colors" title="Réduire/Ouvrir le menu">
                <span class="material-symbols-outlined text-[24px]" x-text="sidebarCollapsed ? 'menu' : 'menu_open'">menu_open</span>
            </button>

            <div class="flex flex-col justify-center gap-1 min-w-0 ml-1">
                <h2 class="font-manrope text-base lg:text-lg font-bold text-brand-primary hidden md:block leading-tight truncate">@yield('header_title', 'Administration')</h2>
                <div class="flex items-center gap-2">
                    <div class="hidden md:inline-flex px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-[10px] uppercase tracking-wider font-bold border border-gray-200 shadow-sm self-start whitespace-nowrap">Admin</div>
                    @hasSection('header_subtitle')
                        @yield('header_subtitle')
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Center: Navigation Menu Pill -->
        <div class="hidden xl:flex flex-shrink-0 bg-on-surface rounded-full p-1 text-sm font-medium text-gray-300 shadow-sm">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 {{ request()->routeIs('admin.dashboard*') ? 'bg-brand-primary text-white shadow-md border border-blue-400/30' : 'border border-transparent hover:text-white transition' }} rounded-full flex items-center">Tableau de bord</a>
            <a href="{{ route('admin.candidates') }}" class="px-4 py-2 {{ request()->routeIs('admin.candidates*') ? 'bg-brand-primary text-white shadow-md border border-blue-400/30' : 'border border-transparent hover:text-white transition' }} rounded-full flex items-center">Candidatures</a>
            <a href="{{ route('admin.seminars') }}" class="px-4 py-2 {{ request()->routeIs('admin.seminars*') ? 'bg-brand-primary text-white shadow-md border border-blue-400/30' : 'border border-transparent hover:text-white transition' }} rounded-full flex items-center">Séminaires</a>
            <a href="{{ route('admin.editions.index') }}" class="px-4 py-2 {{ request()->routeIs('admin.editions*') ? 'bg-brand-primary text-white shadow-md border border-blue-400/30' : 'border border-transparent hover:text-white transition' }} rounded-full flex items-center">Éditions</a>
            <a href="{{ route('admin.finances') }}" class="px-4 py-2 {{ request()->routeIs('admin.finances*') ? 'bg-brand-primary text-white shadow-md border border-blue-400/30' : 'border border-transparent hover:text-white transition' }} rounded-full flex items-center">Finances</a>
        </div>

        <!-- Right: Icons & Profile -->
        <div class="flex-1 flex items-center justify-end gap-3 text-slate-500 pl-4">
            <!-- Normal Icons -->
            <a href="{{ route('admin.documents.index') }}" class="p-2.5 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors hidden lg:flex items-center justify-center" title="GED (Documents)">
                <span class="material-symbols-outlined text-[20px] leading-none">description</span>
            </a>
            <button class="p-2.5 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors hidden lg:flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px] leading-none">bolt</span>
            </button>
            <button class="p-2.5 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors hidden lg:flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px] leading-none">calendar_today</span>
            </button>
            
            <!-- Notifications Bell -->
            <div x-data="{ open: false }" class="relative cursor-pointer ml-1">
                <button @click="open = !open" @click.outside="open = false" class="p-2.5 border border-gray-200 rounded-full hover:bg-gray-50 transition-colors relative focus:outline-none flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px] leading-none">notifications</span>
                    @php
                        $unreadCount = auth()->user() ? auth()->user()->unreadNotifications->count() : 0;
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
                        @if(auth()->user())
                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <div class="px-4 py-3 hover:bg-surface-container-low transition-colors border-b border-outline-variant/30 flex gap-3 {{ $notification->read_at ? 'opacity-60' : 'bg-primary/5' }}">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">{{ $notification->data['icon'] ?? 'notifications' }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <p class="text-xs font-bold text-on-surface">{{ $notification->data['action'] ?? '' }}</p>
                                            <span class="text-[9px] text-on-surface-variant italic">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-[11px] text-on-surface-variant line-clamp-2">{{ $notification->data['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-xs text-on-surface-variant italic">Aucune nouvelle notification</div>
                            @endforelse
                        @endif
                    </div>
                    <a href="{{ route('admin.notifications.index') }}" class="block w-full text-center py-2 text-[10px] font-bold text-on-surface-variant hover:bg-surface-container-low border-t border-outline-variant">
                        Voir tout l'historique
                    </a>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative ml-1">
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 cursor-pointer focus:outline-none rounded-full">
                    <div class="w-10 h-10 rounded-full border-2 border-white shadow-sm overflow-hidden bg-brand-light flex items-center justify-center text-brand-primary font-bold hover:shadow-md transition-shadow">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                </button>
                
                <!-- Menu dropdown -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100" 
                     x-transition:enter-start="opacity-0 scale-95" 
                     x-transition:enter-end="opacity-100 scale-100" 
                     x-transition:leave="transition ease-in duration-75" 
                     x-transition:leave-start="opacity-100 scale-100" 
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50"
                     style="display: none;">
                    
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ auth()->user()->name ?? 'Administrateur' }}</p>
                        <p class="text-xs text-gray-500 line-clamp-1">{{ auth()->user()->email ?? 'admin@pfpt.com' }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="m-0 border-t border-gray-100 mt-1">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 font-semibold hover:bg-red-50 flex items-center gap-2 rounded-b-xl transition-colors">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="pt-20 min-h-screen bg-surface transition-all duration-300 ease-in-out"
      :class="{'ml-0': true, 'lg:ml-20': sidebarCollapsed, 'lg:ml-64': !sidebarCollapsed}">
    @yield('content')
</main>

<!-- Floating Button to Portal -->
<a href="{{ route('portal.login') }}" target="_blank" class="fixed bottom-8 right-8 bg-brand-primary text-white p-4 rounded-full shadow-2xl shadow-brand-primary/40 hover:scale-110 transition-transform z-50 flex items-center justify-center group border-2 border-white" title="Accéder au Portail Public">
    <span class="material-symbols-outlined text-[28px]">language</span>
    <span class="absolute right-full mr-4 bg-gray-900 text-white text-xs font-bold px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none shadow-lg">
        Portail Public
        <!-- Petite flèche tooltip -->
        <div class="absolute top-1/2 -right-1 w-2 h-2 bg-gray-900 transform -translate-y-1/2 rotate-45"></div>
    </span>
</a>

</body>
</html>