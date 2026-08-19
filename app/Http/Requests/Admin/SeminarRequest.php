<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SeminarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'departments' => 'required|array|min:1',
            'departments.*' => 'exists:departments,id',
            'cohort_id' => 'nullable|exists:cohorts,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,active,completed',
            'topics' => 'nullable|array',
            'topics.*' => 'nullable|string|max:255',
        ];
    }
}
