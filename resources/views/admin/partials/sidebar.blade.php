<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        
        <li class="nav-item nav-category">Gestion du Site</li>

        <!-- Utilisateurs -->
        <li class="nav-item {{ request()->is('admin/utilisateurs*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#utilisateurs" aria-expanded="false">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">Utilisateurs</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/utilisateurs*') ? 'show' : '' }}" id="utilisateurs">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/utilisateurs') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.utilisateurs.index') }}">Liste des utilisateurs</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/utilisateurs/create') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.utilisateurs.create') }}">Créer un utilisateur</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/utilisateurs/roles') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.utilisateurs.roles') }}">Gestion des rôles</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Événements -->
        <li class="nav-item {{ request()->is('admin/evenements*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#evenements" aria-expanded="false">
                <i class="menu-icon mdi mdi-calendar-multiple"></i>
                <span class="menu-title">Événements</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/evenements*') ? 'show' : '' }}" id="evenements">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/evenements') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.evenements.index') }}">Liste des événements</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/evenements/create') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.evenements.create') }}">Créer un événement</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/evenements/categories') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.evenements.categories') }}">Catégories</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Challenges -->
        <li class="nav-item {{ request()->is('admin/challenges*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#challenges" aria-expanded="false">
                <i class="menu-icon mdi mdi-trophy-award"></i>
                <span class="menu-title">Challenges</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/challenges*') ? 'show' : '' }}" id="challenges">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/challenges') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.challenges.index') }}">Tous les challenges</a>
                   <li class="nav-item {{ request()->is('admin/challenges/scores/tous') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.challenges.all_scores') }}">
        Tous les scores
    </a>
</li>
                </ul>
            </div>
        </li>

        <!-- Forums -->
        <li class="nav-item {{ request()->is('admin/forums*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#forums" aria-expanded="false">
                <i class="menu-icon mdi mdi-forum"></i>
                <span class="menu-title">Forums</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/forums*') ? 'show' : '' }}" id="forums">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/forums') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.forums.index') }}">Gestion des forums</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/forums/categories') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.forums.categories') }}">Catégories</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/forums/moderations') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.forums.moderations') }}">Modération</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Formations -->
        <li class="nav-item {{ request()->is('admin/formations*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#formations" aria-expanded="false">
                <i class="menu-icon mdi mdi-school"></i>
                <span class="menu-title">Formations</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/formations*') ? 'show' : '' }}" id="formations">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/formations') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.formations.index') }}">Catalogue</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/formations/create') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.formations.create') }}">Créer une formation</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/formations/inscriptions') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.formations.inscriptions') }}">Inscriptions</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Donations -->
        <li class="nav-item {{ request()->is('admin/donations*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#donations" aria-expanded="false">
                <i class="menu-icon mdi mdi-hand-heart"></i>
                <span class="menu-title">Donations</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/donations*') ? 'show' : '' }}" id="donations">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/donations') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.donations.index') }}">Historique</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/donations/campagnes') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.donations.campagnes') }}">Campagnes</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/donations/rapports') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.donations.rapports') }}">Rapports</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item nav-category">UI Elements</li>

        <!-- UI Elements -->
        <li class="nav-item {{ request()->is('admin/ui-features*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">UI Elements</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/ui-features*') ? 'show' : '' }}" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/ui-features/buttons') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.ui-features.buttons') }}">Buttons</a>
                    </li>
                    <li class="nav-item {{ request()->is('admin/ui-features/typography') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.ui-features.typography') }}">Typography</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Forms -->
        <li class="nav-item {{ request()->is('admin/forms*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false">
                <i class="menu-icon mdi mdi-card-text-outline"></i>
                <span class="menu-title">Form elements</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/forms*') ? 'show' : '' }}" id="form-elements">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/forms/basic') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.forms.basic') }}">Basic Elements</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Charts -->
        <li class="nav-item {{ request()->is('admin/charts*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false">
                <i class="menu-icon mdi mdi-chart-line"></i>
                <span class="menu-title">Charts</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/charts*') ? 'show' : '' }}" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/charts/chartjs') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.charts.chartjs') }}">ChartJs</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Tables -->
        <li class="nav-item {{ request()->is('admin/tables*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false">
                <i class="menu-icon mdi mdi-table"></i>
                <span class="menu-title">Tables</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/tables*') ? 'show' : '' }}" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->is('admin/tables/basic') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.tables.basic') }}">Basic table</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
