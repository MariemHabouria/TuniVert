@extends('layouts.admin')

@section('title', 'Statistiques des Scores')

@section('plugin-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('styles')
<style>
    :root {
        --primary: #5B67F1;
        --secondary: #22C55E;
        --accent: #06B6D4;
        --warning: #FACC15;

        --gradient-primary: linear-gradient(135deg, #5B67F1 0%, #7C8CFF 100%);
        --gradient-secondary: linear-gradient(135deg, #22C55E 0%, #4ADE80 100%);
        --gradient-accent: linear-gradient(135deg, #06B6D4 0%, #67E8F9 100%);
        --gradient-warning: linear-gradient(135deg, #FACC15 0%, #FDE68A 100%);
    }

    body {
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: #1e293b;
    }

    .section-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--gradient-primary);
        border-radius: 2px;
    }

    .floating-btn {
        background: var(--gradient-primary);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 12px;
        padding: 12px 22px;
        box-shadow: 0 10px 20px rgba(91, 103, 241, 0.3);
        transition: all 0.25s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .floating-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(91, 103, 241, 0.35);
    }

    /* --- Cards principales --- */
    .stats-card {
        border-radius: 18px;
        padding: 28px;
        color: #fff;
        background: var(--gradient-primary);
        box-shadow: 0 12px 24px rgba(91, 103, 241, 0.25);
        transition: all 0.3s ease;
        transform: translateY(0);
    }

    .stats-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 30px rgba(91, 103, 241, 0.35);
    }

    .stats-card-1 { background: var(--gradient-primary); }
    .stats-card-2 { background: var(--gradient-secondary); }
    .stats-card-3 { background: var(--gradient-accent); }
    .stats-card-4 { background: var(--gradient-warning); }

    .icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 15px;
    }

    .stats-card h3 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .card-label {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* --- Chart Container --- */
    .card-elevated {
        background: #fff;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .card-elevated:hover {
        transform: translateY(-4px);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 10px;
    }

    .chart-header h4 {
        color: #1e293b;
        font-weight: 600;
        margin: 0;
        font-size: 1.2rem;
    }

    .chart-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: white;
    }

    /* --- Badges --- */
    .badge-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        color: #fff;
        box-shadow: inset 0 0 0.5px rgba(255, 255, 255, 0.4),
                    0 10px 25px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        text-align: center;
        padding: 30px;
    }

    .badge-card:hover {
        transform: translateY(-5px);
    }

    .badge-card-gold { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); }
    .badge-card-silver { background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%); }
    .badge-card-bronze { background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%); }

    .badge-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.25);
        font-size: 40px;
        margin-bottom: 15px;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .badge-count {
        font-size: 3rem;
        font-weight: 800;
        margin: 10px 0;
    }

    /* --- Animation --- */
    @keyframes fadeInUp {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .stats-card, .badge-card, .card-elevated {
        animation: fadeInUp 0.6s ease both;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="section-title">📊 Tableau de Bord des Scores</h2>
                        <p class="text-muted">Analyse visuelle des performances et participations</p>
                    </div>
                    <a href="{{ route('admin.challenges.index') }}" class="floating-btn">
                        <i class="mdi mdi-arrow-left"></i> Retour
                    </a>
                </div>

                <!-- Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle-outline mr-2"></i>
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif
<!-- Filtres -->
<div class="card-elevated mb-5 p-4">
    <form method="GET" action="{{ route('admin.challenges.allScores') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="challenge_id" class="form-label fw-semibold">Filtrer par Challenge</label>
            <select name="challenge_id" id="challenge_id" class="form-select">
                <option value="">-- Tous les challenges --</option>
                @foreach($challenges as $ch)
                    <option value="{{ $ch->id }}" {{ request('challenge_id') == $ch->id ? 'selected' : '' }}>
                        {{ $ch->titre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="badge" class="form-label fw-semibold">Badge</label>
            <select name="badge" id="badge" class="form-select">
                <option value="">-- Tous --</option>
                <option value="or" {{ request('badge') == 'or' ? 'selected' : '' }}>Or</option>
                <option value="argent" {{ request('badge') == 'argent' ? 'selected' : '' }}>Argent</option>
                <option value="bronze" {{ request('badge') == 'bronze' ? 'selected' : '' }}>Bronze</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="periode" class="form-label fw-semibold">Période</label>
            <select name="periode" id="periode" class="form-select">
                <option value="">-- Toutes --</option>
                <option value="7" {{ request('periode') == '7' ? 'selected' : '' }}>7 derniers jours</option>
                <option value="30" {{ request('periode') == '30' ? 'selected' : '' }}>30 derniers jours</option>
                <option value="90" {{ request('periode') == '90' ? 'selected' : '' }}>3 derniers mois</option>
            </select>
        </div>

        <div class="col-md-2 d-flex justify-content-end">
            <button type="submit" class="floating-btn w-100">
                <i class="mdi mdi-filter-outline"></i> Filtrer
            </button>
        </div>
    </form>
</div>

                <!-- Statistiques principales -->
                <div class="row mb-5">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card stats-card-1">
                            <div class="icon-wrapper"><i class="mdi mdi-trophy"></i></div>
                            <h3>{{ $stats['total_challenges'] }}</h3>
                            <p class="card-label">Challenges</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card stats-card-2">
                            <div class="icon-wrapper"><i class="mdi mdi-account-multiple"></i></div>
                            <h3>{{ $stats['total_participants'] }}</h3>
                            <p class="card-label">Participants</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card stats-card-3">
                            <div class="icon-wrapper"><i class="mdi mdi-star"></i></div>
                            <h3>{{ $stats['total_points'] }}</h3>
                            <p class="card-label">Total Points</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card stats-card-4">
                            <div class="icon-wrapper"><i class="mdi mdi-chart-line"></i></div>
                            <h3>{{ $stats['points_moyens'] }}</h3>
                            <p class="card-label">Moyenne</p>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row mb-5">
                    <div class="col-lg-6 mb-4">
                        <div class="card-elevated">
                            <div class="chart-header">
                                <h4>📈 Performance des Challenges</h4>
                                <div class="chart-icon" style="background: var(--gradient-primary);">
                                    <i class="mdi mdi-chart-bar"></i>
                                </div>
                            </div>
                            <canvas id="performanceChart" style="height:320px;"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card-elevated">
                            <div class="chart-header">
                                <h4>🎯 Popularité des Challenges</h4>
                                <div class="chart-icon" style="background: var(--gradient-secondary);">
                                    <i class="mdi mdi-chart-pie"></i>
                                </div>
                            </div>
                            <canvas id="participantsChart" style="height:320px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Badges -->
                <h3 class="section-title">🏆 Distribution des Badges</h3>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="badge-card badge-card-gold">
                            <div class="badge-icon"><i class="mdi mdi-crown"></i></div>
                            <h4>Badge Or</h4>
                            <div class="badge-count">{{ $stats['badges_count']['Or'] ?? 0 }}</div>
                            <p>Performances exceptionnelles</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="badge-card badge-card-silver">
                            <div class="badge-icon"><i class="mdi mdi-medal"></i></div>
                            <h4>Badge Argent</h4>
                            <div class="badge-count">{{ $stats['badges_count']['Argent'] ?? 0 }}</div>
                            <p>Excellentes performances</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="badge-card badge-card-bronze">
                            <div class="badge-icon"><i class="mdi mdi-award"></i></div>
                            <h4>Badge Bronze</h4>
                            <div class="badge-count">{{ $stats['badges_count']['Bronze'] ?? 0 }}</div>
                            <p>Bonnes performances</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('plugin-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart : Performance
    const ctx1 = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json($performanceLabels),
            datasets: [{
                label: 'Points Moyens',
                data: @json($performanceData),
                backgroundColor: 'rgba(91, 103, 241, 0.85)',
                borderRadius: 10,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { color: '#64748b' } },
                x: { ticks: { color: '#64748b' }, grid: { display: false } }
            }
        }
    });

    // Chart : Popularité
    const ctx2 = document.getElementById('participantsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: @json($participantsLabels),
            datasets: [{
                data: @json($participantsData),
                backgroundColor: [
                    'rgba(91,103,241,0.85)',
                    'rgba(34,197,94,0.85)',
                    'rgba(6,182,212,0.85)',
                    'rgba(250,204,21,0.85)',
                    'rgba(244,114,182,0.85)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: '#64748b', usePointStyle: true }
                }
            },
            cutout: '65%'
        }
    });
});
</script>
@endsection
