<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
            $rules['matricule'] = ['required', 'regex:/^\d{7}[A-Z]$/', 'unique:users,matricule'];
        } else {
            $rules['matricule'] = ['nullable'];
        }

        $data = $request->validate($rules);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'matricule' => $data['role'] === 'association' ? $data['matricule'] : null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('status', 'Bienvenue, ' . $user->name . ' !');
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

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Identifiants invalides.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
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
        if (!$user->isAdmin()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Accès réservé aux administrateurs.'
            ])->withInput($request->except('password'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('status', 'Connexion admin réussie !');
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('status', 'Déconnexion réussie.');
    }
}
