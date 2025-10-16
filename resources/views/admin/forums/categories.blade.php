@extends('layouts.admin')

@section('title', 'Forums - Catégories')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Catégories des Forums</h4>
                    <button class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouvelle Catégorie
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Discussion Générale</h5>
                                <p class="card-text">Forums de discussions générales</p>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">5 forums</span>
                                    <div>
                                        <button class="btn btn-sm btn-warning">Modifier</button>
                                        <button class="btn btn-sm btn-danger">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Technique</h5>
                                <p class="card-text">Discussions techniques et aides</p>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">3 forums</span>
                                    <div>
                                        <button class="btn btn-sm btn-warning">Modifier</button>
                                        <button class="btn btn-sm btn-danger">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Communauté</h5>
                                <p class="card-text">Événements et vie communautaire</p>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">4 forums</span>
                                    <div>
                                        <button class="btn btn-sm btn-warning">Modifier</button>
                                        <button class="btn btn-sm btn-danger">Supprimer</button>
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
@endsection
