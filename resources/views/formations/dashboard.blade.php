@extends('layouts.site') {{-- ou ton layout principal --}}
@section('title', 'Statistiques formations')

@section('content')
<div class="container py-4">
  <h1 class="mb-4">Statistiques – Mes formations</h1>

  {{-- KPI cards --}}
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Total formations</div>
          <div class="fs-2 fw-bold">{{ $kpis['total'] }}</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Capacité totale</div>
          <div class="fs-2 fw-bold">{{ $kpis['capacite'] }}</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Inscriptions totales</div>
          <div class="fs-2 fw-bold">{{ $inscriptionsTotal }}</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Taux de remplissage</div>
          <div class="fs-2 fw-bold">{{ $remplissageMoyen }}%</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Statuts --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm h-100"><div class="card-body">
        <div class="text-muted small">Ouvertes</div>
        <div class="fs-3 fw-bold">{{ $kpis['ouvertes'] }}</div>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm h-100"><div class="card-body">
        <div class="text-muted small">Suspendues</div>
        <div class="fs-3 fw-bold">{{ $kpis['suspendues'] }}</div>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm h-100"><div class="card-body">
        <div class="text-muted small">Terminées</div>
        <div class="fs-3 fw-bold">{{ $kpis['terminees'] }}</div>
      </div></div>
    </div>
  </div>

  {{-- Graphiques --}}
  <div class="row g-3 mb-4">
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title mb-3">Par catégorie</h5>
          <canvas id="chartCategorie" height="220"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title mb-3">Inscriptions (30 jours)</h5>
          <canvas id="chartInscriptions" height="220"></canvas>
        </div>
      </div>
    </div>
  </div>

  {{-- Par type + Top 5 --}}
  <div class="row g-3 mb-4">
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title mb-3">Par type</h5>
          <canvas id="chartType" height="220"></canvas>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title mb-3">Top 5 par inscriptions</h5>
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead>
                <tr>
                  <th>Titre</th>
                  <th class="text-center">Inscrits</th>
                  <th class="text-center">Capacité</th>
                  <th class="text-center">Statut</th>
                  <th class="text-center">Note moy.</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topFormations as $f)
                  <tr>
                    <td>
                      <a href="{{ route('formations.show', $f->id) }}" class="text-decoration-none">
                        {{ $f->titre }}
                      </a>
                    </td>
                    <td class="text-center">{{ $f->inscrits }}</td>
                    <td class="text-center">{{ $f->capacite }}</td>
                    <td class="text-center"><span class="badge bg-secondary">{{ $f->statut }}</span></td>
                    <td class="text-center">
                      @php $n = $notesMoyennes[$f->id] ?? null; @endphp
                      {{ $n ? number_format($n,2) : '—' }}
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="text-center text-muted">Aucune donnée</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Données PHP -> JS
  const dataCategorie   = @json($parCategorie);
  const dataType        = @json($parType);
  const dataInscJour    = @json($inscriptionsParJour);

  // Catégories (doughnut)
  new Chart(document.getElementById('chartCategorie'), {
    type: 'doughnut',
    data: {
      labels: Object.keys(dataCategorie),
      datasets: [{ data: Object.values(dataCategorie) }]
    }
  });

  // Types (bar)
  new Chart(document.getElementById('chartType'), {
    type: 'bar',
    data: {
      labels: Object.keys(dataType),
      datasets: [{ label: 'Formations', data: Object.values(dataType) }]
    },
    options: { plugins: { legend: { display:false } } }
  });

  // Inscriptions 30 jours (line)
  new Chart(document.getElementById('chartInscriptions'), {
    type: 'line',
    data: {
      labels: Object.keys(dataInscJour),
      datasets: [{ label: 'Inscriptions', data: Object.values(dataInscJour), tension: .3 }]
    }
  });
</script>
@endpush
