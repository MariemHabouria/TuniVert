<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('recherche.avancee') }}" method="GET">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="q" class="form-control" placeholder="🔍 Rechercher..." 
                           value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-control">
                        <option value="tous" {{ request('type') == 'tous' ? 'selected' : '' }}>📝 Tous</option>
                        <option value="forums" {{ request('type') == 'forums' ? 'selected' : '' }}>💬 Forums</option>
                        <option value="alertes" {{ request('type') == 'alertes' ? 'selected' : '' }}>🚨 Alertes</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="gravite" class="form-control">
                        <option value="tous" {{ request('gravite') == 'tous' ? 'selected' : '' }}>🚨 Toutes urgences</option>
                        <option value="feu" {{ request('gravite') == 'feu' ? 'selected' : '' }}>🔥 Feu</option>
                        <option value="haute" {{ request('gravite') == 'haute' ? 'selected' : '' }}>⚠️ Haute</option>
                        <option value="moyenne" {{ request('gravite') == 'moyenne' ? 'selected' : '' }}>📢 Moyenne</option>
                        <option value="basse" {{ request('gravite') == 'basse' ? 'selected' : '' }}>💬 Basse</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                </div>
            </div>
        </form>
    </div>
</div>