<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>TuniVert - Modifier l'événement</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Bootstrap & Template Stylesheet -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>
        @include('layouts.navbar')
        <div style="height: 112px"></div>

<h1 class="text-center my-5">Modifier l'événement</h1>

@if ($errors->any())
    <div class="alert alert-danger w-75 mx-auto">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
$categories = [
    'Nettoyage de plage',
    'Plantation d’arbres',
    'Sensibilisation au recyclage',
    'Conférence environnementale',
    'Campagne de réduction des déchets',
    'Énergie renouvelable',
    'Journée écologique scolaire',
    'Écotourisme',
    'Collecte de fonds écologique'
];
@endphp

<form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="w-75 mx-auto p-4 border rounded shadow-sm bg-light">
    @csrf
    @method('PUT')

    <div class="form-floating mb-3">
        <input type="text" name="title" class="form-control" id="title" placeholder="Titre" value="{{ old('title', $event->title) }}">
        <label for="title">Titre</label>
    </div>

    <div class="form-floating mb-3 position-relative">
        <input type="text" name="location" class="form-control" id="location" placeholder="Lieu" value="{{ old('location', $event->location) }}">
        <label for="location">Lieu</label>
        <div id="location-suggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
    </div>

    <div class="form-floating mb-3">
        <input type="date" name="date" class="form-control" id="date" placeholder="Date" value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d')) }}">
        <label for="date">Date</label>
    </div>

    <div class="form-floating mb-3">
        <select name="category" id="category" class="form-select">
            <option value="" disabled>Choisir une catégorie</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ old('category', $event->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
        <label for="category">Catégorie</label>
    </div>

    <div class="form-floating mb-3">
        <textarea name="details" class="form-control" placeholder="Détails" id="details" style="height: 120px">{{ old('details', $event->details) }}</textarea>
        <label for="details">Détails</label>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" class="form-control" id="image" accept="image/*" onchange="previewImage(event)">
        @if($event->image)
            <img id="imagePreview" src="{{ Storage::url($event->image) }}" class="img-fluid mt-3" style="max-height: 200px;" />
        @else
            <img id="imagePreview" class="img-fluid mt-3 d-none" style="max-height: 200px;" />
        @endif
    </div>

    <button type="submit" class="btn btn-success w-100 py-2">Enregistrer les modifications</button>
</form>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    if(input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('d-none');
    }
}

// Location autocomplete
$(document).ready(function() {
    $('#location').on('input', function() {
        let query = $(this).val();
        if(query.length < 2){
            $('#location-suggestions').empty();
            return;
        }

        $.get('https://nominatim.openstreetmap.org/search', {
            q: query + ', Tunisie',
            format: 'json',
            addressdetails: 1,
            limit: 5
        }, function(data) {
            let suggestions = '';
            data.forEach(function(item) {
                suggestions += `<a href="#" class="list-group-item list-group-item-action" data-display="${item.display_name}">${item.display_name}</a>`;
            });
            $('#location-suggestions').html(suggestions);
        });
    });

    $(document).on('click', '#location-suggestions a', function(e) {
        e.preventDefault();
        let val = $(this).data('display');
        $('#location').val(val);
        $('#location-suggestions').empty();
    });

    $(document).click(function(e) {
        if(!$(e.target).closest('#location').length) {
            $('#location-suggestions').empty();
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
<script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
