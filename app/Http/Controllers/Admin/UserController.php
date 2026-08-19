<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $allUsers = User::with('role')->get();

        $staffUsers = $allUsers->filter(fn($u) => !$u->role || !in_array($u->role->name, ['Candidat', 'Formateur', 'Agent JICA']));
        $candidates = $allUsers->filter(fn($u) => $u->role?->name === 'Candidat');
        $trainers   = $allUsers->filter(fn($u) => $u->role?->name === 'Formateur');
        $jicaAgents = $allUsers->filter(fn($u) => $u->role?->name === 'Agent JICA');

        return view('admin.users.index', compact('staffUsers', 'candidates', 'trainers', 'jicaAgents'));
    }

    public function create()
    {
        $roles = Role::all();
        $seminars = \App\Models\Seminar::all();
        return view('admin.users.create', compact('roles', 'seminars'));
    }

    public function store(Request $request)
    {
        $isCandidate = false;
        $candidateRole = Role::where('name', 'Candidat')->first();
        if ($candidateRole && $request->role_id == $candidateRole->id) {
            $isCandidate = true;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
            'password' => $isCandidate ? 'nullable' : 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialty' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'supervisor' => 'nullable|string|max:255',
        ]);

        $assignedPassword = $isCandidate ? \Illuminate\Support\Str::random(10) : $request->password;

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Créer l'utilisateur avec le mot de passe
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($assignedPassword),
            'role_id' => $request->role_id,
            'must_change_password' => true,
            'avatar' => $avatarPath,
            'specialty' => $request->specialty,
            'position' => $request->position,
            'supervisor' => $request->supervisor,
        ]);

        try {
            Mail::to($user->email)->send(new UserCreatedMail($user, $assignedPassword));
        } catch (\Exception $e) {
            \Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
        }

        $role = Role::find($request->role_id);
        if ($role && $role->name === 'Formateur') {
            $trainer = \App\Models\Trainer::where('email', $user->email)->first();
            if (!$trainer) {
                $trainer = \App\Models\Trainer::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'seminar_id' => $request->seminars[0] ?? 1,
                    'remuneration' => 0,
                    'payment_status' => 'unpaid'
                ]);
            }
            if ($request->has('seminars') && is_array($request->seminars)) {
                $syncData = [];
                foreach($request->seminars as $semId) {
                    $syncData[$semId] = ['remuneration' => 0, 'payment_status' => 'unpaid'];
                }
                $trainer->seminars()->sync($syncData);
            }
        }

        return redirect()->route('admin.users.index')->with('success', "Utilisateur {$user->name} créé. Le mot de passe initial est : {$assignedPassword}");
    }

    public function show(User $user)
    {
        $user->load('role');
        return response()->json($user);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $seminars = \App\Models\Seminar::where('status', 'active')->get();
        
        $trainer = \App\Models\Trainer::with('seminars')->where('email', $user->email)->first();
        $assignedSeminars = $trainer ? $trainer->seminars->pluck('id')->toArray() : [];

        return view('admin.users.edit', compact('user', 'roles', 'seminars', 'assignedSeminars', 'trainer'));
    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'role_id' => 'sometimes|exists:roles,id',
            'seminars' => 'nullable|array',
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialty' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'supervisor' => 'nullable|string|max:255',
        ]);

        $dataToUpdate = $request->only('name', 'email', 'role_id');
        if ($request->has('role_id')) $user->role_id = $request->role_id;
        if ($request->has('specialty')) $user->specialty = $request->specialty;
        if ($request->has('position')) $user->position = $request->position;
        if ($request->has('supervisor')) $user->supervisor = $request->supervisor;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $dataToUpdate['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($dataToUpdate);

        $role = Role::find($request->role_id ?? $user->role_id);
        if ($role && $role->name === 'Formateur') {
            $trainer = \App\Models\Trainer::firstOrCreate(
                ['email' => $user->email],
                ['name' => $user->name, 'remuneration' => 0, 'payment_status' => 'unpaid', 'seminar_id' => 1]
            );
            
            if ($request->has('seminars')) {
                // Ensure default remuneration and payment_status for new assignments
                $syncData = [];
                foreach($request->seminars as $semId) {
                    $syncData[$semId] = ['remuneration' => 0, 'payment_status' => 'unpaid'];
                }
                // Only sync the ones from request, but keep existing pivot data if already assigned?
                // Actually, syncWithoutDetaching is better, but what if they unassign? 
                // Let's use sync, but we need to preserve existing pivot data.
                // It's easier to just sync with empty array for new ones, but `sync` wipes out existing pivot data.
                // Let's get existing pivot data:
                $existing = $trainer->seminars()->pluck('remuneration', 'seminars.id')->toArray();
                $existingStatus = $trainer->seminars()->pluck('payment_status', 'seminars.id')->toArray();
                
                $finalSync = [];
                foreach($request->seminars as $semId) {
                    $finalSync[$semId] = [
                        'remuneration' => $existing[$semId] ?? 0,
                        'payment_status' => $existingStatus[$semId] ?? 'unpaid'
                    ];
                }
                $trainer->seminars()->sync($finalSync);
            } else {
                $trainer->seminars()->sync([]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->password = Hash::make($request->password);
        $user->must_change_password = true;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Mot de passe réinitialisé. Le nouveau mot de passe par défaut est : ' . $request->password);
    }
}
