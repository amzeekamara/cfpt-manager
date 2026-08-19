@forelse($recentExpenses as $expense)
<tr class="hover:bg-gray-50 transition-colors">
    <td class="py-4 px-6">
        <div class="text-sm font-bold text-gray-800">{{ $expense->description }}</div>
        @if($expense->seminar)
            <div class="text-[11px] text-brand-primary font-medium mt-0.5">Séminaire : {{ $expense->seminar->title }}</div>
        @endif
    </td>
    <td class="py-4 px-6">
        <span class="text-xs font-semibold text-slate-700">{{ $expense->category->name ?? 'Général' }}</span>
    </td>
    <td class="py-4 px-6 text-xs text-gray-500">{{ $expense->expense_date->format('d/m/Y') }}</td>
    <td class="py-4 px-6">
        @if($expense->receipt_path)
            <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank" class="inline-flex items-center justify-center p-2 bg-blue-50 text-primary hover:bg-blue-100 rounded-md transition-colors shadow-sm" title="Voir le justificatif">
                <span class="material-symbols-outlined text-[18px]">attachment</span>
            </a>
        @else
            <span class="text-gray-300 text-[10px] italic">Aucun</span>
        @endif
    </td>
    <td class="py-4 px-6 text-right font-black text-gray-900 text-sm whitespace-nowrap">{{ number_format($expense->amount, 0, ',', ' ') }} <span class="text-[10px] text-gray-500">CFA</span></td>
</tr>
@empty
<tr class="hover:bg-gray-50 transition-colors">
    <td colspan="5" class="py-12 text-center text-gray-400 italic text-sm">
        <div class="flex flex-col items-center justify-center gap-2">
            <span class="material-symbols-outlined text-3xl text-gray-300">receipt_long</span>
            <p>Aucune dépense trouvée avec les filtres sélectionnés.</p>
        </div>
    </td>
</tr>
@endforelse
