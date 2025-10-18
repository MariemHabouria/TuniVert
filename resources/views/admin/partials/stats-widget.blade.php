<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h4>{{ $stats['total'] ?? 0 }}</h4>
                <p>Total sujets</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h4>{{ $stats['aujourdhui'] ?? 0 }}</h4>
                <p>Aujourd'hui</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h4>{{ $stats['populaires'] ?? 0 }}</h4>
                <p>Populaires</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h4 id="notifications-count">0</h4>
                <p>Notifications</p>
            </div>
        </div>
    </div>
</div>

<script>
// Actualiser le compteur de notifications
function actualiserNotifications() {
    fetch('{{ route("api.notifications.non-lues") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('notifications-count').textContent = data.count;
        });
}

// Actualiser toutes les 30 secondes
setInterval(actualiserNotifications, 30000);
actualiserNotifications(); // Premier chargement
</script>