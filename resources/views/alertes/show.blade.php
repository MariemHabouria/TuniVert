@extends('layouts.app')

@section('title', $alerte->titre)

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top:-1px; padding-top:6rem!important;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('{{ asset('img/carousel-3.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">🚨 {{ $alerte->titre }}</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('alertes.index') }}" class="text-white">Alertes</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Détails</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Show Alerte -->
<div class="container py-5">
    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 mb-4" style="border-radius:15px;">
                <!-- En-tête avec statut -->
                <div class="card-header text-white d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, 
                         @if($alerte->gravite == 'feu') #000000
                         @elseif($alerte->gravite == 'haute') #dc3545
                         @elseif($alerte->gravite == 'moyenne') #ffc107
                         @else #28a745
                         @endif, #343a40); border-radius:15px 15px 0 0;">
                    <h3 class="mb-0"><i class="fas fa-bell me-2"></i>{{ $alerte->titre }}</h3>
                    <div>
                        <span class="badge bg-{{ $alerte->statut == 'resolue' ? 'success' : 'warning' }} fs-6">
                            {{ $alerte->statut == 'resolue' ? '✅ Résolue' : '🟡 En cours' }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Métriques -->
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div class="border rounded p-2">
                                <h5 class="text-primary mb-1">👁️</h5>
                                <small class="text-muted">Vues</small>
                                <div class="fw-bold">{{ $alerte->nombre_vues }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border rounded p-2">
                                <h5 class="text-info mb-1">📤</h5>
                                <small class="text-muted">Partages</small>
                                <div class="fw-bold">{{ $alerte->nombre_partages }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border rounded p-2">
                                <h5 class="text-warning mb-1">💬</h5>
                                <small class="text-muted">Commentaires</small>
                                <div class="fw-bold">{{ $alerte->commentaires->count() }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="border rounded p-2">
                                <h5 class="text-success mb-1">📅</h5>
                                <small class="text-muted">Publié</small>
                                <div class="fw-bold">{{ $alerte->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="mb-4">
                        <h5 class="text-dark">Description :</h5>
                        <p class="fs-5 text-muted" style="line-height: 1.6;">{{ $alerte->description }}</p>
                    </div>

                    <!-- Informations détaillées -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>🚨 Gravité :</strong>
                                @switch($alerte->gravite)
                                    @case('basse') <span class="badge bg-success fs-6">🟢 Basse</span> @break
                                    @case('moyenne') <span class="badge bg-warning text-dark fs-6">🟡 Moyenne</span> @break
                                    @case('haute') <span class="badge bg-danger fs-6">🔴 Haute</span> @break
                                    @case('feu') <span class="badge bg-dark fs-6">🔥 Situation Critique</span> @break
                                    @default <span class="badge bg-secondary fs-6">N/A</span>
                                @endswitch
                            </div>

                      <div class="mb-3">
    <strong>📍 Zone concernée :</strong>
    <span class="text-primary fw-bold">
        {{ $alerte->zone_geographique ?? ($alerte->localisation ?? 'Non spécifiée') }}
    </span>
    @if(!$alerte->zone_geographique && $alerte->localisation)
        <br>
        <small class="text-muted">
            (Ancien champ: {{ $alerte->localisation }})
        </small>
    @endif
</div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>👤 Publié par :</strong>
                                <span class="text-dark">{{ $alerte->user->name ?? 'Inconnu' }}</span>
                            </div>

                            @if($alerte->statut == 'resolue')
                            <div class="mb-3">
                                <strong>✅ Résolue par :</strong>
                                <span class="text-success">{{ $alerte->resolveur->name ?? 'Inconnu' }}</span>
                                <br>
                                <small class="text-muted">Le {{ $alerte->date_resolution->format('d/m/Y à H:i') }}</small>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Carte (si coordonnées disponibles) -->
                    @if($alerte->lat && $alerte->lng)
                    <div class="mt-4">
                        <h5 class="text-dark mb-3">🗺️ Localisation :</h5>
                        <div id="carte-alerte" style="height: 300px; border-radius: 10px; border: 2px solid #e9ecef;"></div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <a href="{{ route('alertes.index') }}" class="btn btn-secondary">
                            ⬅️ Retour à la liste
                        </a>
                        
                        <!-- Bouton Partager -->
                        <form action="{{ route('alertes.partager', $alerte->id) }}" method="POST" class="d-inline ms-2">
                            @csrf
                            <button type="submit" class="btn btn-info text-white">
                                📤 Partager cette alerte
                            </button>
                        </form>
                    </div>

                   @auth
    <div class="mt-2 mt-md-0">
        @if($alerte->statut == 'en_cours' && ($alerte->utilisateur_id === Auth::id() || (Auth::user() && Auth::user()->is_admin)))
            <form action="{{ route('alertes.marquer-resolue', $alerte->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" 
                        onclick="return confirm('Marquer cette alerte comme résolue ?')">
                    ✅ Marquer comme résolue
                </button>
            </form>
        @endif

        @if($alerte->utilisateur_id === Auth::id() || (Auth::user() && Auth::user()->is_admin))
            <a href="{{ route('alertes.edit', $alerte->id) }}" class="btn btn-warning ms-2">
                ✏️ Modifier
            </a>
            <form action="{{ route('alertes.destroy', $alerte->id) }}"
                  method="POST"
                  class="d-inline ms-2"
                  onsubmit="return confirm('Voulez-vous supprimer cette alerte ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
            </form>
        @endif
    </div>
@endauth
                </div>
            </div>

          <!-- Section Commentaires -->
<div class="card shadow-sm border-0" style="border-radius:15px;">
    <div class="card-header bg-light">
        <h5 class="mb-0">💬 Commentaires ({{ $alerte->commentaires->count() }})</h5>
    </div>
    
    <div class="card-body">
        <!-- Formulaire d'ajout de commentaire -->
        @auth
        <form action="{{ route('alertes.ajouter-commentaire', $alerte->id) }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="contenu" class="form-label">Ajouter un commentaire :</label>
                <textarea name="contenu" id="contenu" 
                          class="form-control @error('contenu') is-invalid @enderror" 
                          rows="3" 
                          placeholder="Partagez des informations, des conseils ou des mises à jour..."
                          required></textarea>
                @error('contenu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">📝 Publier le commentaire</button>
        </form>
        @else
        <div class="alert alert-info">
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-2">Se connecter</a>
            pour ajouter un commentaire.
        </div>
        @endauth

        <!-- Liste des commentaires -->
        <div class="commentaires-list">
            @forelse($alerte->commentaires as $commentaire)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center mb-2">
                            <strong class="text-primary">{{ $commentaire->user->name }}</strong>
                            <small class="text-muted ms-2">{{ $commentaire->created_at->diffForHumans() }}</small>
                        </div>
                        @if($commentaire->user_id === Auth::id())
                            @if(Route::has('commentaires.destroy'))
                            <form action="{{ route('commentaires.destroy', $commentaire->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Supprimer ce commentaire ?')">
                                    🗑️
                                </button>
                            </form>
                            @else
                            <small class="text-muted">(Suppression bientôt disponible)</small>
                            @endif
                        @endif
                    </div>
                    <p class="mb-0 text-dark">{{ $commentaire->contenu }}</p>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <p>Aucun commentaire pour le moment.</p>
                    <p>Soyez le premier à réagir !</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
        <!-- Sidebar avec informations complémentaires -->
        <div class="col-lg-4">
            <!-- Carte statistiques -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius:15px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📊 Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>ID Alerte :</strong>
                        <span class="text-muted">#{{ $alerte->id }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Créée le :</strong>
                        <span class="text-muted">{{ $alerte->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Dernière modification :</strong>
                        <span class="text-muted">{{ $alerte->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    @if($alerte->lat && $alerte->lng)
                    <div class="mb-3">
                        <strong>Coordonnées :</strong>
                        <br>
                        <small class="text-muted">Lat: {{ $alerte->lat }}, Lng: {{ $alerte->lng }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Alertes similaires -->
            <div class="card shadow-sm border-0" style="border-radius:15px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">🔔 Alertes similaires</h5>
                </div>
                <div class="card-body">
                    @php
                        $alertesSimilaires = \App\Models\AlerteForum::where('zone_geographique', $alerte->zone_geographique)
                            ->where('id', '!=', $alerte->id)
                            ->where('statut', 'en_cours')
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp
                    
                    @forelse($alertesSimilaires as $alerteSimilaire)
                        <div class="border-bottom pb-2 mb-2">
                            <a href="{{ route('alertes.show', $alerteSimilaire->id) }}" 
                               class="text-decoration-none">
                                <h6 class="text-dark mb-1">{{ Str::limit($alerteSimilaire->titre, 50) }}</h6>
                            </a>
                            <small class="text-muted">
                                @switch($alerteSimilaire->gravite)
                                    @case('basse') <span class="badge bg-success">Basse</span> @break
                                    @case('moyenne') <span class="badge bg-warning">Moyenne</span> @break
                                    @case('haute') <span class="badge bg-danger">Haute</span> @break
                                @endswitch
                                • {{ $alerteSimilaire->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucune autre alerte dans cette zone.</p>
                    @endforelse
                    
                    @if($alertesSimilaires->count() > 0)
                        <a href="{{ route('alertes.index') }}?zone={{ urlencode($alerte->zone_geographique) }}" 
                           class="btn btn-outline-info btn-sm w-100 mt-2">
                            Voir toutes les alertes de cette zone
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
.page-header {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                url('{{ asset('img/carousel-3.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.commentaires-list {
    max-height: 500px;
    overflow-y: auto;
}
.alert-marker {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}
</style>
@endsection

@section('scripts')
@if($alerte->lat && $alerte->lng)
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var carte = L.map('carte-alerte').setView([{{ $alerte->lat }}, {{ $alerte->lng }}], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(carte);

    // Déterminer la couleur selon la gravité
    var couleur = '';
    switch('{{ $alerte->gravite }}') {
        case 'basse': couleur = '#28a745'; break;
        case 'moyenne': couleur = '#ffc107'; break;
        case 'haute': couleur = '#dc3545'; break;
        case 'feu': couleur = '#000000'; break;
        default: couleur = '#6c757d';
    }

    // Créer un marqueur personnalisé
    var icone = L.divIcon({
        html: `<div class="alert-marker" style="background: ${couleur};"></div>`,
        className: 'alert-marker-container',
        iconSize: [25, 25]
    });

    // Ajouter le marqueur
    var marqueur = L.marker([{{ $alerte->lat }}, {{ $alerte->lng }}], {icon: icone})
        .addTo(carte)
        .bindPopup(`
            <div class="text-center">
                <h6><strong>{{ $alerte->titre }}</strong></h6>
                <p class="mb-1 small">{{ Str::limit($alerte->description, 80) }}</p>
                <span class="badge bg-${couleur.replace('#', '')}">${'{{ ucfirst($alerte->gravite) }}'}</span>
            </div>
        `);

    // Ouvrir le popup automatiquement
    marqueur.openPopup();
});
</script>
@endif

<script>
// Animation pour les nouveaux commentaires
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="ajouter-commentaire"]');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '⏳ Publication...';
            btn.disabled = true;
        });
    }
});
</script>
@endsection