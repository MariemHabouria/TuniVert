@extends('layouts.guest')
@section('title','Dons par événement')

@section('content')
<div class="container">
  <div class="auth-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">Événement #{{ $eventId }}</h2>
      <div class="small text-white-75">
        Total: <strong>{{ number_format((float)$stats['total'], 2, ',', ' ') }} TND</strong> ·
        Dons: <strong>{{ $stats['count'] }}</strong>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-dark table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Donateur</th>
            <th>Montant</th>
            <th>Moyen</th>
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
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  <div class="mt-3">{{ $dons->links('pagination::bootstrap-5-sm') }}</div>
  </div>
</div>
@endsection
