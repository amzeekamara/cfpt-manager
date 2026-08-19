@extends('layouts.admin')

@section('header_title', 'Catalogue des Séminaires')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Séminaires</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Créez et planifiez les sessions de formation continue.</p>
                </div>
            </div>
            @if(auth()->user()->role->name !== 'Agent JICA')
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.seminars.create') }}" class="px-5 py-2.5 bg-[#4B6A95] hover:bg-[#3c5678] text-white text-xs font-bold rounded-full flex items-center gap-2 transition shadow-sm">
                    <span class="material-symbols-outlined text-[18px] leading-none">add_task</span>
                    Créer un Séminaire
                </a>
            </div>
            @endif
        </header>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 mb-8 bg-white/70 backdrop-blur-xs p-1.5 rounded-full w-fit border border-slate-200 shadow-2xs" id="tabs">
            <button onclick="switchTab('active')" id="tab-active" class="tab-btn px-5 py-2 bg-[#4B6A95] text-white font-bold text-xs rounded-full shadow-xs transition">Actifs ({{ $activeSeminars->count() }})</button>
            <button onclick="switchTab('draft')" id="tab-draft" class="tab-btn px-5 py-2 text-slate-600 hover:text-slate-900 font-bold text-xs rounded-full hover:bg-slate-100 transition">Brouillons ({{ $draftSeminars->count() }})</button>
            <button onclick="switchTab('completed')" id="tab-completed" class="tab-btn px-5 py-2 text-slate-600 hover:text-slate-900 font-bold text-xs rounded-full hover:bg-slate-100 transition">Passés ({{ $pastSeminars->count() }})</button>
        </div>

        @php
            $groups = [
                'active' => $activeSeminars,
                'draft' => $draftSeminars,
                'completed' => $pastSeminars
            ];
        @endphp

        @foreach($groups as $status => $seminars)
        <!-- Seminar Grid -->
        <div id="grid-{{ $status }}" class="seminar-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 {{ $status !== 'active' ? 'hidden' : '' }}">
            
            @if($status === 'active' || $status === 'draft')
            <!-- Create New Card Placeholder -->
            @if(auth()->user()->role->name !== 'Agent JICA')
            <a href="{{ route('admin.seminars.create') }}" class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-200 hover:border-brand-primary/50 hover:bg-brand-light/30 transition-all cursor-pointer flex flex-col items-center justify-center p-8 text-center min-h-[350px] group shadow-sm">
                <div class="w-16 h-16 rounded-full bg-white border border-gray-200 group-hover:bg-brand-primary group-hover:text-white group-hover:border-brand-primary flex items-center justify-center mb-4 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[32px] text-gray-400 group-hover:text-white">add</span>
                </div>
                <h3 class="font-headline font-bold text-lg text-gray-900 mb-1">Nouveau Séminaire</h3>
                <p class="text-sm text-gray-500 max-w-[200px]">Commencez à planifier la prochaine session de formation.</p>
            </a>
            @endif
            @endif

            @foreach($seminars as $seminar)
                @php
                    $enrolledCount = $seminar->registrations()->where('status', 'approved')->count();
                    $percent = $seminar->max_participants > 0 ? ($enrolledCount / $seminar->max_participants) * 100 : 0;
                    
                    $statusColor = $percent >= 90 ? 'amber' : 'emerald';
                    
                    if ($percent >= 90) {
                        $diffDays = $seminar->start_date ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($seminar->start_date)->startOfDay(), false) : null;
                        if ($diffDays > 0) {
                            $statusText = 'J-' . intval($diffDays);
                        } elseif ($diffDays === 0) {
                            $statusText = "Aujourd'hui";
                        } else {
                            $statusText = "En cours";
                        }
                    } else {
                        $statusText = 'Inscriptions Ouvertes';
                    }
                    
                    $imageUrl = $seminar->image ? asset('storage/' . $seminar->image) : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=600&auto=format&fit=crop';
                @endphp
                <div x-data="{ open: false, detailsOpen: false }" :style="(open || detailsOpen) ? 'z-index: 50;' : 'z-index: 0;'" class="bg-white rounded-lg border border-gray-100 shadow-sm transition-all flex flex-col relative" :class="detailsOpen ? '' : 'hover:shadow-lg hover:-translate-y-1'">
                    <div @click="detailsOpen = true" class="h-40 relative bg-cover bg-center rounded-t-lg overflow-visible cursor-pointer" style="background-image: url('{{ $imageUrl }}');">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent flex items-end p-5">
                            <span class="bg-{{ $statusColor }}-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">{{ $statusText }}</span>
                        </div>
                        @if($seminar->cohort)
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/95 backdrop-blur-sm text-brand-primary text-xs font-bold px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                                    {{ $seminar->cohort->title }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs font-bold text-brand-primary mb-2 uppercase tracking-wider">{{ $seminar->departments->pluck('name')->join(', ') }}</div>
                        <h3 @click="detailsOpen = true" class="text-xl font-headline font-bold text-gray-900 mb-3 leading-tight cursor-pointer hover:text-brand-primary transition-colors">{{ $seminar->title }}</h3>
                        <p class="text-sm text-gray-500 mb-5 line-clamp-2">{{ $seminar->description }}</p>
                        
                        <div class="space-y-3 mt-auto mb-6">
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-brand-light text-brand-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                                </div>
                                {{ $seminar->start_date ? $seminar->start_date->format('d M') : 'N/A' }} - {{ $seminar->end_date ? $seminar->end_date->format('d M Y') : 'N/A' }}
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-brand-light text-brand-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px]">meeting_room</span>
                                </div>
                                {{ $seminar->trainers->count() }} Formateur(s)
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-brand-light text-brand-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px]">group</span>
                                </div>
                                {{ $enrolledCount }} / {{ $seminar->max_participants }} Inscrits
                            </div>
                        </div>

                        <div class="relative w-full h-2 bg-gray-100 rounded-full overflow-visible mb-6">
                            <div class="absolute top-0 left-0 h-full bg-{{ $statusColor }}-500 rounded-full transition-all" style="width: {{ $percent }}%;"></div>
                        </div>
                        
                        <div class="flex justify-between items-center gap-3 relative">
                              @if(auth()->user()->role->name !== 'Agent JICA')
                              <a href="{{ route('admin.seminars.edit', $seminar->id) }}" class="flex-1 text-center border border-gray-200 py-2.5 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-colors shadow-sm flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[18px]">edit_note</span> Modifier</a>
                              
                              <div class="relative inline-block text-left">
                                  <button @click="open = !open" @click.outside="open = false" class="p-2.5 text-gray-500 bg-white hover:text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm transition-colors flex items-center justify-center">
                                      <span class="material-symbols-outlined text-[20px] leading-none">more_vert</span>
                                  </button>
                                  
                                  <div x-show="open" x-transition class="absolute right-0 bottom-full mb-1 w-44 bg-white border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-lg py-1 z-50 flex flex-col">
                                      <form action="{{ route('admin.seminars.toggle', $seminar->id) }}" method="POST" class="m-0 w-full">
                                          @csrf
                                          @method('PATCH')
                                          <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium flex items-center gap-2 {{ $seminar->status === 'active' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }}">
                                              <span class="material-symbols-outlined text-[16px]">
                                                  {{ $seminar->status === 'active' ? 'block' : 'check_circle' }}
                                              </span>
                                              {{ $seminar->status === 'active' ? 'Désactiver' : 'Activer' }}
                                          </button>
                                      </form>
                                      
                                      <form action="{{ route('admin.seminars.destroy', $seminar->id) }}" method="POST" onsubmit="return confirm('Supprimer ce séminaire ?');" class="m-0 w-full border-t border-gray-50">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 font-medium flex items-center gap-2">
                                              <span class="material-symbols-outlined text-[16px]">delete</span> Supprimer
                                          </button>
                                      </form>
                                  </div>
                              </div>
                              @else
                              <div class="flex-1 text-center text-sm font-semibold text-gray-400 py-2.5 border border-gray-100 rounded-lg bg-gray-50">
                                  Lecture seule
                              </div>
                              @endif
                          </div>
                          
                          <!-- Modal Details -->
                          <div x-show="detailsOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" x-transition x-cloak>
                              <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] mx-4" @click.outside="detailsOpen = false">
                                  <!-- Banner -->
                                  <div class="h-64 md:h-80 relative bg-cover bg-center shrink-0" style="background-image: url('{{ $imageUrl }}');">
                                      <button @click="detailsOpen = false" class="absolute top-4 right-4 bg-white/80 hover:bg-white text-gray-900 rounded-full w-10 h-10 flex items-center justify-center transition-colors shadow-md">
                                          <span class="material-symbols-outlined">close</span>
                                      </button>
                                      <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent flex flex-col justify-end p-8">
                                          <span class="bg-{{ $statusColor }}-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm w-fit mb-3">{{ $statusText }}</span>
                                          <h2 class="text-3xl md:text-4xl font-headline font-bold text-white mb-2">{{ $seminar->title }}</h2>
                                          <div class="text-sm font-bold text-brand-light uppercase tracking-wider">{{ $seminar->departments->pluck('name')->join(', ') }}</div>
                                      </div>
                                  </div>
                                  
                                  <!-- Scrollable Content -->
                                  <div class="p-8 overflow-y-auto flex-1 bg-slate-50 flex flex-col gap-6 text-left">
                                      <!-- Top Info -->
                                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                          <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-3">
                                              <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-800 flex items-center justify-center">
                                                  <span class="material-symbols-outlined">calendar_month</span>
                                              </div>
                                              <div>
                                                  <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Période</p>
                                                  <p class="text-sm font-bold text-gray-800">{{ $seminar->start_date ? $seminar->start_date->format('d/m/Y') : 'N/A' }} - {{ $seminar->end_date ? $seminar->end_date->format('d/m/Y') : 'N/A' }}</p>
                                              </div>
                                          </div>
                                          <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-3">
                                              <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-800 flex items-center justify-center">
                                                  <span class="material-symbols-outlined">group</span>
                                              </div>
                                              <div>
                                                  <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Inscrits</p>
                                                  <p class="text-sm font-bold text-gray-800">{{ $enrolledCount }} / {{ $seminar->max_participants }}</p>
                                              </div>
                                          </div>
                                          <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-3">
                                              <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-800 flex items-center justify-center">
                                                  <span class="material-symbols-outlined">school</span>
                                              </div>
                                              <div>
                                                  <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Formateurs</p>
                                                  <p class="text-sm font-bold text-gray-800">{{ $seminar->trainers->count() }}</p>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                                          <h3 class="text-lg font-bold text-gray-900 mb-2">Description</h3>
                                          <p class="text-gray-600 text-sm leading-relaxed">{{ $seminar->description }}</p>
                                      </div>

                                      @if($seminar->topics->count() > 0)
                                      <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                                          <h3 class="text-lg font-bold text-gray-900 mb-4">Thèmes disponibles</h3>
                                          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                              @foreach($seminar->topics as $topic)
                                              @php
                                                  // Count approved registrations for this specific topic
                                                  $topicCount = $seminar->registrations->where('topic_id', $topic->id)->count();
                                              @endphp
                                              <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors">
                                                  <span class="text-2xl font-black text-gray-900">{{ $topicCount }} <span class="text-sm font-normal text-gray-500">candidat(s)</span></span>
                                                  <span class="font-bold text-gray-800 text-base border-l border-gray-300 pl-4">{{ $topic->name }}</span>
                                              </div>
                                              @endforeach
                                          </div>
                                      </div>
                                      @endif
                                  </div>
                              </div>
                          </div>
                    </div>
                </div>
            @endforeach

        </div>
        @endforeach
    </div>
</div>

<script>
    function switchTab(tab) {
        // Hide all grids
        document.querySelectorAll('.seminar-grid').forEach(el => el.classList.add('hidden'));
        
        // Show target grid
        document.getElementById('grid-' + tab).classList.remove('hidden');

        // Reset all tabs styles
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.className = 'tab-btn px-6 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-full hover:bg-gray-100 transition-all';
        });

        // Set active tab style
        const activeTab = document.getElementById('tab-' + tab);
        activeTab.className = 'tab-btn px-6 py-2 bg-white text-gray-900 font-semibold text-sm rounded-full shadow-sm border border-gray-200 transition-all';
    }
</script>
@endsection
