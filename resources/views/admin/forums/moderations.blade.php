@extends('layouts.admin')

@section('title', 'Forums - Modération')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modération des Forums</h4>
                
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#signals">Signalements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#moderation">En Modération</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#utilisateurs">Utilisateurs</a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="signals">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sujet</th>
                                        <th>Raison</th>
                                        <th>Signalé par</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Problème technique urgent</td>
                                        <td>Spam</td>
                                        <td>user123</td>
                                        <td>25/09/2024</td>
                                        <td>
                                            <button class="btn btn-sm btn-success">Approuver</button>
                                            <button class="btn btn-sm btn-danger">Supprimer</button>
                                            <button class="btn btn-sm btn-info">Voir</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="moderation">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Contenu</th>
                                        <th>Modérateur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Message</td>
                                        <td>Contenu modéré...</td>
                                        <td>admin1</td>
                                        <td>
                                            <button class="btn btn-sm btn-success">Restaurer</button>
                                            <button class="btn btn-sm btn-danger">Supprimer déf.</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="utilisateurs">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Messages</th>
                                        <th>Avertissements</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>user456</td>
                                        <td>45</td>
                                        <td>2</td>
                                        <td><span class="badge badge-warning">Surveillance</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning">Avertir</button>
                                            <button class="btn btn-sm btn-danger">Bannir</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection