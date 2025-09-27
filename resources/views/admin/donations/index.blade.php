<<<<<<< HEAD
@extends('layouts.admin')
@extends('layouts.admin')
@section('title','Gestion des donations')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Donations</h4>
                    <div class="text-muted small">
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
                            <option value="paymee" @selected(request('moyen_paiement')==='paymee')>Paymee</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Utilisateur</th>
                                <th>Montant</th>
                                <th>Moyen</th>
                                <th>Événement</th>
                                <th>Transaction</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dons as $don)
                                <tr>
                                    <td>{{ $don->id }}</td>
                                    <td>{{ $don->date_don?->format('d/m/Y H:i') }}</td>
                                    <td>{{ $don->user?->name ?? '—' }}</td>
                                    <td class="fw-semibold">{{ number_format((float)$don->montant, 2, ',', ' ') }} TND</td>
                                    <td>{{ str_replace('_',' ', ucfirst($don->moyen_paiement)) }}</td>
                                    <td>{{ $don->evenement_id ?? '-' }}</td>
                                    <td class="text-truncate" style="max-width:140px;" title="{{ $don->transaction_id }}">{{ $don->transaction_id ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $dons->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
                            <div class="card-body">
