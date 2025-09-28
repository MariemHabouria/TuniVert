@extends('admin.partials.layout')
@section('title', 'Formations')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap mb-3" style="gap:.75rem;">
  <h4 class="mb-0">Formations</h4>

  <div class="d-flex" style="gap:.5rem;">
    <form method="get" class="d-flex" style="gap:.5rem;max-width:420px;width:100%;">
      <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Recherche (titre, organisateur)">
      <button class="btn btn-primary">Rechercher</button>
    </form>
    @if(Route::has('admin.formations.create'))
      <a class="btn btn-success" href="{{ route('admin.formations.create') }}">Créer une formation</a>
    @endif
  </div>
</div>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

<div class="row">
  @forelse ($formations as $f)
    @php
      // ---- Cover (cover > image > fallback) ----
      $cover = $f->cover
        ? Storage::url($f->cover)
        : ($f->image ? Storage::url($f->image) : asset('images/formation-default.jpg'));

      // ---- Titre ----
      $title = $f->title ?? $f->nom ?? '—';

      // ---- Catégorie (texte) ----
      $categorie = $f->category_name ?? $f->categorie ?? null;

      // ---- Statut (badge) ----
      $status = ($f->status ?? $f->etat ?? 'active');
      $statusClass = in_array($status, ['active','ouverte','open']) ? 'success'
                     : (in_array($status, ['draft','brouillon']) ? 'secondary'
                     : (in_array($status, ['closed','fermée']) ? 'dark' : 'info'));

      // ---- Organisateur: afficher UNIQUEMENT "Nom — email" ----
      // 1) valeurs depuis le contrôleur (join users)
      $orgName  = $f->organizer_name  ?? null;
      $orgEmail = $f->organizer_email ?? null;

      // 2) sinon extraire depuis $f->organizer (JSON/array/objet/relation)
      if (!$orgName || !$orgEmail) {
          $raw = $f->organizer ?? null; $data = null;
          if (is_string($raw)) {
              $tmp = json_decode($raw, true);
              if (json_last_error() === JSON_ERROR_NONE) $data = $tmp;
          } elseif ($raw instanceof \Illuminate\Database\Eloquent\Model) {
              $data = ['name' => $raw->name ?? null, 'email' => $raw->email ?? null];
          } elseif (is_array($raw)) {
              $data = $raw;
          } elseif (is_object($raw)) {
              $data = (array) $raw;
          }
          if (is_array($data)) {
              $orgName  = $orgName  ?: ($data['name']  ?? null);
              $orgEmail = $orgEmail ?: ($data['email'] ?? null);
          }
      }

      // ---- Lien Voir détails (admin prioritaire, sinon public) ----
      $showUrl = Route::has('admin.formations.show')
        ? route('admin.formations.show', $f)
        : (Route::has('formations.show') ? route('formations.show', $f) : '#');

      // ---- Date de début (optionnelle) ----
      $startAt = $f->start_at ?? $f->date_debut ?? null;
      $startStr = $startAt ? \Illuminate\Support\Carbon::parse($startAt)->format('d/m/Y') : null;

      // ---- Extrait / description courte ----
      $short = !empty($f->excerpt) ? $f->excerpt
              : (!empty($f->description) ? \Illuminate\Support\Str::limit(strip_tags($f->description), 140) : null);
    @endphp

    <div class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100 shadow-sm">
        <div class="ratio ratio-16x9 bg-light">
          <img src="{{ $cover }}" class="card-img-top object-fit-cover" alt="{{ $title }}"
               onerror="this.src='{{ asset('images/formation-default.jpg') }}'">
        </div>

        <div class="card-body">
          <div class="d-flex align-items-center mb-2" style="gap:.5rem;">
            @if($categorie)
              <span class="badge bg-primary">{{ $categorie }}</span>
            @endif
            <span class="badge bg-{{ $statusClass }}">{{ ucfirst($status) }}</span>
          </div>

          <h5 class="card-title mb-1">{{ $title }}</h5>

          {{-- Organisateur: seulement Nom — email (jamais l'objet JSON) --}}
          <p class="text-muted mb-2 small">
            @if($orgName || $orgEmail)
              par <span class="fw-semibold">{{ $orgName ?? 'Association' }}</span>
              @if($orgEmail) <span class="text-muted"> — {{ $orgEmail }}</span>@endif
            @else
              &nbsp;
            @endif
          </p>

          @if($short)
            <p class="card-text text-muted" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
              {{ $short }}
            </p>
          @endif
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center bg-white">
          <small class="text-muted">
            @if($startStr) Débute le {{ $startStr }} @endif
          </small>
          <a href="{{ $showUrl }}" class="btn btn-sm btn-outline-success">Voir détails</a>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12">
      <div class="alert alert-info">Aucune formation trouvée.</div>
    </div>
  @endforelse
</div>

<div class="mt-3">
  {{ $formations->links() }}
</div>
@endsection
