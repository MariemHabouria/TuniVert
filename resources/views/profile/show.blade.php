@extends('layouts.guest')
@section('title', 'Mon profil')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card auth-card">
        <div class="card-body p-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="avatar bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                  style="width:56px;height:56px;font-size:1.25rem;">
              {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div>
              <h1 class="h4 mb-0">{{ $user->name }}</h1>
              <div class="text-muted">{{ $user->email }}</div>
            </div>
          </div>

          <hr>

          <dl class="row mb-0">
            <dt class="col-sm-4">Nom</dt>
            <dd class="col-sm-8">{{ $user->name }}</dd>

            <dt class="col-sm-4">Email</dt>
            <dd class="col-sm-8">{{ $user->email }}</dd>

            <dt class="col-sm-4">Membre depuis</dt>
            <dd class="col-sm-8">{{ $user->created_at?->format('d/m/Y H:i') }}</dd>
          </dl>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
