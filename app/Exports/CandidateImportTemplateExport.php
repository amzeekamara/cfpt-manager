<?php

namespace App\Exports;

use App\Models\Seminar;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidateImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new CandidateTemplateDataSheet(),
            new CandidateTemplateGuideSheet(),
        ];
    }
}

class CandidateTemplateDataSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'Candidats';
    }

    public function headings(): array
    {
        return [
            'nom',
            'prenoms',
            'email',
            'telephone',
            'sexe',
            'date_naissance',
            'lieu_naissance',
            'nationalite',
            'pays_residence',
            'etablissement',
            'fonction',
            'matricule',
            'grade',
            'diplome_plus_eleve',
            'theme',
            'statut',
            'adresse',
            'contact_urgence_nom',
            'contact_urgence_tel',
        ];
    }

    public function array(): array
    {
        return [
            [
                'DIALLO',
                'Amadou',
                'amadou.diallo@exemple.sn',
                '+221 77 123 45 67',
                'Masculin',
                '1990-05-14',
                'Dakar',
                'Sénégalaise',
                'Sénégal',
                'CFPT Sénégal-Japon',
                'Formateur Électrotechnique',
                'PFPT-2026-001',
                'Instructeur',
                'Licence Professionnelle',
                '',
                'approved',
                'Rocade Fann Bel-Air, Dakar',
                'Fatou Diallo',
                '+221 77 987 65 43',
            ],
            [
                'KOFFI',
                'Amina',
                'amina.koffi@exemple.tg',
                '+228 90 11 22 33',
                'Féminin',
                '1992-11-03',
                'Lomé',
                'Togolaise',
                'Togo',
                'LETP Lomé',
                'Professeure Systèmes Automatisés',
                'PFPT-2026-002',
                'Enseignante',
                'Master',
                '',
                'pending',
                'Lomé, Togo',
                'Kossi Koffi',
                '+228 90 44 55 66',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('A2');

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B6A95']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            2 => ['font' => ['italic' => true, 'color' => ['rgb' => '64748B']]],
            3 => ['font' => ['italic' => true, 'color' => ['rgb' => '64748B']]],
        ];
    }
}

class CandidateTemplateGuideSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'Instructions & Thèmes';
    }

    public function headings(): array
    {
        return ['Rubrique / Colonne', 'Obligatoire ?', 'Explications & Valeurs acceptées'];
    }

    public function array(): array
    {
        $rows = [
            ['nom & prenoms', 'OUI', 'Remplissez les colonnes "nom" et "prenoms" (ou une seule colonne "Nom et prénom"). Remplacez les 2 lignes d\'exemple avant d\'importer.'],
            ['email', 'Recommandé', 'Adresse email unique du candidat. Si laissée vide, un identifiant interne automatique (@pfpt.local) est généré.'],
            ['sexe', 'Optionnel', 'Masculin ou Féminin (M / F acceptés).'],
            ['date_naissance', 'Optionnel', 'Format AAAA-MM-JJ (ex: 1990-05-14) ou JJ/MM/AAAA.'],
            ['pays_residence / nationalite', 'Optionnel', 'Ex: Sénégal, Togo, Bénin, Guinée-Bissau, Cameroun, Mauritanie...'],
            ['etablissement', 'Optionnel', 'Établissement, école, ministère ou structure d\'origine.'],
            ['theme', 'Optionnel', 'Nom (ou partie du nom) d\'un thème du séminaire choisi. Si vide, le thème par défaut sélectionné lors de l\'import sera appliqué.'],
            ['statut', 'Optionnel', 'Valeurs : approved (Approuvé), pending (En attente), waiting_list (Liste d\'attente). Si vide, le statut choisi lors de l\'import est appliqué.'],
            ['', '', ''],
            ['--- SÉMINAIRES & THÈMES DISPONIBLES ---', '', ''],
        ];

        $seminars = Seminar::with('topics')->where('status', '!=', 'completed')->get();
        foreach ($seminars as $seminar) {
            if ($seminar->topics->isEmpty()) {
                $rows[] = [$seminar->title, 'Séminaire', '(Aucun thème spécifique)'];
            } else {
                foreach ($seminar->topics as $topic) {
                    $rows[] = [$seminar->title, 'Thème', $topic->name];
                }
            }
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A5F']],
            ],
        ];
    }
}
