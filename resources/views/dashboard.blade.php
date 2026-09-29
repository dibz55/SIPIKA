@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="dashboard-container">


    {{-- ========================= --}}
    {{-- STATISTIK --}}
    {{-- ========================= --}}

    <div class="dashboard-stats">


        {{-- TOTAL PENGELUARAN --}}
        <div class="dashboard-stat-card">

            <div class="stat-title">
                Total Pengeluaran
            </div>

            <div class="stat-value">
                Rp{{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}
            </div>

        </div>


        {{-- PEMASUKAN BULAN INI --}}
        <div class="dashboard-stat-card">

            <div class="stat-title">
                Total Pemasukan Bulan Ini
            </div>

            <div class="stat-value">
                Rp{{ number_format($totalBulanIni, 0, ',', '.') }}
            </div>

        </div>


        {{-- PEMASUKAN BULAN LALU --}}
        <div class="dashboard-stat-card">

            <div class="stat-title">
                Total Pemasukan Bulan Lalu
            </div>

            <div class="stat-value">
                Rp{{ number_format($totalBulanLalu, 0, ',', '.') }}
            </div>

        </div>

    </div>



    {{-- ========================= --}}
    {{-- BAGIAN BAWAH --}}
    {{-- ========================= --}}

    <div class="dashboard-bottom">


        {{-- GRAFIK --}}
        <div class="dashboard-chart-card">

            <h2>
                Grafik Pemasukan (6 Bulan Terakhir)
            </h2>

            <div class="chart-wrapper">

                <canvas id="grafikPemasukan"></canvas>

            </div>

        </div>



        {{-- MENU CEPAT --}}
        <div class="quick-menu-card">

            <h2>
                Menu Cepat
            </h2>


            <a href="{{ route('infaq.index') }}"
               class="quick-menu-button">

                <i class="bi bi-calendar3"></i>

                <span>Data Infaq</span>

            </a>


            <a href="{{ route('pengeluaran.index') }}"
               class="quick-menu-button">

                <i class="bi bi-calendar3"></i>

                <span>Data Pengeluaran</span>

            </a>


            <a href="{{ route('laporan.index') }}"
               class="quick-menu-button">

                <i class="bi bi-clipboard-text-fill"></i>

                <span>Laporan</span>

            </a>

        </div>

    </div>

</div>


@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<script>

const ctx = document.getElementById('grafikPemasukan');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: @json(collect($grafik)->pluck('label')),

        datasets: [{

            label: 'Total Infaq',

            data: @json(collect($grafik)->pluck('total')),

            backgroundColor: '#0b7658',

            borderRadius: 5

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {
                display: false
            }

        },

        scales: {

            y: {

                beginAtZero: true

            }

        }

    }

});

</script>

@endpush