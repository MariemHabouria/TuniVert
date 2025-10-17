<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ====== AUTHENTIFICATION STANDARD (UTILISATEURS/ASSOCIATIONS) ======

    /**
     * Affichage des formulaires
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Inscription
     */
    public function register(Request $request)
{
    // 1) Règles de base
    $rules = [
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'terms'    => ['nullable', 'accepted'],
        'role'     => ['required', 'in:user,association'],
    ];

    // 2) Si association, le matricule est requis (format : 7 chiffres + 1 lettre majuscule)
    if ($request->input('role') === 'association') {
        $rules['matricule'] = [
            'required',
            'regex:/^\d{7}[A-Z]$/',
            'unique:users,matricule'
        ];
    } else {
        $rules['matricule'] = ['nullable'];
    }

    $data = $request->validate($rules, [
        'matricule.required' => 'Le matricule RNE est obligatoire pour les associations.',
        'matricule.regex' => 'Le format du matricule est invalide (7 chiffres + 1 lettre majuscule).',
        'matricule.unique' => 'Ce matricule est déjà utilisé par une autre association.',
        'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
    ]);

    // 3) Déterminer si le compte doit être bloqué
    $isAssociation = ($data['role'] === 'association');

    // 4) Créer l'utilisateur
    $user = User::create([
        'name'      => $data['name'],
        'email'     => $data['email'],
        'password'  => Hash::make($data['password']),
        'role'      => $data['role'],
        'matricule' => $isAssociation ? $data['matricule'] : null,
        'is_blocked' => $isAssociation, // 🔒 BLOQUÉ SI ASSOCIATION
        'email_verified_at' => null, // Non vérifié par défaut
    ]);

    // 5) Traitement selon le type de compte
    if ($isAssociation) {
        // Association - redirection vers login sans envoi d'email
        return redirect()->route('login')->with('info',
            '✅ Votre compte association a été créé avec succès ! ' .
            '⏳ Il sera activé après vérification de vos informations par un administrateur (24-48h).'
        );
    } else {
        // Utilisateur normal - Connexion automatique
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', '🎉 Bienvenue sur Tunivert, ' . $user->name . ' !');
    }
}


    /**
     * Connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // 1) Vérifier les identifiants
        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Identifiants invalides.',
            ])->onlyInput('email');
        }

        // 2) Vérifier si le compte est bloqué
        $user = Auth::user();
        
        if ($user->is_blocked) {
            Auth::logout(); // Déconnecter immédiatement
            
            // Message personnalisé selon le rôle
            if ($user->role === 'association') {
                return back()->withErrors([
                    'email' => '⏳ Votre compte association est en attente de vérification par un administrateur. Vous recevrez un email dès que votre compte sera activé.',
                ])->onlyInput('email');
            } else {
                return back()->withErrors([
                    'email' => '🔒 Votre compte a été bloqué. Veuillez contacter l\'administrateur.',
                ])->onlyInput('email');
            }
        }

        // 3) Connexion réussie
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Bienvenue, ' . $user->name . ' !');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('info', 'Vous avez été déconnecté avec succès.');
    }

    // ====== MOT DE PASSE OUBLIÉ / RESET ======
    public function showForgotForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // ====== AUTHENTIFICATION ADMIN ======
    public function showAdminLoginForm()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->filled('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.'
            ])->withInput($request->except('password'));
        }

        $user = Auth::user();
        
        // Vérifier si c'est un admin
        if (!$user->isAdmin()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Accès réservé aux administrateurs.'
            ])->withInput($request->except('password'));
        }

        // Vérifier si le compte admin est bloqué
        if ($user->is_blocked) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Votre compte administrateur a été suspendu. Contactez le super admin.'
            ])->withInput($request->except('password'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Connexion admin réussie !');
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('info', 'Déconnexion réussie.');
    }
}