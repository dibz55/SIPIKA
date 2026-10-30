

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">

    {{-- STATISTIK BULANAN --}}
    <div class="dashboard-stats">

        <div class="dashboard-stat-card">
            <div class="stat-title">Total Pengeluaran Bulan Ini</div>
            <div class="stat-value">
                Rp{{ number_format($totalPengeluaranBulanIni ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="dashboard-stat-card">
            <div class="stat-title">Total Pemasukan Bulan Ini</div>
            <div class="stat-value">
                Rp{{ number_format($totalBulanIni ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="dashboard-stat-card">
            <div class="stat-title">Total Pemasukan Bulan Lalu</div>
            <div class="stat-value">
                Rp{{ number_format($totalBulanLalu ?? 0, 0, ',', '.') }}
            </div>
        </div>

    </div>

    {{-- GRAFIK DAN RINGKASAN KEUANGAN --}}
    <div class="dashboard-bottom">

        {{-- GRAFIK PEMASUKAN --}}
        <div class="dashboard-chart-card">
            <h2>Grafik Pemasukan (6 Bulan Terakhir)</h2>

            <div class="chart-wrapper">
                <canvas id="grafikPemasukan"></canvas>
            </div>
        </div>

        {{-- RINGKASAN KEUANGAN --}}
        <div class="dashboard-summary-card">
            <h2>Ringkasan Keuangan</h2>

            <div class="summary-item">
                <div class="summary-icon income-icon">
                    <i class="bi bi-wallet2"></i>
                </div>

                <div class="summary-info">
                    <div class="summary-label">Total Seluruh Pemasukan</div>
                    <div class="summary-value">
                        Rp{{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="summary-item">
                <div class="summary-icon expense-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>

                <div class="summary-info">
                    <div class="summary-label">Total Seluruh Pengeluaran</div>
                    <div class="summary-value">
                        Rp{{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="summary-balance">
                <div class="summary-label">Sisa Saldo Saat Ini</div>

                <div class="summary-balance-value">
                    Rp{{ number_format(
                        ($totalPemasukan ?? 0) - ($totalPengeluaran ?? 0),
                        0,
                        ',',
                        '.'
                    ) }}
                </div>

                <div class="summary-note">
                    Total pemasukan dikurangi total pengeluaran
                </div>
            </div>

            <a href="{{ route('laporan.index') }}" class="summary-report-link">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Lihat Laporan Keuangan</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

    </div>

    {{-- IMPORT DATA SISWA --}}
    <div class="dashboard-chart-card mt-4">
        <h2>
            <i class="bi bi-people-fill me-2"></i>
            Import Data Siswa
        </h2>

        <p>
            Masukkan daftar siswa dari file CSV agar admin bisa memilih
            nama dan kelas saat melakukan input infaq massal.
        </p>

        <form action="{{ route('siswa.import') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label for="fileSiswa" class="form-label">
                    Pilih file data siswa (.csv)
                </label>

                <input
                    type="file"
                    name="file"
                    id="fileSiswa"
                    class="form-control"
                    accept=".csv,.txt"
                    required
                >

                @error('file')
                    <div class="text-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-upload me-1"></i>
                Import Data Siswa
            </button>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3 mb-0">
                {{ session('success') }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('grafikPemasukan');

    if (!canvas) return;

    new Chart(canvas, {
        type: 'bar',

        data: {
            labels: @json(collect($grafik ?? [])->pluck('label')),
            datasets: [{
                label: 'Total Infaq',
                data: @json(collect($grafik ?? [])->pluck('total')),
                backgroundColor: '#0b7658',
                borderRadius: 5,
                maxBarThickness: 65
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
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return 'Rp' + Number(value).toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
