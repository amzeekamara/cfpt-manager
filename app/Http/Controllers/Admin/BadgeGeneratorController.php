<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BadgeGeneratorController extends Controller
{
    private string $presetsFile = 'card-presets/presets.json';

    public function index()
    {
        $seminars = Seminar::with('cohort')
            ->withCount(['registrations' => fn($q) => $q->where('status', 'approved')])
            ->orderByDesc('created_at')
            ->get();

        $presets = $this->loadPresets();

        return view('admin.badges.generator', [
            'seminars' => $seminars,
            'presets' => $presets,
            'samplePdfUrl' => asset('samples/badge_pfpt_2026_template.pdf'),
            'sampleCsvUrl' => asset('samples/participants_sample.csv'),
        ]);
    }

    /**
     * Retourne les candidats validés d'un séminaire au format compatible Excel/Studio.
     */
    public function seminarParticipants(Seminar $seminar)
    {
        $registrations = $seminar->registrations()
            ->with(['user.candidateProfile', 'topic'])
            ->where('status', 'approved')
            ->get();

        $rows = $registrations->map(function ($reg) use ($seminar) {
            $user = $reg->user;
            $profile = $user?->candidateProfile;

            $fullName = $profile
                ? trim(($profile->first_names ?? '') . ' ' . ($profile->last_name ?? ''))
                : ($user?->name ?? 'Inconnu');

            return [
                'Nom et prénom' => mb_strtoupper($fullName, 'UTF-8'),
                'Pays' => $profile?->residence_country ?: ($profile?->nationality ?: ''),
                'Thème' => $reg->topic?->name ?: $seminar->title,
                'Établissement / structure' => $profile?->current_employer ?: '',
                'Matricule' => $profile?->matricule ?: ('PFPT-' . str_pad($reg->id, 4, '0', STR_PAD_LEFT)),
                'Fonction' => $profile?->job_function ?: ($profile?->current_profession ?: ''),
                'Séminaire' => $seminar->title,
                'Email' => $user?->email ?? '',
            ];
        })->values();

        return response()->json([
            'columns' => ['Nom et prénom', 'Pays', 'Thème', 'Établissement / structure', 'Matricule', 'Fonction', 'Séminaire', 'Email'],
            'rows' => $rows,
            'seminar' => $seminar->title,
        ]);
    }

    /**
     * Sauvegarde un gabarit (template PDF + positions des zones de texte) pour réutilisation.
     */
    public function savePreset(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'type' => 'required|in:badge,attestation,carte',
            'fields' => 'required|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:15360',
            'existing_pdf_url' => 'nullable|string',
        ]);

        $presets = $this->loadPresets();
        $id = Str::slug($request->name) . '-' . substr(uniqid(), -5);

        $pdfUrl = $request->existing_pdf_url;
        if ($request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('card-presets/pdfs', 'public');
            $pdfUrl = asset('storage/' . $path);
        }

        $preset = [
            'id' => $id,
            'name' => $request->name,
            'type' => $request->type,
            'pdf_url' => $pdfUrl,
            'fields' => json_decode($request->fields, true) ?? [],
            'created_at' => now()->format('d/m/Y H:i'),
        ];

        array_unshift($presets, $preset);
        Storage::disk('local')->put($this->presetsFile, json_encode($presets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json([
            'success' => true,
            'preset' => $preset,
            'presets' => $presets,
        ]);
    }

    /**
     * Supprime un gabarit enregistré.
     */
    public function deletePreset(string $id)
    {
        $presets = collect($this->loadPresets())
            ->reject(fn($p) => ($p['id'] ?? '') === $id)
            ->values()
            ->all();

        Storage::disk('local')->put($this->presetsFile, json_encode($presets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json([
            'success' => true,
            'presets' => $presets,
        ]);
    }

    private function loadPresets(): array
    {
        if (!Storage::disk('local')->exists($this->presetsFile)) {
            return [];
        }
        $data = json_decode(Storage::disk('local')->get($this->presetsFile), true);
        return is_array($data) ? $data : [];
    }
}
