@extends('layouts.admin')

@section('title', 'Formations - Inscriptions')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Gestion des Inscriptions</h4>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">Total</h4>
                                        <h2>156</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-account-multiple icon-lg"></i>
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
                                        <h4 class="card-title">Actives</h4>
                                        <h2>128</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-check-circle icon-lg"></i>
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
                                        <h4 class="card-title">En attente</h4>
                                        <h2>18</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-clock icon-lg"></i>
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
                                        <h4 class="card-title">Terminées</h4>
                                        <h2>45</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-school icon-lg"></i>
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
                                <th>Étudiant</th>
                                <th>Formation</th>
                                <th>Date Inscription</th>
                                <th>Statut</th>
                                <th>Progression</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Marie Dupont</td>
                                <td>Laravel Avancé</td>
                                <td>20/09/2024</td>
                                <td><span class="badge badge-success">Active</span></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 65%">65%</div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info">Détails</button>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jean Martin</td>
                                <td>React Masterclass</td>
                                <td>18/09/2024</td>
                                <td><span class="badge badge-warning">En attente</span></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: 0%">0%</div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success">Approuver</button>
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