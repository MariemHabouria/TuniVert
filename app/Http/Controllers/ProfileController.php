<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // Get user statistics
        $stats = [];
        
        if ($user->role === 'association') {
            $stats = [
                'formations_created' => $user->formationsOrganisees()->count(),
                'events_created' => $user->events()->count(),
                'total_participants' => \Illuminate\Support\Facades\DB::table('formation_user')
                    ->whereIn('formation_id', $user->formationsOrganisees()->pluck('id'))
                    ->count(),
            ];
        } else {
            $stats = [
                'formations_enrolled' => $user->formations()->count(),
                'donations_made' => $user->donations()->count(),
                'total_donated' => $user->donations()->sum('montant') ?? 0,
                'points_earned' => $user->points ?? 0,
            ];
        }
        
        return view('profile.show', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'matricule_fiscale' => 'nullable|string|max:50', // For associations
            'adresse' => 'nullable|string|max:500',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = $avatar->storeAs('avatars', $avatarName, 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès !');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès !');
    }

    public function deactivate(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'password' => 'required',
            'confirmation' => 'required|in:DÉSACTIVER',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $user->update(['is_active' => false]);
        Auth::logout();

        return redirect()->route('login')->with('success', 'Votre compte a été désactivé avec succès.');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'password' => 'required',
            'confirmation' => 'required|in:SUPPRIMER',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $userName = $user->name;
        $user->delete();
        Auth::logout();

        return redirect()->route('home')->with('success', "Le compte de {$userName} a été supprimé définitivement.");
    }
}
