@extends('layouts.admin')

@section('title', 'Événements - Création')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Créer un Nouvel Événement</h4>
                
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom">Nom de l'événement</label>
                                <input type="text" class="form-control" id="nom" placeholder="Entrez le nom">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="4" placeholder="Description de l'événement"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lieu">Lieu</label>
                                <input type="text" class="form-control" id="lieu" placeholder="Lieu de l'événement">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prix">Prix</label>
                                <input type="number" class="form-control" id="prix" placeholder="Prix d'entrée">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="capacite">Capacité</label>
                                <input type="number" class="form-control" id="capacite" placeholder="Nombre de places">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Créer l'événement</button>
                    <a href="{{ route('admin.evenements.index') }}" class="btn btn-light">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection