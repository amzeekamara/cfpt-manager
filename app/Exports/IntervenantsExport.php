<?php

namespace App\Exports;

use App\Models\Intervenant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IntervenantsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Intervenant::with(['seminar.edition']);

        if (!empty($this->filters['payment_status'])) {
            $query->where('payment_status', $this->filters['payment_status']);
        }
        
        if (!empty($this->filters['edition_id'])) {
            $query->whereHas('seminar', function($q) {
                $q->where('edition_id', $this->filters['edition_id']);
            });
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
            'Nom',
            'Édition',
            'Séminaire',
            'Rémunération',
            'Statut Paiement'
        ];
    }

    public function map($intervenant): array
    {
        return [
            $intervenant->id,
            $intervenant->name,
            $intervenant->seminar->edition->title ?? '',
            $intervenant->seminar->title ?? '',
            $intervenant->remuneration,
            $intervenant->payment_status
        ];
    }
}
