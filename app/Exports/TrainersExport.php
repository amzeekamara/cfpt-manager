<?php

namespace App\Exports;

use App\Models\Trainer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TrainersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Trainer::with(['seminar.cohort']);

        if (!empty($this->filters['payment_status'])) {
            $query->where('payment_status', $this->filters['payment_status']);
        }
        
        if (!empty($this->filters['cohort_id'])) {
            $query->whereHas('seminar', function($q) {
                $q->where('cohort_id', $this->filters['cohort_id']);
            });
        }

        if (!empty($this->filters['seminar_id'])) {
            $query->where('seminar_id', $this->filters['seminar_id']);
        }

        if (!empty($this->filters['topic_id'])) {
            $query->whereHas('seminar.topics', function($q) {
                $q->where('id', $this->filters['topic_id']);
            });
        }

        if (!empty($this->filters['department_id'])) {
            $query->whereHas('seminar.departments', function($q) {
                $q->where('departments.id', $this->filters['department_id']);
            });
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

    public function map($trainer): array
    {
        return [
            $trainer->id,
            $trainer->name,
            $trainer->seminar->cohort->title ?? '',
            $trainer->seminar->title ?? '',
            $trainer->remuneration,
            $trainer->payment_status
        ];
    }
}
