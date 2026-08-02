<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CandidateProfile;
use App\Models\Message;

class CandidateController extends Controller
{
    public function dashboard(Request $request)
    {
        $profileId = $request->session()->get('portal_candidate_id');
        $profile = CandidateProfile::with(['user.enrollments.edition', 'user.enrollments.department', 'user.enrollments.seminars'])->findOrFail($profileId);

        // Get the active enrollment (assuming the latest one for simplicity)
        $enrollment = $profile->user->enrollments()->latest()->first();
        
        $messages = collect();
        if ($enrollment) {
            $editionId = $enrollment->edition_id;
            $departmentId = $enrollment->department_id;
            $seminarIds = $enrollment->seminars->pluck('id')->toArray();

            $messages = Message::with('intervenant')
                ->where(function ($query) use ($editionId, $departmentId, $seminarIds) {
                    $query->whereNull('edition_id')->orWhere('edition_id', $editionId);
                })
                ->where(function ($query) use ($departmentId) {
                    $query->whereNull('department_id')->orWhere('department_id', $departmentId);
                })
                ->where(function ($query) use ($seminarIds) {
                    $query->whereNull('seminar_id')->orWhereIn('seminar_id', $seminarIds);
                })
                ->latest()
                ->get();
        }

        return view('portal.candidate.dashboard', compact('profile', 'enrollment', 'messages'));
    }
}
