@extends('portal.layout')
@section('title', 'Espace Candidat - Tableau de bord')

@section('content')
<div x-data="{
    activeTab: 'formation',
    msgModal: { open: false, sender: '', date: '', subject: '', body: '', attachment: '', attachmentName: '' },
    openMsg(data) {
        this.msgModal = { open: true, ...data };
    },
    closeMsg() {
        this.msgModal.open = false;
    }
}">

    <!-- Breadcrumb & Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-2 text-xs text-slate-500 font-semibold">
            <span>Espace Candidat</span>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <span class="text-slate-900 font-black">Mon Tableau de Bord</span>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('portal.candidate.profile.edit') }}" class="px-5 py-2.5 bg-[#4B6A95] text-white text-xs font-bold rounded-full hover:bg-[#354e70] shadow-xs flex items-center gap-2 transition-all">
                <i data-lucide="user-cog" class="w-4 h-4"></i>
                <span>Modifier mon dossier / profil</span>
            </a>
        </div>
    </div>

    <!-- TOP SECTION: Combined Profile Card + Remarques Box -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- LEFT 2 COLS: Profile Summary & Personal Details Grid -->
        <div class="lg:col-span-2 bg-white rounded-[28px] border border-slate-200/90 shadow-sm overflow-hidden flex flex-col md:flex-row">
            
            <!-- Left Sub-Box: Avatar, Name, Email, Stats & Status with Centre Image Background -->
            <div class="md:w-1/3 relative flex flex-col items-center text-center p-6 justify-between overflow-hidden text-white shrink-0"
                 style="background: linear-gradient(180deg, rgba(75, 106, 149, 0.88) 0%, rgba(26, 38, 57, 0.96) 100%), url('{{ asset('images/centre.png') }}') center/cover no-repeat;">
                
                <div class="flex flex-col items-center w-full">
                    <div class="w-24 h-24 rounded-full border-4 border-white/30 shadow-xl overflow-hidden bg-white/10 flex items-center justify-center text-white text-3xl font-bold mb-3 shrink-0 backdrop-blur-xs">
                        @if(auth()->user() && auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @elseif(isset($profile) && $profile->photo_path)
                            <img src="{{ asset('storage/' . $profile->photo_path) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($profile->first_names, 0, 1) }}{{ substr($profile->last_name, 0, 1) }}
                        @endif
                    </div>

                    <h2 class="text-base font-black text-white leading-tight mb-1">
                        {{ $profile->first_names }} {{ $profile->last_name }}
                    </h2>
                    <p class="text-[11px] text-white/80 font-medium break-all mb-4">{{ $profile->user->email }}</p>

                    <!-- Stats Counters -->
                    <div class="flex items-center justify-center gap-4 py-2 px-4 bg-white/15 backdrop-blur-md rounded-2xl border border-white/20 text-xs w-full mb-4">
                        <div class="text-center">
                            <span class="text-base font-black text-white block leading-tight">{{ $messages->count() }}</span>
                            <span class="text-[9px] font-bold text-white/80 uppercase tracking-wider">Messages</span>
                        </div>
                        <div class="w-px h-6 bg-white/30"></div>
                        <div class="text-center">
                            <span class="text-base font-black text-emerald-300 block leading-tight">{{ $resources->count() }}</span>
                            <span class="text-[9px] font-bold text-white/80 uppercase tracking-wider">Supports</span>
                        </div>
                    </div>
                </div>

                <!-- Status Badge (Clean text & icon without heavy background) -->
                <div class="w-full pt-1">
                    @if($registration && $registration->status === 'approved')
                        <div class="w-full py-1 text-emerald-300 font-bold text-xs flex items-center justify-center gap-1.5 drop-shadow-xs">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                            <span class="tracking-wide">Inscription Validée</span>
                        </div>
                    @elseif($registration && $registration->status === 'pending')
                        <div class="w-full py-1 text-amber-300 font-bold text-xs flex items-center justify-center gap-1.5 drop-shadow-xs">
                            <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
                            <span class="tracking-wide">En cours de validation</span>
                        </div>
                    @elseif($registration && $registration->status === 'rejected')
                        <div class="w-full py-1 text-rose-300 font-bold text-xs flex items-center justify-center gap-1.5 drop-shadow-xs">
                            <i data-lucide="x-circle" class="w-4 h-4 text-rose-400"></i>
                            <span class="tracking-wide">Non Retenu</span>
                        </div>
                    @else
                        <div class="w-full py-1 text-white/80 font-bold text-xs flex items-center justify-center drop-shadow-xs">
                            Aucune inscription
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Sub-Box: Personal Details Grid -->
            <div class="md:w-2/3 p-6 sm:p-8 flex flex-col justify-between bg-white">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                        <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Fiche Candidat & Coordonnées</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-4 gap-x-4 text-xs">
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Genre</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ ucfirst($profile->gender ?? 'Non renseigné') }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Date de Naissance</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $profile->birth_date ? \Carbon\Carbon::parse($profile->birth_date)->format('d M Y') : 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Téléphone</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $profile->phone ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Profession</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $profile->current_profession ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Employeur</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $profile->current_employer ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Diplôme</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $profile->highest_degree ?? 'N/A' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Nationalité</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $profile->nationality ?? 'Ivoirienne' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Édition</span>
                            <strong class="text-[#4B6A95] font-bold text-sm">{{ $registration->seminar->cohort->title ?? 'Édition 2026 – 2027' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block mb-0.5 text-[11px]">Date Dépôt</span>
                            <strong class="text-slate-800 font-bold text-sm">{{ $registration ? $registration->created_at->format('d M Y') : 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: Annonces & Remarques Box -->
        <div class="lg:col-span-1 bg-white rounded-[28px] border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="font-black text-sm text-slate-900 flex items-center gap-2">
                        <i data-lucide="megaphone" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Annonces & Remarques</span>
                    </h3>
                    <span class="text-xs text-[#4B6A95] font-bold cursor-pointer hover:underline" @click="activeTab = 'messages'">Boîte de réception</span>
                </div>

                <!-- Clean Information text without background box -->
                <div class="text-xs leading-relaxed mb-4 space-y-1 py-1">
                    <div class="flex items-center gap-1.5 font-bold text-[#4B6A95] text-xs">
                        <i data-lucide="info" class="w-3.5 h-3.5 text-[#4B6A95]"></i>
                        <span>Information de formation</span>
                    </div>
                    <p class="text-slate-600 text-[11.5px]">Consultez régulièrement les ressources et supports partagés par vos formateurs avant chaque séance de travaux dirigés.</p>
                </div>

                @if($messages->count() > 0)
                    @php $latestMsg = $messages->first(); @endphp
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                        <div class="font-bold text-slate-900 flex items-center justify-between mb-1">
                            <span>Message récent</span>
                            <span class="text-[10px] text-slate-400 font-normal">{{ $latestMsg->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 line-clamp-2">{{ $latestMsg->content }}</p>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-400 text-center italic">
                        Aucun nouveau message pour le moment.
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-semibold">{{ $messages->count() }} message(s)</span>
                <button @click="activeTab = 'messages'" type="button" class="px-4 py-2 bg-[#4B6A95] text-white font-bold rounded-full hover:bg-[#354e70] transition-all shadow-xs">
                    Consulter
                </button>
            </div>
        </div>

    </div>

    <!-- BOTTOM SECTION: Tabs & Timeline (Left 2 cols) + Files Box (Right 1 col) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT 2 COLS: Tabs & Timeline Container -->
        <div class="lg:col-span-2 bg-white rounded-[28px] border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <!-- Tab Navigation Pills -->
                <div class="flex items-center gap-2 border-b border-slate-100 pb-4 mb-6 overflow-x-auto">
                    <button @click="activeTab = 'formation'" 
                            :class="{ 'bg-[#4B6A95] text-white shadow-xs': activeTab === 'formation', 'bg-slate-100 text-slate-600 hover:bg-slate-200': activeTab !== 'formation' }"
                            class="px-5 py-2.5 rounded-full font-bold text-xs transition-all whitespace-nowrap">
                        Ma Formation & Séminaire
                    </button>
                    <button @click="activeTab = 'messages'" 
                            :class="{ 'bg-[#4B6A95] text-white shadow-xs': activeTab === 'messages', 'bg-slate-100 text-slate-600 hover:bg-slate-200': activeTab !== 'messages' }"
                            class="px-5 py-2.5 rounded-full font-bold text-xs transition-all whitespace-nowrap flex items-center gap-1.5">
                        <span>Messages</span>
                        @if($messages->count() > 0)
                            <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px] font-black">{{ $messages->count() }}</span>
                        @endif
                    </button>
                </div>

                <!-- TAB 1: FORMATION & TIMELINE -->
                <div x-show="activeTab === 'formation'" class="space-y-4">
                    @if($registration && $registration->seminar)
                        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <!-- Clean green text without background -->
                                    <span class="text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                                        Séminaire Actif
                                    </span>
                                    <span class="text-xs font-semibold text-slate-400">
                                        {{ $registration->seminar->start_date ? $registration->seminar->start_date->format('d M Y à H:i') : 'Date à venir' }}
                                    </span>
                                </div>
                                <h4 class="text-base font-black text-slate-900">{{ $registration->seminar->title }}</h4>
                                <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                                    <span>Thème : <strong class="text-[#4B6A95] font-bold">{{ $registration->topic->name ?? 'Général' }}</strong></span>
                                    <span>-</span>
                                    <span>Cohorte : <strong class="text-slate-800">{{ $registration->seminar->cohort->title ?? 'N/A' }}</strong></span>
                                </p>
                            </div>
                            <div class="shrink-0">
                                <span class="px-4 py-2 rounded-full bg-slate-50 border border-slate-200 text-slate-800 text-xs font-bold shadow-2xs">
                                    Statut : <span class="text-emerald-600">{{ ucfirst($registration->status) }}</span>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                            <p class="font-bold text-slate-700 text-sm">Aucun séminaire attribué pour le moment.</p>
                            <p class="mt-1 text-slate-400">Votre dossier sera mis à jour dès validation par le comité de pilotage.</p>
                        </div>
                    @endif
                </div>

                <!-- TAB 2: MESSAGES -->
                <div x-show="activeTab === 'messages'" class="space-y-3" style="display: none;">
                    @forelse($messages as $msg)
                        <button type="button"
                            @click="openMsg({
                                sender: '{{ addslashes($msg->trainer->name ?? 'Formateur / Admin') }}',
                                date: '{{ $msg->created_at->format('d M Y, H:i') }}',
                                subject: '{{ addslashes($msg->subject ?? 'Sans objet') }}',
                                body: '{{ addslashes($msg->body ?? $msg->content ?? '') }}',
                                attachment: '{{ $msg->attachment_path ? asset('storage/' . $msg->attachment_path) : '' }}',
                                attachmentName: '{{ $msg->attachment_path ? basename($msg->attachment_path) : '' }}'
                            })"
                            class="w-full text-left p-4 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-white hover:shadow-md transition-all cursor-pointer group">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-[#4B6A95] text-white font-bold flex items-center justify-center text-[10px]">
                                        {{ substr($msg->trainer->name ?? 'F', 0, 1) }}
                                    </div>
                                    <strong class="text-xs font-bold text-slate-900">{{ $msg->trainer->name ?? 'Formateur / Admin' }}</strong>
                                    @if($msg->attachment_path)
                                        <i data-lucide="paperclip" class="w-3.5 h-3.5 text-slate-400"></i>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-slate-400 font-semibold">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                                    <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-300 group-hover:text-[#4B6A95] transition-colors"></i>
                                </div>
                            </div>
                            <p class="text-xs font-bold text-slate-800 mb-1">{{ $msg->subject ?? 'Sans objet' }}</p>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{{ $msg->body ?? $msg->content ?? '' }}</p>
                        </button>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <i data-lucide="mail-check" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                            <p>Aucun message reçu dans votre boîte.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- RIGHT 1 COL: Files / Pedagogical Resources Box -->
        <div class="lg:col-span-1 bg-white rounded-[28px] border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="font-black text-sm text-slate-900 flex items-center gap-2">
                        <i data-lucide="folder" class="w-4 h-4 text-[#4B6A95]"></i>
                        <span>Documents & Supports</span>
                    </h3>
                    <span class="text-xs font-bold text-slate-400">{{ $resources->count() }} fichier(s)</span>
                </div>

                <div class="space-y-2.5">
                    @forelse($resources as $res)
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#4B6A95] flex items-center justify-center shrink-0">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <div class="truncate">
                                    <div class="text-xs font-bold text-slate-800 truncate" title="{{ $res->title }}">{{ $res->title }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $res->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $res->file_path) }}" target="_blank" download class="p-2 text-slate-400 hover:text-[#4B6A95] hover:bg-blue-50 rounded-xl transition-colors shrink-0" title="Télécharger">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </a>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            <i data-lucide="folder-x" class="w-8 h-8 mx-auto mb-1 opacity-40"></i>
                            <p>Aucun fichier de formation disponible.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- MESSAGE POPUP MODAL -->
    <div x-show="msgModal.open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="closeMsg()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">

        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-xs" @click="closeMsg()"></div>

        <!-- Modal Card -->
        <div x-show="msgModal.open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden z-10 border border-slate-200">

            <!-- Modal Header -->
            <div class="px-6 py-5 flex items-center justify-between bg-slate-900 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#4B6A95] flex items-center justify-center text-white font-black text-base">
                        <span x-text="msgModal.sender.charAt(0)"></span>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm leading-tight" x-text="msgModal.sender"></p>
                        <p class="text-slate-400 text-[11px] font-medium" x-text="msgModal.date"></p>
                    </div>
                </div>
                <button @click="closeMsg()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-5">
                <!-- Subject -->
                <div class="mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Objet</p>
                    <p class="text-sm font-bold text-slate-900" x-text="msgModal.subject"></p>
                </div>

                <!-- Message Body -->
                <div class="mb-5">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Message</p>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-xs text-slate-700 leading-relaxed max-h-52 overflow-y-auto whitespace-pre-wrap" x-text="msgModal.body"></div>
                </div>

                <!-- Attachment (shown only if present) -->
                <template x-if="msgModal.attachment">
                    <div class="mb-5">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Pièce jointe</p>
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-2xl border border-blue-100">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-[#4B6A95] flex items-center justify-center shrink-0">
                                <i data-lucide="paperclip" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 truncate">
                                <p class="text-xs font-bold text-slate-800 truncate" x-text="msgModal.attachmentName"></p>
                                <p class="text-[10px] text-slate-400">Fichier joint au message</p>
                            </div>
                            <a :href="msgModal.attachment" target="_blank" download
                               class="shrink-0 flex items-center gap-1.5 px-3.5 py-2 bg-[#4B6A95] text-white text-xs font-bold rounded-full hover:bg-[#354e70] transition-all">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                <span>Télécharger</span>
                            </a>
                        </div>
                    </div>
                </template>

                <!-- Close button -->
                <button @click="closeMsg()"
                        class="w-full py-3 rounded-full bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
