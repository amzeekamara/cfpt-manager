@extends('layouts.admin')

@section('header_title', 'Historique des Notifications')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-[1200px] mx-auto bg-white rounded-[2rem] shadow-xl p-6 border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2 border border-gray-200 rounded-full hover:bg-gray-50 cursor-pointer transition-colors">
                    <span class="material-symbols-outlined text-[20px] leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl font-headline font-bold text-gray-900 tracking-tight">Historique</h1>
                    <p class="text-gray-500 text-sm mt-1">Consultez et filtrez l'historique complet des actions.</p>
                </div>
            </div>
        </header>

        <!-- Filters Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4 bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
            <form action="{{ route('admin.notifications.index') }}" method="GET" id="filterForm" class="m-0 flex flex-wrap items-center gap-3 w-full">
                
                <div class="relative shadow-sm rounded-full flex-1 min-w-[200px] max-w-[400px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 flex items-center">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" onblur="document.getElementById('filterForm').submit()" placeholder="Filtrer par action ou description..." class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-full text-sm outline-none w-full focus:border-brand-primary focus:ring-1 focus:ring-brand-primary bg-white transition-all">
                </div>

                <div class="relative shadow-sm rounded-full flex-shrink-0">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 flex items-center">
                        <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                    </span>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="document.getElementById('filterForm').submit()" class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-full text-sm outline-none focus:border-brand-primary focus:ring-1 focus:ring-brand-primary bg-white transition-all cursor-pointer">
                </div>

                @if(request()->anyFilled(['search', 'date']))
                    <a href="{{ route('admin.notifications.index') }}" class="px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 rounded-full font-medium transition-colors border border-rose-100 flex items-center gap-1 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                        Effacer
                    </a>
                @endif
            </form>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="divide-y divide-gray-100">
                @forelse($notifications as $notification)
                    <div class="p-4 hover:bg-gray-50 transition-colors flex gap-4 {{ $notification->read_at ? '' : 'bg-primary/5' }}">
                        <div class="w-10 h-10 rounded-full bg-brand-light/50 text-brand-primary flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="material-symbols-outlined text-[20px]">{{ $notification->data['icon'] ?? 'notifications' }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-1">
                                <h3 class="text-sm font-bold text-gray-900">{{ $notification->data['action'] ?? 'Notification' }}</h3>
                                <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    {{ $notification->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $notification->data['description'] ?? '' }}</p>
                            @if(isset($notification->data['user_name']))
                                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">person</span>
                                    {{ $notification->data['user_name'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">notifications_off</span>
                        <p>Aucun historique trouvé pour ces critères.</p>
                    </div>
                @endforelse
            </div>
            
            @if(method_exists($notifications, 'hasPages') && $notifications->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/30">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
        
    </div>
</div>
@endsection
