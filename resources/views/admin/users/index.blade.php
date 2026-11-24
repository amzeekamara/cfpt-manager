@extends('layouts.admin')

@section('header_title', 'Gestion des Profils')

@section('content')
<style>
    /* --- Variables Custom --- */
    :root {
        --primary-blue: #4B6A95;
        --text-dark: #1f2937;
        --text-muted: #8b95a5;
        --text-light: #cbd5e1;
        --border-color: #e5e7eb;
        --table-header-bg: #f3f4f8;
    }

    /* --- Onglets (Tabs) --- */
    .tabs-container {
        display: flex;
        align-items: flex-end;
        padding-left: 20px;
        position: relative;
        z-index: 1;
        margin-bottom: -1px;
    }

    .custom-tab {
        padding: 12px 24px;
        background-color: #c9d3e5;
        color: #6a7c92;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border-radius: 12px 12px 0 0;
        margin-right: 4px;
        transition: background-color 0.2s;
        clip-path: polygon(5% 0, 95% 0, 100% 100%, 0% 100%);
        min-width: 170px;
        text-align: center;
    }

    .custom-tab:hover:not(.active) {
        background-color: #b8c4d8;
    }

    .custom-tab.active {
        background-color: #ffffff;
        color: var(--text-dark);
        font-weight: 700;
        padding: 16px 28px 12px 28px;
        border-radius: 16px 16px 0 0;
        box-shadow: 0 -4px 6px -2px rgba(0, 0, 0, 0.05);
        clip-path: none;
    }

    /* --- Panneau de Contenu (Blanc) --- */
    .content-panel {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px 32px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 2;
    }

    /* --- Barre d'outils (Filtres & Actions) --- */
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-top: 8px;
    }

    .filters {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .filter-group label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .filter-input {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 14px;
        color: var(--text-dark);
        outline: none;
        width: 200px;
        transition: border-color 0.2s;
    }
    
    .filter-input::placeholder { color: #a1a1aa; }
    .filter-input:focus { border-color: var(--primary-blue); }

    /* Fake select for styling consistency */
    .select-wrapper {
        position: relative;
    }
    
    .select-wrapper select {
        appearance: none;
        padding-right: 32px;
        background-color: white;
        color: var(--text-dark);
    }

    .select-wrapper::after {
        content: "⌄";
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-60%);
        pointer-events: none;
        color: #a1a1aa;
        font-size: 16px;
    }

    /* --- Boutons --- */
    .custom-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-outline {
        background-color: transparent;
        border: 1px solid var(--primary-blue);
        color: var(--primary-blue);
        padding: 8px 20px;
    }

    .btn-outline:hover { background-color: #eff6ff; }

    .btn-primary-custom {
        background-color: var(--primary-blue);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
    }

    .btn-primary-custom:hover { background-color: #2563eb; }

    .btn-primary-custom svg {
        width: 16px;
        height: 16px;
    }

    /* --- Table des données --- */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 24px;
    }

    .data-table th {
        background-color: var(--table-header-bg);
        color: var(--text-muted);
        font-weight: 500;
        font-size: 13px;
        text-align: left;
        padding: 14px 16px;
    }

    .data-table th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; padding-left: 24px;}
    .data-table th:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

    .data-table td {
        padding: 16px 16px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #4b5563;
    }

    .data-table td:first-child {
        padding-left: 24px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* --- Actions dans la table --- */
    .table-actions {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        background-color: transparent;
        border: 1px solid var(--primary-blue);
        color: var(--primary-blue);
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-action:hover {
        background-color: #eff6ff;
    }
    
    .btn-action-danger {
        border-color: #ef4444;
        color: #ef4444;
    }
    
    .btn-action-danger:hover {
        background-color: #fef2f2;
    }

    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
</style>

<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Gestion des Comptes Utilisateurs</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Administrez les accès et rôles de l'institution.</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold rounded-full flex items-center gap-2 transition shadow-sm" style="text-decoration: none;">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Ajouter un Utilisateur
                </a>
            </div>
        </div>

    <!-- Onglets -->
    <div class="tabs-container">
        <div class="custom-tab active" onclick="switchTab('staff', this)">Direction & Administration</div>
        <div class="custom-tab" onclick="switchTab('dept-heads', this)">Chefs de Département ({{ $departmentHeads->count() }})</div>
        <div class="custom-tab" onclick="switchTab('trainers', this)">Formateurs ({{ $trainers->count() }})</div>
        <div class="custom-tab" onclick="switchTab('candidates', this)">Étudiants ({{ $candidates->count() }})</div>
    </div>

    <!-- Panneau Principal -->
    <div class="content-panel">
        
        <!-- Barre d'outils -->
        <div class="toolbar">
            <div class="filters">
                <div class="filter-group">
                    <label>Rechercher</label>
                    <input type="text" class="filter-input" placeholder="Nom ou email...">
                </div>
                <div class="filter-group">
                    <label>Rôle</label>
                    <div class="select-wrapper">
                        <select class="filter-input">
                            <option value="" selected>Tous les rôles</option>
                            <option value="admin">Administrateur</option>
                            <option value="trainer">Formateur</option>
                        </select>
                    </div>
                </div>
                <button class="custom-btn btn-outline">Filtrer</button>
            </div>
            
            <button class="custom-btn btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Exporter
            </button>
        </div>

        <!-- Tab Content: Staff -->
        <div id="tab-staff" class="tab-content active">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffUsers as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover shadow-sm">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-primary font-bold text-xs shadow-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                @endif
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role && $user->role->name == 'Administrateur')
                                <span class="text-primary text-xs font-extrabold uppercase tracking-wider">Admin</span>
                            @elseif($user->role && $user->role->name == 'Formateur')
                                <span class="text-emerald-600 text-xs font-extrabold uppercase tracking-wider">Formateur</span>
                            @else
                                <span class="text-slate-500 text-xs font-extrabold uppercase tracking-wider">{{ $user->role ? $user->role->name : 'N/A' }}</span>
                            @endif
                        </td>
                        <td>
                            <div x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'" class="relative inline-block text-left">
                                <button @click="open = !open" @click.outside="open = false" class="p-1 rounded-md hover:bg-slate-100 transition-colors text-slate-500">
                                    <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100" 
                                     x-transition:enter-start="transform opacity-0 scale-95" 
                                     x-transition:enter-end="transform opacity-100 scale-100" 
                                     x-transition:leave="transition ease-in duration-75" 
                                     x-transition:leave-start="transform opacity-100 scale-100" 
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 z-50 mt-2 w-36 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                     style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                            <span class="material-symbols-outlined text-[18px]">edit</span> Éditer
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left font-medium">
                                                <span class="material-symbols-outlined text-[18px]">delete</span> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tab Content: Candidates -->
        <div id="tab-candidates" class="tab-content">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($candidates as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover shadow-sm">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-700 font-bold text-xs shadow-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                @endif
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td><span class="text-orange-500 text-xs font-extrabold uppercase tracking-wider">Candidat</span></td>
                        <td>
                            <div x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'" class="relative inline-block text-left">
                                <button @click="open = !open" @click.outside="open = false" class="p-1 rounded-md hover:bg-slate-100 transition-colors text-slate-500">
                                    <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100" 
                                     x-transition:enter-start="transform opacity-0 scale-95" 
                                     x-transition:enter-end="transform opacity-100 scale-100" 
                                     x-transition:leave="transition ease-in duration-75" 
                                     x-transition:leave-start="transform opacity-100 scale-100" 
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 z-50 mt-2 w-36 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                     style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                            <span class="material-symbols-outlined text-[18px]">edit</span> Éditer
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left font-medium">
                                                <span class="material-symbols-outlined text-[18px]">delete</span> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

         <!-- Tab Content: Formateurs -->
        <div id="tab-trainers" class="tab-content" x-data="{ 
            selectedTrainerModal: false, 
            trainerName: '', 
            trainerEmail: '', 
            trainerSpecialty: '',
            trainerHours: 0,
            trainerSessionsCount: 0,
            trainerRemuneration: 0,
            trainerPaymentStatus: '',
            trainerSessions: [],
            trainerSeminars: []
        }">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Formateur</th>
                        <th>Email</th>
                        <th>Séminaire(s) & Spécialité</th>
                        <th>Volume Horaire Dispensé</th>
                        <th>Rémunération</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainers as $user)
                    @php
                        $trainerModel = $user->trainer;
                        $totalHours = $trainerModel ? $trainerModel->total_hours_taught : 0;
                        $sessionsCount = $trainerModel ? $trainerModel->sessions->count() : 0;
                        $remuneration = $trainerModel ? $trainerModel->seminars->sum('pivot.remuneration') : 0;
                        $paymentStatus = $trainerModel && $trainerModel->seminars->count() > 0 ? $trainerModel->seminars->first()->pivot->payment_status : 'unpaid';
                        
                        $sessionsJson = $trainerModel ? $trainerModel->sessions->map(function($s) {
                            return [
                                'id' => $s->id,
                                'date' => \Carbon\Carbon::parse($s->session_date)->format('d/m/Y'),
                                'seminar' => $s->seminar->title ?? 'Séminaire',
                                'topic' => $s->topic->name ?? 'Général / Atelier',
                                'hours' => $s->hours_taught
                            ];
                        })->values() : collect();

                        $seminarsJson = $trainerModel ? $trainerModel->seminars->map(function($sem) {
                            return [
                                'title' => $sem->title,
                                'remuneration' => $sem->pivot->remuneration ?? 0,
                                'payment_status' => $sem->pivot->payment_status ?? 'unpaid'
                            ];
                        })->values() : collect();
                    @endphp
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover shadow-sm">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs shadow-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $user->name }}</div>
                                    @if($user->specialty)
                                        <div class="text-[11px] text-slate-400 font-medium">{{ $user->specialty }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-xs text-slate-600 font-medium">{{ $user->email }}</span>
                        </td>
                        <td>
                            @if($trainerModel && $trainerModel->seminars->count() > 0)
                                <div class="space-y-1">
                                    @foreach($trainerModel->seminars as $sem)
                                        <div class="text-xs font-semibold text-[#4B6A95] bg-blue-50/80 px-2 py-0.5 rounded-md inline-block max-w-[220px] truncate" title="{{ $sem->title }}">
                                            {{ $sem->title }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs">Aucun séminaire assigné</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-slate-900 text-white rounded-lg text-xs font-black">
                                    {{ $totalHours }} h
                                </span>
                                <span class="text-xs text-slate-500 font-medium">({{ $sessionsCount }} séance{{ $sessionsCount > 1 ? 's' : '' }})</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="font-black text-slate-900 text-xs">{{ number_format($remuneration, 0, ',', ' ') }} CFA</span>
                                <div>
                                    @if($paymentStatus === 'paid')
                                        <span class="text-[10px] font-bold text-emerald-600">Payé</span>
                                    @else
                                        <span class="text-[10px] font-bold text-amber-600">En attente</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                    @click="
                                        trainerName = '{{ addslashes($user->name) }}';
                                        trainerEmail = '{{ addslashes($user->email) }}';
                                        trainerSpecialty = '{{ addslashes($user->specialty ?? '') }}';
                                        trainerHours = {{ $totalHours }};
                                        trainerSessionsCount = {{ $sessionsCount }};
                                        trainerRemuneration = {{ $remuneration }};
                                        trainerPaymentStatus = '{{ $paymentStatus }}';
                                        trainerSessions = {{ json_encode($sessionsJson) }};
                                        trainerSeminars = {{ json_encode($seminarsJson) }};
                                        selectedTrainerModal = true;
                                    "
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-[#4B6A95] text-slate-700 hover:text-white rounded-lg text-xs font-bold transition shadow-2xs"
                                    title="Voir le relevé d'heures et les séances">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    <span>Séances ({{ $totalHours }}h)</span>
                                </button>

                                <div x-data="{ open: false }" :style="open ? 'z-index: 50;' : 'z-index: 0;'" class="relative inline-block text-left">
                                    <button @click="open = !open" @click.outside="open = false" class="p-1 rounded-md hover:bg-slate-100 transition-colors text-slate-500">
                                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                    </button>
                                    
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100" 
                                         x-transition:enter-start="transform opacity-0 scale-95" 
                                         x-transition:enter-end="transform opacity-100 scale-100" 
                                         x-transition:leave="transition ease-in duration-75" 
                                         x-transition:leave-start="transform opacity-100 scale-100" 
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 z-50 mt-2 w-36 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                         style="display: none;">
                                        <div class="py-1">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-slate-50 hover:text-primary">
                                                <span class="material-symbols-outlined mr-3 text-[18px] text-gray-400 group-hover:text-primary">edit</span>
                                                Éditer
                                            </a>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce formateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <span class="material-symbols-outlined mr-3 text-[18px] text-red-500">delete</span>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-500 italic">Aucun formateur trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Modal: Statistiques & Séances d'Enseignement du Formateur -->
            <div x-show="selectedTrainerModal" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="selectedTrainerModal = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div class="inline-block align-bottom bg-white rounded-[24px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200">
                        <div class="p-6 sm:p-8">
                            <!-- Modal Header -->
                            <div class="flex justify-between items-start pb-4 border-b border-slate-100 mb-6">
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-[#4B6A95] block mb-1">Relevé d'Enseignement & Justification</span>
                                    <h3 class="text-xl font-bold text-slate-900" x-text="trainerName"></h3>
                                    <p class="text-xs text-slate-500 mt-0.5" x-text="trainerEmail"></p>
                                </div>
                                <button type="button" @click="selectedTrainerModal = false" class="p-2 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                </button>
                            </div>

                            <!-- KPIs Summary Row -->
                            <div class="grid grid-cols-3 gap-3 mb-6">
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block mb-1">Volume Total</span>
                                    <span class="text-2xl font-black text-slate-900" x-text="trainerHours + ' h'"></span>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block mb-1">Séances Effectuées</span>
                                    <span class="text-2xl font-black text-slate-900" x-text="trainerSessionsCount"></span>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block mb-1">Rémunération</span>
                                    <span class="text-base font-black text-slate-900" x-text="new Intl.NumberFormat('fr-FR').format(trainerRemuneration) + ' CFA'"></span>
                                    <div class="text-[10px] font-bold" :class="trainerPaymentStatus === 'paid' ? 'text-emerald-600' : 'text-amber-600'" x-text="trainerPaymentStatus === 'paid' ? 'Payé' : 'En attente'"></div>
                                </div>
                            </div>

                            <!-- Séances Table -->
                            <div>
                                <h4 class="text-xs font-black uppercase text-slate-700 tracking-wider mb-3 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-[#4B6A95]">history_edu</span>
                                    Détail des séances d'émargement & heures dispensées
                                </h4>

                                <template x-if="trainerSessions.length > 0">
                                    <div class="overflow-x-auto max-h-[260px] overflow-y-auto rounded-xl border border-slate-100">
                                        <table class="w-full text-left text-xs">
                                            <thead class="bg-slate-50 border-b border-slate-100 sticky top-0">
                                                <tr>
                                                    <th class="px-4 py-2.5 font-bold text-slate-500 uppercase text-[10px]">Date</th>
                                                    <th class="px-4 py-2.5 font-bold text-slate-500 uppercase text-[10px]">Séminaire & Thème</th>
                                                    <th class="px-4 py-2.5 font-bold text-slate-500 uppercase text-[10px] text-right">Heures</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                <template x-for="session in trainerSessions" :key="session.id">
                                                    <tr class="hover:bg-slate-50/60 transition">
                                                        <td class="px-4 py-3 font-bold text-slate-800" x-text="session.date"></td>
                                                        <td class="px-4 py-3">
                                                            <div class="font-bold text-slate-900" x-text="session.seminar"></div>
                                                            <div class="text-[11px] text-slate-500" x-text="session.topic"></div>
                                                        </td>
                                                        <td class="px-4 py-3 text-right font-black text-slate-900" x-text="session.hours + ' h'"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </template>

                                <template x-if="trainerSessions.length === 0">
                                    <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                        <span class="material-symbols-outlined text-3xl mb-1 opacity-50">event_busy</span>
                                        <p class="text-xs font-bold text-slate-600">Aucune séance d'émargement enregistrée.</p>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer close button -->
                            <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                                <button type="button" @click="selectedTrainerModal = false" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-full text-xs font-bold transition shadow-xs">
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content: Chefs de Département -->
        <div id="tab-dept-heads" class="tab-content">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Chef de Département</th>
                        <th>Email</th>
                        <th>Département géré</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departmentHeads as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover shadow-sm">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs shadow-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                @endif
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="px-2.5 py-1 rounded-full bg-blue-50 text-[#4B6A95] text-xs font-bold">
                                {{ $user->department?->name ?? $user->managedDepartment?->name ?? 'Non assigné' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action">Éditer</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce chef de département ?');" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-danger">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-500 italic">Aucun Chef de Département enregistré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<script>
    function switchTab(tabId, element) {
        // Update tabs
        document.querySelectorAll('.custom-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        if(element) {
            element.classList.add('active');
        }

        // Update content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById('tab-' + tabId).classList.add('active');
    }
</script>
@endsection
