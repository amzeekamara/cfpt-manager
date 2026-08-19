<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicFormSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers.matricule' => 'nullable|string|max:255',
            'answers.nom' => 'required|string|max:255',
            'answers.prenom' => 'required|string|max:255',
            'answers.sexe' => 'required|string|in:Masculin,Féminin',
            'answers.email' => 'required|email|max:255',
            'answers.whatsapp' => 'nullable|string|max:255',
            'answers.religion' => 'nullable|string|max:255',
            'answers.date_naissance' => 'nullable|date',
            'answers.lieu_naissance' => 'nullable|string|max:255',
            'answers.nationalite' => 'nullable|string|max:255',
            'answers.pays' => 'nullable|string|max:255',
            'answers.adresse' => 'nullable|string|max:500',
            'answers.situation_fam' => 'nullable|string|max:255',
            'answers.urgence_nom' => 'nullable|string|max:255',
            'answers.urgence' => 'nullable|string|max:255',
            
            'answers.grade' => 'nullable|string|max:255',
            'answers.fonction' => 'nullable|string|max:255',
            'answers.profession' => 'nullable|string|max:255',
            'answers.employeur' => 'nullable|string|max:255',
            'answers.emp_precedent' => 'nullable|string|max:255',
            'answers.desc_travail' => 'nullable|string',
            
            'answers.diplomas' => 'nullable|array',
            'answers.diplomas.*.nom' => 'required_with:answers.diplomas|string|max:255',
            'answers.diplomas.*.etab' => 'required_with:answers.diplomas|string|max:255',
            'answers.diplomas.*.annee' => 'required_with:answers.diplomas|string|max:255',
            'answers.diplomas.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            'answers.photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            'answers.sante_chronique' => 'nullable|string|in:Oui,Non',
            'answers.sante_traitement' => 'nullable|string|in:Oui,Non',
            'answers.sante_obs' => 'nullable|string',
            'answers.hebergement' => 'nullable|string|max:255',
            'answers.assurance' => 'nullable|string|max:255',
        ];
    }
}
