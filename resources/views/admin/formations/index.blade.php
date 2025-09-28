@extends('layouts.admin')

@section('title', 'Formations - Catalogue')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Catalogue des Formations</h4>
                    <a href="{{ route('admin.formations.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouvelle Formation
                    </a>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Rechercher une formation...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Toutes les catégories</option>
                                <option>Développement</option>
                                <option>Design</option>
                                <option>Marketing</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Tous les statuts</option>
                                <option>Actif</option>
                                <option>Inactif</option>
                                <option>Brouillon</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ asset('admin/images/samples/300x300/3.jpg') }}" class="card-img-top" alt="Formation">
                            <div class="card-body">
                                <h5 class="card-title">Laravel Avancé</h5>
                                <p class="card-text">Maîtrisez Laravel avec des techniques avancées.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">€199</span>
                                    <span class="badge badge-success">Actif</span>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-info w-100 mb-2">Voir</button>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-warning flex-fill">Modifier</button>
                                        <button class="btn btn-sm btn-danger flex-fill">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ asset('admin/images/samples/300x300/2.jpg') }}" class="card-img-top" alt="Formation">
                            <div class="card-body">
                                <h5 class="card-title">React Masterclass</h5>
                                <p class="card-text">Devenez expert en React et écosystème.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">€249</span>
                                    <span class="badge badge-success">Actif</span>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-info w-100 mb-2">Voir</button>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-warning flex-fill">Modifier</button>
                                        <button class="btn btn-sm btn-danger flex-fill">Supprimer</button>
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