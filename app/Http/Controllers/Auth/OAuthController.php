<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class OAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // If user exists but hasn't linked google_id, update it
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
                
                Auth::login($user, true);
                
                // Redirect based on role
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role && $user->role->name === 'Formateur') {
                    return redirect()->route('portal.trainer.dashboard');
                } else {
                    return redirect()->route('portal.candidate.dashboard');
                }
            } else {
                // Get the default Candidate role (id 3)
                $candidateRole = \App\Models\Role::where('name', 'Candidat')->first();
                
                // Create a new user (defaulting to candidate for safety)
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                    'role_id' => $candidateRole ? $candidateRole->id : 3
                ]);

                Auth::login($newUser, true);
                return redirect()->route('portal.candidate.dashboard');
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Erreur lors de la connexion avec Google. Veuillez réessayer.']);
        }
    }
}
