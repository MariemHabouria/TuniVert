@extends('layouts.admin')

@section('title', 'Événements - Détails')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Détails de l'Événement</h4>
                    <div>
                        <a href="{{ route('admin.evenements.edit', $evenement) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil"></i> Modifier
                        </a>
                        <a href="{{ route('admin.evenements.index') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td>{{ $evenement->id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Titre:</strong></td>
                                        <td>{{ $evenement->title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lieu:</strong></td>
                                        <td>{{ $evenement->location }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Catégorie:</strong></td>
                                        <td><span class="badge badge-info">{{ $evenement->category }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Description:</strong></td>
                                        <td>{{ $evenement->details ?: 'Aucune description' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Créé le:</strong></td>
                                        <td>{{ $evenement->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Modifié le:</strong></td>
                                        <td>{{ $evenement->updated_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @if($evenement->image)
                            <div class="text-center">
                                <img src="{{ asset($evenement->image) }}" alt="Event" class="img-fluid rounded">
                                <p class="mt-2 text-muted small">Image de l'événement</p>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">Aucune image</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Actions disponibles</h5>
                    <form action="{{ route('admin.evenements.destroy', $evenement) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.')">
                            <i class="mdi mdi-delete"></i> Supprimer l'événement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection