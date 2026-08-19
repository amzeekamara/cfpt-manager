<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function change()
    {
        // Only allow users who must change their password to see this page.
        // Wait, what if they just want to change it normally? We can allow anyone, 
        // but for now let's just show it.
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

        return redirect('/')->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }
}
