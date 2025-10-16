@extends('layouts.admin')

@section('title', 'Donations - Rapports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Rapports et Statistiques</h4>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_debut">Date de début</label>
                            <input type="date" class="form-control" id="date_debut" value="2024-01-01">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_fin">Date de fin</label>
                            <input type="date" class="form-control" id="date_fin" value="2024-12-31">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="campagne">Campagne</label>
                            <select class="form-control" id="campagne">
                                <option>Toutes les campagnes</option>
                                <option>Aide Humanitaire</option>
                                <option>Éducation</option>
                                <option>Environnement</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Évolution des Donations</h5>
                                <canvas id="donationsChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Répartition par Campagne</h5>
                                <canvas id="campaignsChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Top Donateurs</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Donateur</th>
                                                <th>Montant Total</th>
                                                <th>Dernier don</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Pierre Martin</td>
                                                <td class="text-success fw-bold">€1,250</td>
                                                <td>25/09/2024</td>
                                            </tr>
                                            <tr>
                                                <td>Sophie Lambert</td>
                                                <td class="text-success fw-bold">€850</td>
                                                <td>24/09/2024</td>
                                            </tr>
                                            <tr>
                                                <td>Marc Dubois</td>
                                                <td class="text-success fw-bold">€600</td>
                                                <td>23/09/2024</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Méthodes de Paiement</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Méthode</th>
                                                <th>Nombre</th>
                                                <th>Montant Total</th>
                                                <th>Pourcentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Carte Bancaire</td>
                                                <td>89</td>
                                                <td>€7,845</td>
                                                <td>63%</td>
                                            </tr>
                                            <tr>
                                                <td>PayPal</td>
                                                <td>45</td>
                                                <td>€3,120</td>
                                                <td>25%</td>
                                            </tr>
                                            <tr>
                                                <td>Virement</td>
                                                <td>22</td>
                                                <td>€1,493</td>
                                                <td>12%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Rapport Détaillé</h5>
                                    <button class="btn btn-primary">
                                        <i class="mdi mdi-file-pdf"></i> Exporter PDF
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Période</th>
                                                <th>Donations</th>
                                                <th>Montant Total</th>
                                                <th>Donateurs</th>
                                                <th>Moyenne/Don</th>
                                                <th>Taux de Croissance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Septembre 2024</td>
                                                <td>45</td>
                                                <td class="text-success fw-bold">€3,845</td>
                                                <td>32</td>
                                                <td>€85.44</td>
                                                <td class="text-success">+15%</td>
                                            </tr>
                                            <tr>
                                                <td>Août 2024</td>
                                                <td>39</td>
                                                <td class="text-success fw-bold">€3,342</td>
                                                <td>28</td>
                                                <td>€85.69</td>
                                                <td class="text-success">+8%</td>
                                            </tr>
                                            <tr>
                                                <td>Juillet 2024</td>
                                                <td>36</td>
                                                <td class="text-success fw-bold">€3,095</td>
                                                <td>25</td>
                                                <td>€85.97</td>
                                                <td class="text-success">+12%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Scripts pour les graphiques (exemple)
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution des donations
    const donationsCtx = document.getElementById('donationsChart').getContext('2d');
    const donationsChart = new Chart(donationsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep'],
            datasets: [{
                label: 'Donations (€)',
                data: [1200, 1900, 1500, 2200, 1800, 2500, 3000, 2800, 3200],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Graphique de répartition
    const campaignsCtx = document.getElementById('campaignsChart').getContext('2d');
    const campaignsChart = new Chart(campaignsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aide Humanitaire', 'Éducation', 'Environnement', 'Autres'],
            datasets: [{
                data: [45, 30, 20, 5],
                backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#9E9E9E']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endsection