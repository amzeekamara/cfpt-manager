<!DOCTYPE html>
<html lang="fr" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portail') - PFPT CFPT</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Lucide Icons & Material Symbols -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Google Font: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "brand-primary": "#4B6A95",
                        "brand-light": "#d2e4ff",
                        "brand-dark": "#354e70",
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        headline: ['"Plus Jakarta Sans"', 'sans-serif'],
                        body: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; }
        
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
<body class="bg-[#e9ebf0] text-slate-800 antialiased min-h-screen flex flex-col">

<!-- Page Loader -->
<div id="page-loader" class="fixed inset-0 z-[9999] bg-[#e9ebf0] flex flex-col items-center justify-center transition-opacity duration-300">
    <div class="relative w-16 h-16">
        <div class="absolute inset-0 border-4 border-[#4B6A95]/20 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-[#4B6A95] rounded-full border-t-transparent animate-spin"></div>
    </div>
    <p class="mt-4 text-[#4B6A95] font-bold font-sans animate-pulse text-sm">Chargement...</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loader = document.getElementById('page-loader');
        if (loader) {
            setTimeout(() => {
                loader.classList.add('opacity-0');
                setTimeout(() => loader.style.display = 'none', 300);
            }, 150);
        }
        if (window.lucide) {
            lucide.createIcons();
        }
    });

    window.addEventListener('beforeunload', () => {
        const loader = document.getElementById('page-loader');
        if (loader) {
            loader.style.display = 'flex';
            requestAnimationFrame(() => {
                loader.classList.remove('opacity-0');
            });
        }
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.classList.add('opacity-0');
                loader.style.display = 'none';
            }
        }
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>

    @php
        $isTrainer = session()->has('portal_trainer_id');
        $isCandidate = session()->has('portal_candidate_id');
        $portalUser = null;
        $portalName = 'Utilisateur';
        $portalEmail = '';
        $portalRoleTitle = 'Portail Utilisateur';
        $avatarUrl = '';

        if ($isTrainer) {
            $portalUser = \App\Models\Trainer::find(session('portal_trainer_id'));
            $portalName = $portalUser ? ($portalUser->user ? $portalUser->user->name : $portalUser->name ?? 'Formateur') : 'Formateur';
            $portalEmail = $portalUser && $portalUser->user ? $portalUser->user->email : ($portalUser->email ?? '');
            $portalRoleTitle = 'Espace Formateur';
            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($portalName) . '&background=4B6A95&color=fff&size=128';
        } elseif ($isCandidate) {
            $portalUser = auth()->user();
            $portalName = $portalUser ? $portalUser->name : 'Candidat';
            $portalEmail = $portalUser ? $portalUser->email : '';
            $portalRoleTitle = 'Espace Candidat';
            if ($portalUser && !empty($portalUser->avatar)) {
                $avatarUrl = asset('storage/' . $portalUser->avatar);
            } else {
                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($portalName) . '&background=4B6A95&color=fff&size=128';
            }
        } elseif (auth()->check()) {
            $portalUser = auth()->user();
            $portalName = $portalUser->name;
            $portalEmail = $portalUser->email;
            $portalRoleTitle = $portalUser->role ? $portalUser->role->name : 'Utilisateur';
            if (!empty($portalUser->avatar)) {
                $avatarUrl = asset('storage/' . $portalUser->avatar);
            } else {
                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($portalName) . '&background=4B6A95&color=fff&size=128';
            }
        }
    @endphp

    <!-- ============================================================== -->
    <!-- TOP HORIZONTAL NAVBAR (STYLE FINEXY - LOGO FAVICON & BRANDING BLEU) -->
    <!-- ============================================================== -->
    <header class="fixed top-0 right-0 left-0 z-40 bg-[#e9ebf0]/95 backdrop-blur-md border-b border-slate-200/80 transition-all duration-300">
        <div class="flex items-center justify-between h-20 px-4 md:px-8 max-w-[1600px] mx-auto gap-4">
            
            <!-- Left: Logo Area -->
            <div class="flex items-center gap-3">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2.5 bg-white px-3.5 py-1.5 rounded-full border border-slate-200/90 shadow-2xs hover:shadow-xs transition group">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo PFPT" class="h-8 w-8 object-contain rounded-full">
                    <div class="hidden sm:flex flex-col">
                        <span class="text-xs font-black text-slate-900 leading-none tracking-tight group-hover:text-[#4B6A95] transition">PFPT - CFPT</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-0.5">{{ $portalRoleTitle }}</span>
                    </div>
                </a>
            </div>

            <!-- Center: Floating Navigation Pill -->
            <nav class="hidden md:flex items-center bg-white rounded-full p-1.5 border border-slate-200/90 shadow-2xs text-xs font-semibold text-slate-600 gap-1">
                @if($isTrainer)
                    <a href="{{ route('portal.trainer.dashboard') }}" 
                       class="px-5 py-2 rounded-full font-bold transition {{ request()->routeIs('portal.trainer.dashboard') ? 'bg-[#4B6A95] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Vue d'ensemble
                    </a>
                    <a href="{{ route('portal.trainer.attendances') }}" 
                       class="px-4 py-2 rounded-full transition {{ request()->routeIs('portal.trainer.attendances*') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Émargements & Présences
                    </a>
                @elseif($isCandidate)
                    <a href="{{ route('portal.candidate.dashboard') }}" 
                       class="px-5 py-2 rounded-full font-bold transition {{ request()->routeIs('portal.candidate.dashboard') ? 'bg-[#4B6A95] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Mon Tableau de bord
                    </a>
                    <a href="{{ route('portal.candidate.profile.edit') }}" 
                       class="px-4 py-2 rounded-full transition {{ request()->routeIs('portal.candidate.profile.edit') ? 'bg-[#4B6A95] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Mettre à jour mon profil
                    </a>
                @endif
                <a href="{{ route('welcome') }}" target="_blank" class="px-4 py-2 rounded-full text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition flex items-center gap-1.5">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>Site public</span>
                </a>
            </nav>

            <!-- Right: Actions & User Profile Pill -->
            <div class="flex items-center gap-2.5 sm:gap-3.5">
                
                <!-- Date Indicator Pill -->
                <div class="hidden xl:flex items-center gap-2 bg-white/80 px-3.5 py-1.5 rounded-full border border-slate-200/80 shadow-2xs text-xs font-semibold text-slate-600">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#4B6A95]"></i>
                    <span>{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('D MMM YYYY') }}</span>
                </div>

                <!-- Notifications Flyout -->
                @php
                    $notifications = collect();
                    $unreadCount = 0;
                    if ($isTrainer && $portalUser) {
                        $notifications = $portalUser->notifications()->take(5)->get();
                        $unreadCount = $portalUser->unreadNotifications()->count();
                    } elseif (auth()->check()) {
                        $notifications = auth()->user()->notifications()->take(5)->get();
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    }
                @endphp
                
                <div x-data="{ notifOpen: false }" class="relative" @click.outside="notifOpen = false">
                    <button @click="notifOpen = !notifOpen" 
                            class="relative p-2.5 rounded-full bg-white hover:bg-slate-50 border border-slate-200/90 text-slate-600 shadow-2xs transition flex items-center justify-center"
                            title="Notifications">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-show="notifOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-200 p-3 z-50"
                         style="display: none;">
                        <div class="flex items-center justify-between px-3 py-2 border-b border-slate-100 mb-2">
                            <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Notifications</span>
                            @if($unreadCount > 0)
                                <span class="text-[10px] bg-red-50 text-red-600 font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }} nouvelle(s)</span>
                            @endif
                        </div>
                        <div class="space-y-1 max-h-72 overflow-y-auto">
                            @forelse($notifications as $notif)
                                <div class="p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100 {{ is_null($notif->read_at) ? 'bg-blue-50/40' : '' }}">
                                    <p class="text-xs text-slate-800 {{ is_null($notif->read_at) ? 'font-bold' : 'font-medium' }}">{{ $notif->data['message'] ?? 'Notification système' }}</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <div class="py-6 text-center text-slate-400 text-xs">
                                    <i data-lucide="bell-off" class="w-6 h-6 mx-auto mb-1 opacity-40"></i>
                                    Aucune notification pour le moment.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Profile Pill & Dropdown -->
                <div x-data="{ profileOpen: false }" class="relative" @click.outside="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" 
                            class="flex items-center gap-2.5 bg-white pl-2 pr-3 py-1.5 rounded-full border border-slate-200/90 shadow-2xs hover:shadow-xs transition group">
                        <img src="{{ $avatarUrl }}" alt="{{ $portalName }}" class="h-7 w-7 rounded-full object-cover ring-2 ring-slate-100">
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-slate-800 group-hover:text-[#4B6A95] transition leading-none">{{ Str::limit($portalName, 18) }}</span>
                            <span class="text-[9px] font-bold text-slate-400 leading-none mt-0.5">{{ $portalRoleTitle }}</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600 transition ml-0.5"></i>
                    </button>

                    <!-- Profile Dropdown Menu -->
                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50"
                         style="display: none;">
                        
                        <div class="px-3 py-2.5 border-b border-slate-100 mb-1.5">
                            <p class="text-xs font-black text-slate-900">{{ $portalName }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ $portalEmail }}</p>
                        </div>

                        <div class="space-y-0.5 text-xs font-semibold text-slate-700">
                            @if($isCandidate)
                                <a href="{{ route('portal.candidate.profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-50 hover:text-[#4B6A95] transition">
                                    <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                    <span>Mon Profil</span>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="m-0 pt-1 border-t border-slate-100">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-red-50 text-red-600 transition font-bold text-left">
                                    <i data-lucide="log-out" class="w-4 h-4 text-red-500"></i>
                                    <span>Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content Canvas -->
    <main class="flex-grow w-full max-w-[1600px] mx-auto px-4 md:px-8 pt-24 pb-12">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-2xs">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span class="text-xs sm:text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-2xs">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                <span class="text-xs sm:text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white/60 backdrop-blur-xs border-t border-slate-200/80 py-5 mt-auto">
        <div class="max-w-[1600px] mx-auto px-4 md:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 font-medium">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-4 w-4 rounded-full">
                <span>&copy; {{ date('Y') }} PFPT - CFPT / JICA. Tous droits réservés.</span>
            </div>
            <div class="flex items-center gap-4 text-[11px] text-slate-400">
                <span>Plateforme Sécurisée</span>
                <span>-</span>
                <span>Support & Assistance</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
