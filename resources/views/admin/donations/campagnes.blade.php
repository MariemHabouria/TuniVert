@extends('layouts.admin')

@section('title', 'Donations - Campagnes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Gestion des Campagnes</h4>
                    <button class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouvelle Campagne
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card campaign-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge badge-success">Active</span>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Modifier</a>
                                            <a class="dropdown-item" href="#">Statistiques</a>
                                            <a class="dropdown-item text-danger" href="#">Désactiver</a>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Aide Humanitaire Urgente</h5>
                                <p class="card-text">Soutien aux populations affectées par les catastrophes naturelles.</p>
                                
                                <div class="campaign-progress mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>€8,450 collectés</span>
                                        <span>€10,000 objectif</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 84.5%"></div>
                                    </div>
                                </div>
                                
                                <div class="campaign-stats">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <small>Donateurs</small>
                                            <h6 class="mb-0">67</h6>
                                        </div>
                                        <div class="col-4">
                                            <small>Jours restants</small>
                                            <h6 class="mb-0">15</h6>
                                        </div>
                                        <div class="col-4">
                                            <small>Partages</small>
                                            <h6 class="mb-0">124</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card campaign-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge badge-info">À venir</span>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Modifier</a>
                                            <a class="dropdown-item" href="#">Statistiques</a>
                                            <a class="dropdown-item text-success" href="#">Activer</a>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Éducation pour Tous</h5>
                                <p class="card-text">Scolarisation des enfants défavorisés dans le monde.</p>
                                
                                <div class="campaign-progress mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>€0 collectés</span>
                                        <span>€25,000 objectif</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <div class="campaign-stats">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <small>Donateurs</small>
                                            <h6 class="mb-0">0</h6>
                                        </div>
                                        <div class="col-4">
                                            <small>Début dans</small>
                                            <h6 class="mb-0">7j</h6>
                                        </div>
                                        <div class="col-4">
                                            <small>Partages</small>
                                            <h6 class="mb-0">0</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card campaign-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge badge-success">Active</span>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Modifier</a>
                                            <a class="dropdown-item" href="#">Statistiques</a>
                                            <a class="dropdown-item text-danger" href="#">Désactiver</a>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Protection Environnement</h5>
                                <p class="card-text">Lutte contre la déforestation et protection de la biodiversité.</p>
                                
                                <div class="campaign-progress mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>€3,245 collectés</span>
                                        <span>€15,000 objectif</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" style="width: 21.6%"></div>
                                    </div>
                                </div>
                                
                                <div class="campaign-stats">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <small>Donateurs</small>
                                            <h6 class="mb-0">42</h6>
                                        </div>
                                        <div class="col-4">
                                            <small>Jours restants</small>
                                            <h6 class="mb-0">42</h6>
                                        </div>
                                        <div class="col-4">
                                            <small>Partages</small>
                                            <h6 class="mb-0">89</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.campaign-card {
    transition: transform 0.2s;
}
.campaign-card:hover {
    transform: translateY(-5px);
}
</style>
@endsection