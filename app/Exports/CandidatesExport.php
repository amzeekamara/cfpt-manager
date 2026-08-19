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
        $query = CandidateProfile::with(['user.registrations.seminar.cohort']);

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['cohort_id'])) {
            $query->whereHas('user.registrations.seminar', function($q) {
                $q->where('cohort_id', $this->filters['cohort_id']);
            });
        }

        if (!empty($this->filters['seminar_id'])) {
            $query->whereHas('user.registrations', function($q) {
                $q->where('seminar_id', $this->filters['seminar_id']);
            });
        }

        if (!empty($this->filters['topic_id'])) {
            $query->whereHas('user.registrations.seminar.topics', function($q) {
                $q->where('id', $this->filters['topic_id']);
            });
        }

        if (!empty($this->filters['department_id'])) {
            $query->whereHas('user.registrations.seminar.departments', function($q) {
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
        $cohorts = [];
        $seminars = [];
        
        if ($candidate->user && $candidate->user->registrations) {
            foreach ($candidate->user->registrations as $registration) {
                if ($registration->seminar) {
                    if ($registration->seminar->cohort) {
                        $cohorts[] = $registration->seminar->cohort->title;
                    }
                    $seminars[] = $registration->seminar->title;
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
            implode(', ', array_unique(array_filter($cohorts))),
            implode(', ', array_unique(array_filter($seminars))),
            $candidate->created_at->format('d/m/Y')
        ];
    }
}
