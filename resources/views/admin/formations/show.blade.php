@extends('admin.partials.layout')
@section('title', 'Détails formation')

@section('content')
@php
  use Illuminate\Support\Carbon;
  use Illuminate\Support\Str;

  // Petite helper inline : renvoie la 1ère valeur non vide parmi plusieurs clés
  $pick = function($model, array $keys) {
      foreach ($keys as $k) {
          if (isset($model->$k) && $model->$k !== '' && $model->$k !== null) {
              return $model->$k;
          }
      }
      return null;
  };

  // -------- Cover --------
  $cover = $pick($formation, ['cover','image']);
  $cover = $cover ? (Str::startsWith($cover, ['http://','https://','/storage/']) ? $cover : Storage::url($cover)) : asset('images/formation-default.jpg');

  // -------- Champs de base --------
$title  = $pick($formation, ['title','nom','name','titre','intitule','libelle']) ?? '—';
  $slug   = $pick($formation, ['slug']);
  $cat    = $pick($formation, ['category_name','categorie']);
  $status = $pick($formation, ['status','etat']) ?? 'active';
  $statusClass = in_array($status, ['active','ouverte','open']) ? 'success'
                 : (in_array($status, ['draft','brouillon']) ? 'secondary'
                 : (in_array($status, ['closed','fermée']) ? 'dark' : 'info'));

  // -------- Organisateur : Name + Email --------
  $orgName  = $pick($formation, ['organizer_name','organisateur_nom','association_nom']);
  $orgEmail = $pick($formation, ['organizer_email','organisateur_email','association_email']);

  if (!$orgName || !$orgEmail) {
      $raw = $pick($formation, ['organizer','organisateur','association']);
      $data = null;
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

  // -------- Dates Début / Fin : on teste plusieurs noms de colonnes --------
  $startRaw = $pick($formation, ['start_at','date_debut','debut','start','starts_at','starts_on','date']);
  $endRaw   = $pick($formation, ['end_at','date_fin','fin','end','ends_at','ends_on']);

  $fmt = function($val) {
      if (!$val) return null;
      try {
          return Carbon::parse($val)->format('d/m/Y H:i');
      } catch (\Throwable $e) {
          // si ce n’est pas parsable, on affiche brut
          return is_string($val) ? $val : null;
      }
  };
  $startStr = $fmt($startRaw);
  $endStr   = $fmt($endRaw);

  // -------- Autres --------
  $created = $formation->created_at ? Carbon::parse($formation->created_at)->format('d/m/Y H:i') : null;
  $updated = $formation->updated_at ? Carbon::parse($formation->updated_at)->format('d/m/Y H:i') : null;
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Détails de la formation</h4>
  <div class="d-flex" style="gap:.5rem;">
    <a href="{{ route('admin.formations.index') }}" class="btn btn-light">← Retour</a>
    @if(Route::has('admin.formations.edit'))
      <a href="{{ route('admin.formations.edit', $formation->id) }}" class="btn btn-primary">Éditer</a>
    @endif
  </div>
</div>

<div class="card mb-3">
  <div class="ratio ratio-21x9 bg-light">
    <img src="{{ $cover }}" class="object-fit-cover" alt="{{ $title }}"
         onerror="this.src='{{ asset('images/formation-default.jpg') }}'">
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0">
        <tbody>
          <tr>
            <th style="width:260px;">Titre</th>
            <td class="fw-semibold">{{ $title }}</td>
          </tr>
          @if($slug)
          <tr>
            <th>Slug</th>
            <td>{{ $slug }}</td>
          </tr>
          @endif
          @if($cat)
          <tr>
            <th>Catégorie</th>
            <td><span class="badge bg-primary">{{ $cat }}</span></td>
          </tr>
          @endif
          <tr>
            <th>Statut</th>
            <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst($status) }}</span></td>
          </tr>
          <tr>
            <th>Organisateur</th>
            <td>
              @if($orgName || $orgEmail)
                <span class="fw-semibold">{{ $orgName ?? 'Association' }}</span>
                @if($orgEmail) <span class="text-muted"> — {{ $orgEmail }}</span>@endif
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
          </tr>
          <tr>
            <th>Début</th>
            <td>{{ $startStr ?? '—' }}</td>
          </tr>
          <tr>
            <th>Fin</th>
            <td>{{ $endStr ?? '—' }}</td>
          </tr>
          @if(!empty($formation->lieu) || !empty($formation->adresse))
          <tr>
            <th>Lieu</th>
            <td>{{ $formation->lieu ?? $formation->adresse }}</td>
          </tr>
          @endif
          @if(!empty($formation->description))
          <tr>
            <th>Description</th>
            <td>{!! nl2br(e($formation->description)) !!}</td>
          </tr>
          @endif
          <tr>
            <th>Créée le</th>
            <td>{{ $created ?? '—' }}</td>
          </tr>
          <tr>
            <th>Dernière mise à jour</th>
            <td>{{ $updated ?? '—' }}</td>
          </tr>
          <tr>
            <th>ID</th>
            <td>{{ $formation->id }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
