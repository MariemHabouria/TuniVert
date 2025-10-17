@extends('layouts.admin')

@section('title', 'Modifier Utilisateur')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card p-3">
            <h4>Modifier {{ $user->name }}</h4>
            <form method="POST" action="{{ route('admin.utilisateurs.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Nom</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Confirmer mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Rôle</label>
                    <select name="role" class="form-control" required>
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="association" {{ $user->role === 'association' ? 'selected' : '' }}>Association</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection
