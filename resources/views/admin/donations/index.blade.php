@extends('layouts.guest')
@section('title','Gestion des donations')

@section('content')
<div class="container">
  <div class="auth-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">Donations</h2>
      <div class="small text-white-75">
        Total: <strong>{{ number_format((float)$stats['total'], 2, ',', ' ') }} TND</strong> ·
        Dons: <strong>{{ $stats['count'] }}</strong>
      </div>
    </div>

    <form method="GET" class="row g-2 mb-3">
      
      <div class="col-md-3">
        <select class="form-select" name="moyen_paiement">
          <option value="">Moyen (tous)</option>
          <option value="carte" @selected(request('moyen_paiement')==='carte')>Carte</option>
          <option value="paypal" @selected(request('moyen_paiement')==='paypal')>PayPal</option>
          <option value="virement_bancaire" @selected(request('moyen_paiement')==='virement_bancaire')>Virement bancaire</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-white w-100">Filtrer</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-dark table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Utilisateur</th>
            <th>Montant</th>
            <th>Moyen</th>
            <th>Événement</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($dons as $don)
            <tr>
              <td>{{ $don->id }}</td>
              <td>{{ $don->date_don?->format('d/m/Y H:i') }}</td>
              <td>{{ $don->user?->name ?? '—' }}</td>
              <td>{{ number_format((float)$don->montant, 2, ',', ' ') }} TND</td>
              <td>{{ str_replace('_',' ', ucfirst($don->moyen_paiement)) }}</td>
              <td>{{ $don->evenement_id ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-3">{{ $dons->links() }}</div>
  </div>
</div>
@endsection
