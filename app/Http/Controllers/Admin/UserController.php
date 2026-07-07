<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::with('role')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = \App\Models\Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Créer l'utilisateur avec un mot de passe aléatoire temporaire
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
            'role_id' => $request->role_id,
        ]);

        // Générer un token de réinitialisation pour le premier accès
        $token = \Illuminate\Support\Facades\Password::createToken($user);

        // Envoyer la notification de bienvenue
        $user->notify(new \App\Notifications\WelcomeNewUserNotification($token));

        return redirect()->route('admin.users.index')->with('success', "Utilisateur {$user->name} créé. Un email d'activation lui a été envoyé.");
    }

    public function show(string $id)
    {
        $user = \App\Models\User::with('role')->findOrFail($id);
        return response()->json($user);
    }

    public function edit(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $roles = \App\Models\Role::all();
        return response()->json(['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'role_id' => 'sometimes|exists:roles,id',
        ]);

        $user->update($request->only('name', 'email', 'role_id'));

        return response()->json(['message' => 'Utilisateur modifié', 'user' => $user]);
    }

    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}
