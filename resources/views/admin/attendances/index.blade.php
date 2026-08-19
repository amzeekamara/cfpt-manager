@extends('layouts.admin')

@section('header_title', 'Gestion des Présences')

@section('content')
<div class="w-full min-h-screen bg-[#e9ebf0] p-4 lg:p-7 text-slate-800">
    <div class="w-full max-w-[1600px] mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2.5 border border-slate-200 rounded-full hover:bg-slate-50 cursor-pointer transition shadow-2xs bg-white text-slate-700" title="Retour au tableau de bord">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Historique des Présences</h1>
                    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Consultez et suivez les présences enregistrées par les formateurs.</p>
                </div>
            </div>
        </div>
    
    <!-- STATS CARDS & CHART -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="col-span-1 lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <x-stat-card 
                title="Présences" icon="check_circle" :showProgress="false"
                value="{{ number_format($totalPresences) }}" 
                total="{{ $total }}" 
                unit=""
                description="Nombre total de présences selon les filtres."
                color="green">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 text-surface-container opacity-40"><span class="material-symbols-outlined text-8xl text-green-100" style="font-variation-settings: 'FILL' 1;">how_to_reg</span></div>
                </x-slot>
            </x-stat-card>

            <x-stat-card 
                title="Absences" icon="cancel" :showProgress="false"
                value="{{ number_format($totalAbsences) }}" 
                total="{{ $total }}" 
                unit=""
                description="Nombre total d'absences selon les filtres."
                color="red">
                <x-slot name="decoration">
                    <div class="absolute -bottom-4 -right-4 text-surface-container opacity-40"><span class="material-symbols-outlined text-8xl text-red-100" style="font-variation-settings: 'FILL' 1;">person_off</span></div>
                </x-slot>
            </x-stat-card>
        </div>
        
        <div class="col-span-1 bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center items-center relative overflow-hidden">
            <h3 class="text-sm font-bold text-slate-700 mb-4 w-full text-left">Taux de présence</h3>
            <div class="relative w-32 h-32 md:w-40 md:h-40">
                <canvas id="attendanceChart"></canvas>
            </div>
            <div class="mt-4 text-2xl font-black text-primary">{{ $attendanceRate }}%</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <form method="GET" action="{{ route('admin.attendances.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-bold text-slate-700 mb-2">Séminaire</label>
                <select name="seminar_id" class="w-full rounded-md border-slate-200 focus:ring-primary focus:border-primary">
                    <option value="">Tous les séminaires</option>
                    @foreach($seminars as $seminar)
                        <option value="{{ $seminar->id }}" {{ $seminarId == $seminar->id ? 'selected' : '' }}>
                            {{ $seminar->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-bold text-slate-700 mb-2">Date (Optionnel)</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full rounded-md border-slate-200 focus:ring-primary focus:border-primary">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-bold text-slate-700 mb-2">Statut</label>
                <select name="status" class="w-full rounded-md border-slate-200 focus:ring-primary focus:border-primary">
                    <option value="">Tous les statuts</option>
                    <option value="present" {{ (isset($status) && $status === 'present') ? 'selected' : '' }}>Présent</option>
                    <option value="absent" {{ (isset($status) && $status === 'absent') ? 'selected' : '' }}>Absent</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-primary hover:brightness-90 text-white font-bold py-2.5 px-6 rounded-md transition-all shadow">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-xl border border-slate-200 overflow-visible">
        <div class="overflow-visible">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider border-b border-slate-200">
                        <th class="py-4 px-6 rounded-tl-xl">Date</th>
                        <th class="py-4 px-6">Candidat</th>
                        <th class="py-4 px-6">Séminaire & Thème</th>
                        <th class="py-4 px-6">Enregistré par</th>
                        <th class="py-4 px-6 text-center rounded-tr-xl">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 text-sm text-slate-700 font-medium">
                                {{ \Carbon\Carbon::parse($attendance->session_date)->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6 text-sm text-slate-900 font-bold">
                                {{ $attendance->registration->user->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-sm">
                                <div class="font-medium text-slate-800">{{ $attendance->registration->seminar->title ?? 'N/A' }}</div>
                                @if($attendance->topic)
                                    <div class="text-xs text-slate-500">{{ $attendance->topic->name }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm text-slate-600">
                                {{ $attendance->trainer->name ?? 'Système' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($attendance->status === 'present')
                                    <span class="text-xs font-bold text-emerald-600">Présent</span>
                                @else
                                    <span class="text-xs font-bold text-rose-600">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500 font-medium">
                                <span class="material-symbols-outlined text-4xl block text-slate-300 mb-2">event_busy</span>
                                Aucune présence trouvée pour ces critères.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $attendances->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('attendanceChart').getContext('2d');
    var totalPresences = {{ $totalPresences ?? 0 }};
    var totalAbsences = {{ $totalAbsences ?? 0 }};
    
    var data = (totalPresences === 0 && totalAbsences === 0) ? [1] : [totalPresences, totalAbsences];
    var bgColors = (totalPresences === 0 && totalAbsences === 0) ? ['#e2e8f0'] : ['#22c55e', '#ef4444'];
    var hoverBgColors = (totalPresences === 0 && totalAbsences === 0) ? ['#e2e8f0'] : ['#16a34a', '#dc2626'];
    var labels = (totalPresences === 0 && totalAbsences === 0) ? ['Aucune donnée'] : ['Présences', 'Absences'];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: bgColors,
                hoverBackgroundColor: hoverBgColors,
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (totalPresences === 0 && totalAbsences === 0) return ' Aucune donnée';
                            var label = context.label || '';
                            var value = context.parsed || 0;
                            return ' ' + label + ': ' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
