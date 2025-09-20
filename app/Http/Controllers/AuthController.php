<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// ↓↓↓ Ajouts pour le reset de mot de passe
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ====== Affichage des formulaires ======
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ====== Inscription ======
    // ====== Inscription ======
public function register(Request $request)
{
    // 1) Règles de base
    $rules = [
        'name'     => ['required','string','max:255'],
        'email'    => ['required','email','max:255','unique:users,email'],
        'password' => ['required','string','min:8','confirmed'],
        'terms'    => ['nullable','accepted'],
        'role'     => ['required','in:user,association'],
    ];

    // 2) Si association, le matricule est requis + format + unique
    // Matricule RNE Tunisie : 7 chiffres + 1 lettre majuscule
    if ($request->input('role') === 'association') {
        $rules['matricule'] = ['required','regex:/^\d{7}[A-Z]$/','unique:users,matricule'];
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
        ->with('status', 'Bienvenue, '.$user->name.' !');
}


    // ====== Connexion ======
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
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

    // ====== Déconnexion ======
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ====== Mot de passe oublié / reset ======

    // Formulaire "Mot de passe oublié"
    public function showForgotForm()
    {
        return view('auth.passwords.email');
    }

    // Envoi du lien de réinitialisation
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required','email']]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // Formulaire de réinitialisation (depuis le lien reçu par mail)
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Traitement de la réinitialisation
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required','email'],
            'password' => ['required','confirmed','min:8'],
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
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
}