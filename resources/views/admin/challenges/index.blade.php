@extends('layouts.admin')

@section('title', 'Challenges - Liste')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Gestion des Challenges</h4>
                    <a href="{{ route('admin.challenges.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouveau Challenge
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Participants</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Challenge Coding 2024</td>
                                <td>01/01/2024</td>
                                <td>31/12/2024</td>
                                <td>150</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">Voir</button>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
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