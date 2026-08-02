<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Intervenant;
use App\Models\Message;
use App\Models\Edition;
use App\Models\Department;

class TrainerController extends Controller
{
    public function dashboard(Request $request)
    {
        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Intervenant::with('seminar')->findOrFail($trainerId);
        
        $messages = Message::where('intervenant_id', $trainerId)->latest()->get();
        
        $editions = Edition::all();
        $departments = Department::all();

        return view('portal.trainer.dashboard', compact('trainer', 'messages', 'editions', 'departments'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'edition_id' => 'nullable|exists:editions,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $trainerId = $request->session()->get('portal_trainer_id');
        $trainer = Intervenant::findOrFail($trainerId);

        Message::create([
            'intervenant_id' => $trainerId,
            'edition_id' => $request->edition_id,
            'department_id' => $request->department_id,
            'seminar_id' => $trainer->seminar_id,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Message envoyé avec succès aux candidats.');
    }
}
