<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormTemplate;
use App\Models\FormSubmission;
use App\Models\Seminar;
use App\Models\Edition;
use App\Models\Enrollment;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormController extends Controller
{
    /**
     * List all form templates
     */
    public function index()
    {
        $forms = FormTemplate::withCount('submissions')
            ->with('seminar')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.forms.index', compact('forms'));
    }

    /**
     * Show builder for new form
     */
    public function create()
    {
        $seminars = Seminar::where('status', '!=', 'completed')->get();

        // Default PFPT fields pre-loaded
        $defaultFields = [
            ['key' => 'civilite',       'type' => 'radio',    'label' => 'Titre de civilité',            'required' => true,  'options' => ['Monsieur', 'Madame'], 'data_source' => ''],
            ['key' => 'nom',            'type' => 'text',     'label' => 'Nom',                          'required' => true,  'placeholder' => 'Votre nom de famille', 'data_source' => ''],
            ['key' => 'prenom',         'type' => 'text',     'label' => 'Prénom',                       'required' => true,  'placeholder' => 'Votre prénom', 'data_source' => ''],
            ['key' => 'date_naissance', 'type' => 'date',     'label' => 'Date de naissance',            'required' => true,  'placeholder' => '', 'data_source' => ''],
            ['key' => 'lieu_naissance', 'type' => 'text',     'label' => 'Lieu de naissance',            'required' => true,  'placeholder' => 'Ville, Pays', 'data_source' => ''],
            ['key' => 'email',          'type' => 'email',    'label' => 'Adresse e-mail',               'required' => true,  'placeholder' => 'exemple@email.com', 'data_source' => ''],
            ['key' => 'whatsapp',       'type' => 'tel',      'label' => 'Numéro de téléphone WhatsApp', 'required' => true,  'placeholder' => '+221 77 000 00 00', 'data_source' => ''],
            ['key' => 'urgence',        'type' => 'tel',      'label' => "Numéro en cas d'urgence",     'required' => true,  'placeholder' => '+221 77 000 00 00', 'data_source' => ''],
            ['key' => 'pays',           'type' => 'select',   'label' => 'Pays',                         'required' => true,  'options' => [], 'data_source' => 'countries'],
            ['key' => 'theme',          'type' => 'select',   'label' => 'Thème',                        'required' => true,  'options' => [], 'data_source' => 'seminars'],
        ];

        // Data sources available in the builder
        $dbSources = [
            'seminars' => Seminar::where('status', '!=', 'completed')->pluck('title', 'id'),
            'editions' => Edition::orderByDesc('start_date')->pluck('title', 'id'),
        ];

        return view('admin.forms.builder', compact('seminars', 'defaultFields', 'dbSources'));
    }

    /**
     * Save new form
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'seminar_id'  => 'nullable|exists:seminars,id',
            'is_active'   => 'boolean',
            'fields_json' => 'required|json',
        ]);

        $form = FormTemplate::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'seminar_id'  => $validated['seminar_id'] ?? null,
            'is_active'   => $request->boolean('is_active', true),
            'fields_json' => json_decode($validated['fields_json'], true),
            'created_by'  => auth()->id(),
        ]);

        ActivityLogger::log(
            'Formulaire Créé',
            "Le formulaire '{$form->title}' a été créé.",
            $form
        );

        return redirect()->route('admin.forms.index')
            ->with('success', "Formulaire créé ! Lien : " . $form->share_url);
    }

    /**
     * Show builder for existing form
     */
    public function edit(FormTemplate $form)
    {
        $seminars = Seminar::where('status', '!=', 'completed')->get();
        $dbSources = [
            'seminars' => Seminar::where('status', '!=', 'completed')->pluck('title', 'id'),
            'editions' => Edition::orderByDesc('start_date')->pluck('title', 'id'),
        ];
        return view('admin.forms.builder', compact('form', 'seminars', 'dbSources'));
    }

    /**
     * Update existing form
     */
    public function update(Request $request, FormTemplate $form)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'seminar_id'  => 'nullable|exists:seminars,id',
            'is_active'   => 'boolean',
            'fields_json' => 'required|json',
        ]);

        $form->update([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'seminar_id'  => $validated['seminar_id'] ?? null,
            'is_active'   => $request->boolean('is_active', true),
            'fields_json' => json_decode($validated['fields_json'], true),
        ]);

        ActivityLogger::log(
            'Formulaire Modifié',
            "Le formulaire '{$form->title}' a été mis à jour.",
            $form
        );

        return redirect()->route('admin.forms.index')
            ->with('success', 'Formulaire mis à jour avec succès.');
    }

    /**
     * Delete a form
     */
    public function destroy(FormTemplate $form)
    {
        $title = $form->title;
        $form->delete();

        ActivityLogger::log('Formulaire Supprimé', "Le formulaire '{$title}' a été supprimé.", null);

        return redirect()->route('admin.forms.index')
            ->with('success', 'Formulaire supprimé.');
    }

    /**
     * View responses for a form
     */
    public function responses(FormTemplate $form)
    {
        $submissions = FormSubmission::where('form_template_id', $form->id)
            ->with('enrollment.user', 'enrollment.seminar')
            ->orderByDesc('submitted_at')
            ->paginate(20);

        return view('admin.forms.responses', compact('form', 'submissions'));
    }

    /**
     * Accept / Reject / Waitlist a submission (and update linked enrollment)
     */
    public function updateSubmissionStatus(Request $request, FormSubmission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,waiting_list,pending',
        ]);

        $enrollment = $submission->enrollment;

        if (!$enrollment) {
            return back()->with('error', 'Aucune candidature liée à cette réponse.');
        }

        $oldStatus = $enrollment->status;
        $enrollment->update(['status' => $validated['status']]);

        // If approved → link to the active edition's first open seminar (if not already done)
        if ($validated['status'] === 'approved') {
            $activeEdition = Edition::where('status', 'active')
                ->orWhere('status', 'ongoing')
                ->latest('start_date')
                ->first();

            if ($activeEdition && !$enrollment->seminar_id) {
                $firstSeminar = $activeEdition->seminars()->where('status', '!=', 'completed')->first();
                if ($firstSeminar) {
                    $enrollment->update(['seminar_id' => $firstSeminar->id]);
                }
            }
        }

        $name = $enrollment->user->name ?? 'Inconnu';
        ActivityLogger::log(
            'Décision Candidature (Formulaire)',
            "Statut de '{$name}' changé de '{$oldStatus}' en '{$validated['status']}' via formulaire.",
            $enrollment
        );

        return back()->with('success', "Statut mis à jour : {$validated['status']}.");
    }

    /**
     * Export responses as CSV
     */
    public function exportCsv(FormTemplate $form)
    {
        $submissions = FormSubmission::where('form_template_id', $form->id)
            ->orderByDesc('submitted_at')
            ->get();

        $fields = $form->fields_json ?? [];
        $headers = array_column($fields, 'label');
        $keys    = array_column($fields, 'key');

        $rows = [];
        $rows[] = array_merge(['#', 'Date de soumission'], $headers);

        foreach ($submissions as $i => $sub) {
            $answers = $sub->answers_json ?? [];
            $row = [$i + 1, $sub->submitted_at->format('d/m/Y H:i')];
            foreach ($keys as $key) {
                $row[] = $answers[$key] ?? '';
            }
            $rows[] = $row;
        }

        $filename = 'reponses_' . Str::slug($form->title) . '_' . now()->format('Ymd') . '.csv';

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for Excel
            foreach ($rows as $row) {
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(FormTemplate $form)
    {
        $form->update(['is_active' => !$form->is_active]);
        $state = $form->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Formulaire {$state}.");
    }
}
