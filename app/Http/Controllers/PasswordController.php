<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function change()
    {
        return view('auth.password-change');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        $redirectRoute = match (true) {
            $user->isAdmin() => route('admin.dashboard'),
            $user->role?->name === 'Candidat' => route('portal.candidate.dashboard'),
            $user->role?->name === 'Formateur' => route('portal.trainer.dashboard'),
            default => '/',
        };

        return redirect($redirectRoute)->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }
}
