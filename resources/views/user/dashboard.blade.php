@extends('layouts.main')

@section('title', 'Dashboard Pengguna')

@section('content')
    <div class="row">
        <!-- Card untuk status Pending -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending Tasks</h5>
                    <p class="card-text">Total: {{ $pendingCount }}</p>
                </div>
            </div>
        </div>

        <!-- Card untuk status Proses -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tasks in Proses</h5>
                    <p class="card-text">Total: {{ $prosesCount }}</p>
                </div>
            </div>
        </div>

        <!-- Card untuk status Selesai -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Completed Tasks</h5>
                    <p class="card-text">Total: {{ $selesaiCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card untuk Grafik -->
    <div class="row">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Task Status Chart</h4>
                </div>
                <div class="card-body">
                    <div id="chart" style="min-height: 365px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Tambahkan script untuk ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/ui-apexchart.js') }}"></script>
    <script src="{{ asset('assets/extensions/dayjs/dayjs.min.js') }}"></script>

    <script>
        var options = {
            chart: {
                type: 'area',
                height: 350,
            },
            series: [{
                name: 'Jumlah Task',
                data: [{{ $pendingCount }}, {{ $prosesCount }}, {{ $selesaiCount }}] // Data dari controller
            }],
            xaxis: {
                categories: ['Pending', 'Proses', 'Selesai'] // Label status
            },
            colors: ['#008FFB'],
            legend: {
                position: 'bottom'
            }
        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush
