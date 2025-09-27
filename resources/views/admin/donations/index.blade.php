@extends('layouts.admin')

@section('title', 'Donations - Historique')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Historique des Donations</h4>
                    <div>
                        <button class="btn btn-primary">
                            <i class="mdi mdi-download"></i> Exporter
                        </button>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">Total Collecté</h4>
                                        <h2>€12,458</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-cash icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">Donations</h4>
                                        <h2>156</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-hand-heart icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">Donateurs</h4>
                                        <h2>89</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-account-group icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">Moyenne</h4>
                                        <h2>€79.86</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-chart-line icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Donateur</th>
                                <th>Montant</th>
                                <th>Campagne</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Méthode</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#DON001</td>
                                <td>Pierre Martin</td>
                                <td class="text-success fw-bold">€100.00</td>
                                <td>Aide Humanitaire</td>
                                <td>25/09/2024</td>
                                <td><span class="badge badge-success">Complétée</span></td>
                                <td>Carte Bancaire</td>
                                <td>
                                    <button class="btn btn-sm btn-info">Détails</button>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#DON002</td>
                                <td>Sophie Lambert</td>
                                <td class="text-success fw-bold">€50.00</td>
                                <td>Éducation</td>
                                <td>24/09/2024</td>
                                <td><span class="badge badge-success">Complétée</span></td>
                                <td>PayPal</td>
                                <td>
                                    <button class="btn btn-sm btn-info">Détails</button>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#DON003</td>
                                <td>Marc Dubois</td>
                                <td class="text-success fw-bold">€200.00</td>
                                <td>Environnement</td>
                                <td>23/09/2024</td>
                                <td><span class="badge badge-warning">En attente</span></td>
                                <td>Virement</td>
                                <td>
                                    <button class="btn btn-sm btn-success">Valider</button>
                                    <button class="btn btn-sm btn-danger">Refuser</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection