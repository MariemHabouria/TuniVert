@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('plugin-css')
<link rel="stylesheet" href="{{ asset('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/js/select.dataTables.min.css') }}">
@endsection

@section('plugin-js')
<script src="{{ asset('admin/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('admin/vendors/progressbar.js/progressbar.min.js') }}"></script>
@endsection

@section('scripts')
<script src="{{ asset('admin/js/jquery.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/js/dashboard.js') }}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Aperçu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiences</a>
                    </li>
                </ul>
                <div>
                    <div class="btn-wrapper">
                        <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Partager</a>
                        <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Imprimer</a>
                        <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Exporter</a>
                    </div>
                </div>
            </div>
            
            <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <!-- Copiez ici le contenu HTML de votre dashboard -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="statistics-details d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="statistics-title">Taux de rebond</p>
                                    <h3 class="rate-percentage">32.53%</h3>
                                    <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
                                </div>
                                <div>
                                    <p class="statistics-title">Vues de page</p>
                                    <h3 class="rate-percentage">7,682</h3>
                                    <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                                </div>
                                <!-- ... Ajoutez le reste du contenu ... -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection