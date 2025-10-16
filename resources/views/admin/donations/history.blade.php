@extends('admin.partials.layout')
@section('title','Historique des donations')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap mb-3" style="gap:.75rem;">
  <h4 class="mb-0">Historique des donations</h4>

  <form method="get" class="d-flex align-items-center" style="gap:.5rem;">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Recherche (donneur, email, campagne)">
    <select name="status" class="form-select" style="max-width:170px">
      <option value="">Statut (tous)</option>
      @php $st = request('status'); @endphp
      @foreach(['succeeded'=>'Réussie','pending'=>'En attente','failed'=>'Échouée','refunded'=>'Remboursée'] as $k=>$v)
        <option value="{{ $k }}" {{ $st===$k?'selected':'' }}>{{ $v }}</option>
      @endforeach
    </select>
    <button class="btn btn-primary">Filtrer</button>
  </form>
</div>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Donneur</th>
            <th>Email</th>
            <th>Campagne / Événement</th>
            <th>Méthode</th>
            <th>Statut</th>
            <th class="text-end">Montant</th>
            <th>Date</th>
            <th style="width:110px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($donations as $d)
          @php
            $donorName  = $d->donor_name  ?? ($d->name  ?? null);
            $donorEmail = $d->donor_email ?? ($d->email ?? null);
            if (!$donorName || !$donorEmail) {
              $raw = $d->donor ?? $d->user ?? null; $data=null;
              if (is_string($raw)) { $tmp=json_decode($raw,true); if (json_last_error()===JSON_ERROR_NONE) $data=$tmp; }
              elseif ($raw instanceof \Illuminate\Database\Eloquent\Model) { $data=['name'=>$raw->name??null,'email'=>$raw->email??null]; }
              elseif (is_array($raw)) { $data=$raw; }
              elseif (is_object($raw)) { $data=(array)$raw; }
              if (is_array($data)) {
                $donorName  = $donorName  ?: ($data['name']  ?? null);
                $donorEmail = $donorEmail ?: ($data['email'] ?? null);
              }
            }

            $campaign = $d->campaign_name ?? $d->event_title ?? $d->cause ?? $d->label ?? null;
            $method   = $d->payment_method ?? $d->provider ?? $d->gateway ?? '—';

            $status = strtolower($d->status ?? $d->payment_status ?? 'succeeded');
            $statusClass = match($status){
              'succeeded','paid','completed' => 'success',
              'pending','processing'         => 'warning',
              'failed','canceled','cancelled'=> 'danger',
              'refunded'                     => 'info',
              default                        => 'secondary'
            };

            $amount   = (float)($d->amount ?? $d->total ?? 0);
            $currency = strtoupper($d->currency ?? 'TND');
            $amountStr = number_format($amount, 2, ',', ' ') . ' ' . $currency;

            $date = $d->created_at ?? $d->paid_at ?? $d->date ?? null;
            try { $dateStr = $date ? \Illuminate\Support\Carbon::parse($date)->format('d/m/Y H:i') : '—'; }
            catch(\Throwable $e){ $dateStr = is_string($date) ? $date : '—'; }

            $showUrl = Route::has('admin.donations.show') ? route('admin.donations.show', $d->id) : null;
          @endphp

          <tr>
            <td class="fw-semibold">{{ $donorName ?? '—' }}</td>
            <td class="text-muted">{{ $donorEmail ?? '—' }}</td>
            <td>{{ $campaign ?? '—' }}</td>
            <td>{{ strtoupper($method) }}</td>
            <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst($status) }}</span></td>
            <td class="text-end">{{ $amountStr }}</td>
            <td>{{ $dateStr }}</td>
            <td>
              @if($showUrl)
                <a class="btn btn-sm btn-outline-success" href="{{ $showUrl }}">Voir</a>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted p-4">Aucune donation trouvée.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-3">
  {{ $donations->links() }}
</div>
@endsection
