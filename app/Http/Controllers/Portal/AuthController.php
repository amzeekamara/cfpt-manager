<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\Intervenant;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('portal.login', ['title' => 'Connexion au Portail']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string',
        ]);

        // 1. Try to find a Candidate
        $user = User::where('email', $request->email)->first();
        if ($user && $user->candidateProfile) {
            $profile = $user->candidateProfile;
            if ($profile->birth_date->format('Y-m-d') === $request->birth_date && 
                strtolower(trim($profile->birth_place)) === strtolower(trim($request->birth_place))) {
                
                $request->session()->put('portal_candidate_id', $profile->id);
                return redirect()->route('portal.candidate.dashboard');
            }
        }

        // 2. Try to find a Trainer
        $trainer = Intervenant::where('email', $request->email)
            ->where('birth_date', $request->birth_date)
            ->first();
            
        if ($trainer && strtolower(trim($trainer->birth_place)) === strtolower(trim($request->birth_place))) {
            $request->session()->put('portal_trainer_id', $trainer->id);
            return redirect()->route('portal.trainer.dashboard');
        }

        return back()->with('error', 'Identifiants incorrects. Veuillez vérifier vos informations.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['portal_candidate_id', 'portal_trainer_id']);
        return redirect()->route('portal.welcome');
    }
}
