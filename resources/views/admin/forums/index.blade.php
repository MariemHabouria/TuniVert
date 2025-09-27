@extends('layouts.admin')

@section('title', 'Forums - Gestion')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Gestion des Forums</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addForumModal">
                        <i class="mdi mdi-plus"></i> Nouveau Forum
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">Forums</h4>
                                        <h2>15</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-forum icon-lg"></i>
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
                                        <h4 class="card-title">Sujets</h4>
                                        <h2>256</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-message-text icon-lg"></i>
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
                                        <h4 class="card-title">Messages</h4>
                                        <h2>1,248</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-comment icon-lg"></i>
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
                                        <h4 class="card-title">Membres</h4>
                                        <h2>589</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="mdi mdi-account-multiple icon-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive mt-4">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom du Forum</th>
                                <th>Description</th>
                                <th>Sujets</th>
                                <th>Messages</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Général</td>
                                <td>Discussions générales</td>
                                <td>45</td>
                                <td>289</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">Voir</button>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                    <button class="btn btn-sm btn-danger">Désactiver</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Aide et Support</td>
                                <td>Section d'aide aux membres</td>
                                <td>32</td>
                                <td>156</td>
                                <td><span class="badge badge-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">Voir</button>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                    <button class="btn btn-sm btn-danger">Désactiver</button>
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