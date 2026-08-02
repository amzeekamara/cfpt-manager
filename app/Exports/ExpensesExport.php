<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Expense::with(['edition', 'seminar']);

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('expense_date', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('expense_date', '<=', $this->filters['date_to']);
        }
        
        if (!empty($this->filters['edition_id'])) {
            $query->where('edition_id', $this->filters['edition_id']);
        }

        if (!empty($this->filters['seminar_id'])) {
            $query->where('seminar_id', $this->filters['seminar_id']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Description',
            'Montant',
            'Édition',
            'Séminaire',
            'Date'
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->id,
            $expense->description,
            $expense->amount,
            $expense->edition->title ?? '',
            $expense->seminar->title ?? '',
            $expense->expense_date ? $expense->expense_date->format('d/m/Y') : ''
        ];
    }
}
