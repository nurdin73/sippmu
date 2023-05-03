<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Dashboard</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i class="feather icon-home"></i></a></li>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-none">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-chart-pie text-primary"></i>
                            Statistik Keanggotaan
                        </h4>
                    </div>
                    <div class="card-body">
                        <canvas id="keanggotaan"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-none">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-chart-pie text-primary"></i>
                            Statistik Pengurus
                        </h4>
                    </div>
                    <div class="card-body">
                        <canvas id="pengurus"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-none">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-chart-pie text-primary"></i>
                            Statistik Pengurus Ranting
                        </h4>
                    </div>
                    <div class="card-body">
                        <canvas id="pengurus-ranting"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-none">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fa fa-chart-pie text-primary"></i>
                    Statistik Anggota
                </h4>
            </div>
            <div class="card-body">
                <canvas id="anggota"></canvas>
            </div>
        </div>
        <div class="card shadow-none">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fa fa-map text-primary"></i>
                    Map Viewer
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <div id="map" style="height: 500px"></div>
                    </div>
                    <div class="col-md-3" id="list-assets">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/custom-layer.js') ?>"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>