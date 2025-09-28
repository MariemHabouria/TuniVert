@extends('layouts.admin')

@section('title', 'Événements - Liste')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Gestion des Événements</h4>
                    <a href="{{ route('admin.evenements.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouvel Événement
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Conférence Tech 2024</td>
                                <td>25/12/2024</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">Voir</button>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </td>
                            </tr>
                            <!-- Ajoutez plus d'événements ici -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection