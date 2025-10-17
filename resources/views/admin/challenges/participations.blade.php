@extends('layouts.admin')

@section('title', 'Participants - ' . $challenge->titre)

@section('content')
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2>Participants du Challenge: {{ $challenge->titre }}</h2>
                <a href="{{ route('admin.challenges.index') }}" class="btn btn-outline-primary">Retour aux Challenges</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Points</th>
                        <th>Badge</th>
                        <th>Rang</th>
                        <th>Statut</th>
                        <th>Preuve</th>
                      
                    </tr>
                </thead>
                <tbody>
                    @forelse($challenge->participants as $p)
                        <tr>
                            <td>{{ $p->utilisateur->id ?? '-' }}</td>
                            <td>{{ $p->utilisateur->name ?? '-' }}</td>
                            <td>{{ $p->utilisateur->email ?? '-' }}</td>
                            <td>{{ $p->score?->points ?? 0 }}</td>
                            <td>{{ $p->score?->badge ?? '-' }}</td>
                            <td>{{ $p->score?->rang ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $p->statut == 'valide' ? 'success' : ($p->statut == 'rejete' ? 'danger' : ($p->statut == 'en_cours' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($p->statut) }}
                                </span>
                                @if(isset($p->ia_valide) && $p->ia_valide)
                                    <span class="badge bg-info ms-1">Validé par l'IA</span>
                                @endif
                                @if(isset($p->ia_pourcentage))
                                    <span class="badge bg-primary ms-1">IA: {{ $p->ia_pourcentage }}%</span>
                                @endif
                            </td>
                            <td>
                                @if($p->preuve)
                                    @php
                                        $fileUrl = asset('storage/' . $p->preuve);
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank" onclick="return openProofModal('{{ $fileUrl }}','{{ $p->utilisateur->name }}')">
                                        Voir
                                    </a>
                                @else
                                    <span class="text-muted">Aucune preuve</span>
                                @endif
                            </td>
                           
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Aucun participant trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Statistiques -->
        @if($challenge->participants->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card p-3">
                        <h5>Statistiques des Participants</h5>
                        <div class="row text-center">
                            <div class="col-md-3">En attente: {{ $challenge->participants->where('statut','en_cours')->count() }}</div>
                            <div class="col-md-3">Validés: {{ $challenge->participants->where('statut','valide')->count() }}</div>
                            <div class="col-md-3">Rejetés: {{ $challenge->participants->where('statut','rejete')->count() }}</div>
                            <div class="col-md-3">Autres: {{ $challenge->participants->whereIn('statut',['complet','annule'])->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal preuve -->
<div class="modal fade" id="proofModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preuve de <span id="participantName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" id="proofContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="downloadProof" class="btn btn-primary" download><i class="fas fa-download me-1"></i>Télécharger</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openProofModal(fileUrl, participantName) {
    const modal = new bootstrap.Modal(document.getElementById('proofModal'));
    document.getElementById('participantName').textContent = participantName;
    document.getElementById('downloadProof').href = fileUrl;

    const ext = fileUrl.split('.').pop().toLowerCase();
    const content = document.getElementById('proofContent');

    if(['jpg','jpeg','png','gif'].includes(ext)){
        content.innerHTML = `<img src="${fileUrl}" class="img-fluid">`;
    } else if(ext==='pdf'){
        content.innerHTML = `<embed src="${fileUrl}" type="application/pdf" class="w-100" style="height:70vh;">`;
    } else {
        content.innerHTML = `<p>Fichier non prévisualisable. Veuillez le télécharger.</p>`;
    }

    modal.show();
    return false;
}
</script>
@endsection
