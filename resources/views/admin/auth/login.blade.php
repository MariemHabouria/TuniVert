@extends('layouts.admin-auth')

@section('title', 'Connexion Admin')

@section('content')
@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach($errors->all() as $error)
            <p class="mb-1">{{ $error }}</p>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('admin.login.submit') }}">
    @csrf
    
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="mdi mdi-email-outline"></i> Email administrateur
        </label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email') }}" 
               required autofocus placeholder="admin@example.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="mdi mdi-lock-outline"></i> Mot de passe
        </label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" required placeholder="Votre mot de passe">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Se souvenir de moi</label>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="mdi mdi-login"></i> Se connecter
    </button>

    <div class="text-center">
        <a href="{{ route('home') }}" class="text-muted small">
            <i class="mdi mdi-arrow-left"></i> Retour au site public
        </a>
    </div>
</form>
@endsection