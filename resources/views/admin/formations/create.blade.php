@extends('layouts.admin')

@section('title', 'Formations - Création')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Créer une Nouvelle Formation</h4>
                
                <form>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="titre">Titre de la formation</label>
                                <input type="text" class="form-control" id="titre" placeholder="Titre attractif">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prix">Prix (€)</label>
                                <input type="number" class="form-control" id="prix" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="3" placeholder="Description détaillée"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="categorie">Catégorie</label>
                                <select class="form-control" id="categorie">
                                    <option>Développement</option>
                                    <option>Design</option>
                                    <option>Marketing</option>
                                    <option>Business</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="duree">Durée (heures)</label>
                                <input type="number" class="form-control" id="duree" placeholder="10">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="niveau">Niveau</label>
                                <select class="form-control" id="niveau">
                                    <option>Débutant</option>
                                    <option>Intermédiaire</option>
                                    <option>Avancé</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="objectifs">Objectifs d'apprentissage</label>
                        <textarea class="form-control" id="objectifs" rows="3" placeholder="Ce que les apprenants vont maîtriser"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="prerequis">Prérequis</label>
                        <textarea class="form-control" id="prerequis" rows="2" placeholder="Connaissances nécessaires"></textarea>
                    </div>
                    
                    <div class="form-check form-check-flat">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" checked>
                            Formation active et visible
                        </label>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Créer la Formation</button>
                        <a href="{{ route('admin.formations.index') }}" class="btn btn-light">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection