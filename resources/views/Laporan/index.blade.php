@extends('layouts.app')

@section('content')

<div class="laporan-page">

    {{-- HEADER --}}
    <div class="laporan-header">
        <div>
            <h1>Laporan</h1>
            <p>Laporan Keuangan</p>
        </div>

        <a href="{{ route('laporan.cetak', ['bulan' => $bulan]) }}"
           target="_blank"
           class="btn-cetak-laporan">
            <i class="bi bi-printer-fill"></i>
            Cetak Laporan
        </a>
    </div>

    {{-- FILTER --}}
    <div class="laporan-filter">
        <form method="GET" action="{{ route('laporan.index') }}">

            <div class="filter-group">

                <label for="bulan">Pilih Bulan</label>

                <input
                    type="month"
                    name="bulan"
                    id="bulan"
                    value="{{ $bulan }}"
                >

                <button type="submit" class="btn-tampilkan-laporan">
                    <i class="bi bi-search"></i>
                    Tampilkan
                </button>

            </div>

        </form>
    </div>

    {{-- RINGKASAN --}}
    <div class="laporan-summary">

        <div class="laporan-card">
            <div class="laporan-card-icon">
                <i class="bi bi-wallet2"></i>
            </div>

            <div>
                <span>Total Infaq</span>
                <h2>
                    Rp{{ number_format($totalInfaq, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <div class="laporan-card">
            <div class="laporan-card-icon">
                <i class="bi bi-cart-dash"></i>
            </div>

            <div>
                <span>Total Pengeluaran</span>
                <h2>
                    Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <div class="laporan-card laporan-card-saldo">
            <div class="laporan-card-icon">
                <i class="bi bi-cash-stack"></i>
            </div>

            <div>
                <span>Saldo</span>
                <h2>
                    Rp{{ number_format($saldo, 0, ',', '.') }}
                </h2>
            </div>
        </div>

    </div>

    {{-- DATA INFAQ --}}
    <div class="laporan-section">

        <div class="laporan-section-header">
            <h2>Data Infaq</h2>
        </div>

        <div class="laporan-table-wrapper">

            <table class="laporan-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($infaqs as $i => $infaq)

                        <tr>

                            <td>
                                {{ $i + 1 }}
                            </td>

                            <td>
                                {{ $infaq->tanggal->format('d/m/Y') }}
                            </td>

                            <td>
                                Rp{{ number_format($infaq->nominal, 0, ',', '.') }}
                            </td>

                            <td>

                                @if($infaq->status === 'Sudah Diterima')

                                    <span class="status-diterima">
                                        Sudah Diterima
                                    </span>

                                @else

                                    <span class="status-belum">
                                        Belum Diterima
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="laporan-empty">
                                Belum ada data infaq pada bulan ini.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- DATA PENGELUARAN --}}
    <div class="laporan-section">

        <div class="laporan-section-header">
            <h2>Data Pengeluaran</h2>
        </div>

        <div class="laporan-table-wrapper">

            <table class="laporan-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pengeluarans as $i => $pengeluaran)

                        <tr>

                            <td>
                                {{ $i + 1 }}
                            </td>

                            <td>
                                {{ $pengeluaran->tanggal->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $pengeluaran->keterangan }}
                            </td>

                            <td>
                                Rp{{ number_format($pengeluaran->nominal, 0, ',', '.') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="laporan-empty">
                                Belum ada data pengeluaran pada bulan ini.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection