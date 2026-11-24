<?php

namespace App\Imports;

use App\Models\CandidateProfile;
use App\Models\Registration;
use App\Models\Role;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class CandidatesImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;
    public int $skippedCount = 0;
    public array $rowErrors = [];

    private int $seminarId;
    private ?int $defaultTopicId;
    private bool $forceTopic;
    private string $defaultStatus;
    private ?int $candidateRoleId;
    private Collection $seminarTopics;

    public function __construct(int $seminarId, ?int $defaultTopicId = null, string $defaultStatus = 'approved', bool $forceTopic = false)
    {
        $this->seminarId = $seminarId;
        $this->defaultTopicId = $defaultTopicId;
        $this->forceTopic = $forceTopic;
        $this->defaultStatus = in_array($defaultStatus, ['pending', 'approved', 'waiting_list', 'rejected'])
            ? $defaultStatus
            : 'approved';

        $this->candidateRoleId = Role::where('name', 'Candidat')->value('id');
        $this->seminarTopics = Topic::where('seminar_id', $seminarId)->get();
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $rawRow) {
            $lineNumber = $index + 2; // Ligne 1 = en-têtes
            $row = $this->normalizeRow($rawRow->toArray());

            // 1. Extraire Nom & Prénoms (supporte "nom"+"prenoms" OU "nom_et_prenom")
            $lastName = trim((string) ($row['nom'] ?? $row['last_name'] ?? ''));
            $firstNames = trim((string) ($row['prenoms'] ?? $row['prenom'] ?? $row['first_names'] ?? ''));
            $combinedName = trim((string) ($row['nom_et_prenom'] ?? $row['nom_complet'] ?? $row['name'] ?? ''));

            if ($lastName === '' && $firstNames === '' && $combinedName !== '') {
                $parts = preg_split('/\s+/', $combinedName);
                if (count($parts) === 1) {
                    $lastName = $parts[0];
                    $firstNames = $parts[0];
                } else {
                    $lastName = array_pop($parts);
                    $firstNames = implode(' ', $parts);
                }
            }

            // Ignorer les lignes totalement vides ou les sous-titres de section
            if ($lastName === '' && $firstNames === '') {
                continue;
            }

            $fullName = trim($firstNames . ' ' . $lastName);

            // 2. Email (ou génération automatique si absent)
            $email = trim((string) ($row['email'] ?? $row['adresse_email'] ?? $row['mail'] ?? ''));
            if ($email === '') {
                $slug = Str::slug($fullName, '.');
                $email = ($slug ?: 'candidat') . '.' . substr(md5($fullName . $this->seminarId), 0, 5) . '@pfpt.local';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->rowErrors[] = "Ligne {$lineNumber} ({$fullName}) : adresse email invalide « {$email} ».";
                continue;
            }

            try {
                // 3. Créer ou récupérer le compte User
                $user = User::where('email', $email)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $fullName,
                        'email' => $email,
                        'password' => Hash::make(Str::random(12)),
                        'role_id' => $this->candidateRoleId,
                        'must_change_password' => true,
                    ]);
                }

                // 4. Mettre à jour / créer le CandidateProfile
                $country = trim((string) ($row['pays_residence'] ?? $row['pays'] ?? $row['residence_country'] ?? ''));
                $nationality = trim((string) ($row['nationalite'] ?? $row['nationality'] ?? $country));
                $employer = trim((string) ($row['etablissement'] ?? $row['etablissement_structure'] ?? $row['structure'] ?? $row['current_employer'] ?? ''));
                $jobFunc = trim((string) ($row['fonction'] ?? $row['profession'] ?? $row['job_function'] ?? ''));
                $genderRaw = strtolower(trim((string) ($row['sexe'] ?? $row['genre'] ?? $row['gender'] ?? '')));
                $gender = match (true) {
                    in_array($genderRaw, ['m', 'masculin', 'homme', 'male']) => 'Masculin',
                    in_array($genderRaw, ['f', 'féminin', 'feminin', 'femme', 'female']) => 'Féminin',
                    default => null,
                };

                $birthDate = $this->parseDate($row['date_naissance'] ?? $row['birth_date'] ?? null);

                CandidateProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    array_filter([
                        'first_names' => $firstNames ?: $lastName,
                        'last_name' => $lastName ?: $firstNames,
                        'phone' => trim((string) ($row['telephone'] ?? $row['tel'] ?? $row['phone'] ?? '')) ?: null,
                        'gender' => $gender,
                        'birth_date' => $birthDate,
                        'birth_place' => trim((string) ($row['lieu_naissance'] ?? $row['birth_place'] ?? '')) ?: null,
                        'nationality' => $nationality ?: null,
                        'residence_country' => $country ?: null,
                        'current_employer' => $employer ?: null,
                        'job_function' => $jobFunc ?: null,
                        'current_profession' => $jobFunc ?: null,
                        'matricule' => trim((string) ($row['matricule'] ?? '')) ?: null,
                        'grade' => trim((string) ($row['grade'] ?? '')) ?: null,
                        'highest_degree' => trim((string) ($row['diplome_plus_eleve'] ?? $row['diplome'] ?? $row['highest_degree'] ?? '')) ?: null,
                        'address' => trim((string) ($row['adresse'] ?? $row['address'] ?? '')) ?: null,
                        'emergency_contact_name' => trim((string) ($row['contact_urgence_nom'] ?? '')) ?: null,
                        'emergency_contact_phone' => trim((string) ($row['contact_urgence_tel'] ?? '')) ?: null,
                    ], fn($v) => $v !== null)
                );

                // 5. Résolution du Thème
                $topicId = $this->resolveTopicId(trim((string) ($row['theme'] ?? $row['topic'] ?? '')));

                // 6. Statut
                $rowStatus = strtolower(trim((string) ($row['statut'] ?? $row['status'] ?? '')));
                $status = match ($rowStatus) {
                    'approved', 'approuve', 'approuvé', 'valide', 'validé' => 'approved',
                    'pending', 'en_attente', 'en attente' => 'pending',
                    'waiting_list', 'liste_attente', 'liste d\'attente' => 'waiting_list',
                    'rejected', 'refuse', 'refusé' => 'rejected',
                    default => $this->defaultStatus,
                };

                // 7. Éviter les doublons sur le même séminaire (mais compléter le thème si manquant)
                $existingReg = Registration::where('user_id', $user->id)
                    ->where('seminar_id', $this->seminarId)
                    ->first();

                if ($existingReg) {
                    if ($topicId && ($this->forceTopic || !$existingReg->topic_id)) {
                        $existingReg->update(['topic_id' => $topicId]);
                    }
                    $this->skippedCount++;
                    continue;
                }

                Registration::create([
                    'user_id' => $user->id,
                    'seminar_id' => $this->seminarId,
                    'topic_id' => $topicId,
                    'status' => $status,
                ]);

                $this->importedCount++;
            } catch (\Throwable $e) {
                $this->rowErrors[] = "Ligne {$lineNumber} ({$fullName}) : " . $e->getMessage();
            }
        }
    }

    private function normalizeRow(array $raw): array
    {
        $out = [];
        foreach ($raw as $key => $val) {
            $cleanKey = Str::slug((string) $key, '_');
            $out[$cleanKey] = $val;
        }
        return $out;
    }

    private function normalizeTopicLabel(string $label): string
    {
        $ascii = mb_strtolower(Str::ascii($label), 'UTF-8');
        // Retirer un éventuel préfixe numéroté ("1 - ", "Thème 2 :", "3 — ", etc.)
        $ascii = preg_replace('/^(theme\s*)?\d+\s*[-–—:.]+\s*/i', '', $ascii);
        $ascii = preg_replace('/[^a-z0-9]+/', ' ', $ascii);
        return trim(preg_replace('/\s+/', ' ', $ascii));
    }

    private function resolveTopicId(string $themeText): ?int
    {
        if ($this->forceTopic && $this->defaultTopicId) {
            return $this->defaultTopicId;
        }

        if ($themeText === '' || $this->seminarTopics->isEmpty()) {
            return $this->defaultTopicId;
        }

        $needle = $this->normalizeTopicLabel($themeText);
        if ($needle === '') {
            return $this->defaultTopicId;
        }

        // 1. Correspondance exacte ou par inclusion après normalisation
        foreach ($this->seminarTopics as $topic) {
            $haystack = $this->normalizeTopicLabel((string) $topic->name);
            if ($haystack !== '' && ($haystack === $needle || str_contains($haystack, $needle) || str_contains($needle, $haystack))) {
                return $topic->id;
            }
        }

        // 2. Correspondance par mots significatifs communs (gère "des" vs "de", titres raccourcis, etc.)
        $needleWords = array_values(array_filter(explode(' ', $needle), fn($w) => strlen($w) >= 4));
        $bestId = null;
        $bestScore = 0.0;

        if (count($needleWords) > 0) {
            foreach ($this->seminarTopics as $topic) {
                $haystackWords = array_values(array_filter(explode(' ', $this->normalizeTopicLabel((string) $topic->name)), fn($w) => strlen($w) >= 4));
                if (count($haystackWords) === 0) {
                    continue;
                }
                $common = count(array_intersect($needleWords, $haystackWords));
                $score = $common / min(count($needleWords), count($haystackWords));
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestId = $topic->id;
                }
            }
        }

        if ($bestId && $bestScore >= 0.5) {
            return $bestId;
        }

        return $this->defaultTopicId;
    }

    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d');
            }
            $str = trim((string) $value);
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $str)) {
                return Carbon::createFromFormat('d/m/Y', $str)->format('Y-m-d');
            }
            return Carbon::parse($str)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
