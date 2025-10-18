<div class="btn-group mb-3">
    <a href="{{ route('forums.index', ['tri' => 'recent']) }}" 
       class="btn btn-outline-primary {{ request('tri', 'recent') == 'recent' ? 'active' : '' }}">
        ⏰ Récents
    </a>
    <a href="{{ route('forums.index', ['tri' => 'populaire']) }}" 
       class="btn btn-outline-primary {{ request('tri') == 'populaire' ? 'active' : '' }}">
        🔥 Populaires
    </a>
    <a href="{{ route('forums.index', ['tri' => 'actif']) }}" 
       class="btn btn-outline-primary {{ request('tri') == 'actif' ? 'active' : '' }}">
        💬 Plus actifs
    </a>
</div>