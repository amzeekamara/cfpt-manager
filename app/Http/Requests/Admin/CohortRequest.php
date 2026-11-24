<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CohortRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'clone_from_cohort_id' => 'nullable|exists:cohorts,id',
            'selected_seminar_ids' => 'nullable|array',
            'selected_seminar_ids.*' => 'exists:seminars,id',
        ];
    }
}
