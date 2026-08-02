<?php

namespace App\Exports;

use App\Models\CandidateProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;

class CandidatesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = CandidateProfile::with(['user.enrollments.seminar.edition']);

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['edition_id'])) {
            $query->whereHas('user.enrollments.seminar', function($q) {
                $q->where('edition_id', $this->filters['edition_id']);
            });
        }

        if (!empty($this->filters['seminar_id'])) {
            $query->whereHas('user.enrollments', function($q) {
                $q->where('seminar_id', $this->filters['seminar_id']);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom',
            'Prénoms',
            'Email',
            'Sexe',
            'Nationalité',
            'Date de Naissance',
            'Téléphone',
            'Édition(s)',
            'Séminaire(s)',
            'Date de Création'
        ];
    }

    public function map($candidate): array
    {
        $editions = [];
        $seminars = [];
        
        if ($candidate->user && $candidate->user->enrollments) {
            foreach ($candidate->user->enrollments as $enrollment) {
                if ($enrollment->seminar) {
                    if ($enrollment->seminar->edition) {
                        $editions[] = $enrollment->seminar->edition->title;
                    }
                    $seminars[] = $enrollment->seminar->title;
                }
            }
        }
        
        return [
            $candidate->id,
            $candidate->last_name ?? '',
            $candidate->first_names ?? '',
            $candidate->user->email ?? '',
            $candidate->gender ?? '',
            $candidate->nationality ?? '',
            $candidate->birth_date ? $candidate->birth_date->format('d/m/Y') : '',
            $candidate->phone ?? '',
            implode(', ', array_unique(array_filter($editions))),
            implode(', ', array_unique(array_filter($seminars))),
            $candidate->created_at->format('d/m/Y')
        ];
    }
}
