@extends('admin.partials.layout')

@section('title', 'Utilisateurs')

@section('content')
<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Utilisateurs</h4>
    <form method="get" class="d-flex" style="gap:.5rem;max-width:420px;width:100%;">
      <input name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Recherche (nom, email, rôle)">
      <button class="btn btn-primary">Rechercher</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th style="width:56px;"></th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Créé le</th>
            <th style="width:220px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $u)
            <tr>
              <td>
                <img class="rounded-circle" width="40" height="40"
                     src="{{ $u->avatar ? Storage::url($u->avatar) : asset('images/avatar-default.png') }}"
                     alt="{{ $u->name }}"
                     onerror="this.src='{{ asset('images/avatar-default.png') }}'">
              </td>
              <td class="fw-semibold">{{ $u->name }}</td>
              <td class="text-muted">{{ $u->email }}</td>
              <td>
                <span class="badge bg-{{ $u->role === 'admin' ? 'danger' : 'secondary' }}">
                  {{ strtoupper($u->role ?? 'user') }}
                </span>
              </td>
              <td>{{ optional($u->created_at)->format('d/m/Y H:i') }}</td>
              <td>
                @if(Route::has('admin.utilisateurs.show'))
                  <a class="btn btn-sm btn-outline-success" href="{{ route('admin.utilisateurs.show', $u) }}">Voir</a>
                @endif
                @if(Route::has('admin.utilisateurs.edit'))
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.utilisateurs.edit', $u) }}">Éditer</a>
                @endif
                @if(Route::has('admin.utilisateurs.destroy'))
                  <form method="post" action="{{ route('admin.utilisateurs.destroy', $u) }}"
                        class="d-inline"
                        onsubmit="return confirm('Supprimer cet utilisateur ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted p-4">Aucun utilisateur trouvé.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-3">
  {{ $users->links() }}
</div>
@endsection

