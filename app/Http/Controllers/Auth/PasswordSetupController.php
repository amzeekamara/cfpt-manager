<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PasswordSetupController extends Controller
{
    public function show(string $token, Request $request)
    {
        $email = $request->get('email');
        
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        // Si le token n'existe pas ou ne correspond pas
        if (!$tokenData || !Hash::check($token, $tokenData->token)) {
            abort(403, 'Lien d\'activation invalide ou expiré.');
        }

        return view('auth.setup-password', compact('token', 'email'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'Lien invalide ou expiré.']);
        }

        // Mettre à jour le mot de passe de l'utilisateur
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Supprimer le token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Connecter l'utilisateur
        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('success', 'Votre mot de passe a été configuré avec succès !');
    }
}
