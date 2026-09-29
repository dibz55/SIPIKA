@extends('layouts.app')

@section('title', 'Laporan')

@section('content')

<div class="card-panel">

    <h5 class="mb-3">Laporan Keuangan</h5>

    {{-- Filter Bulan --}}
    <form method="GET"
          action="{{ route('laporan.index') }}"
          class="d-flex align-items-center gap-2 mb-4">

        <label class="mb-0">Pilih Bulan</label>

        <input
            type="month"
            name="bulan"
            value="{{ $bulan }}"
            class="form-control"
            style="max-width:180px;"
        >

        <button type="submit" class="btn-sipika-green">
            Tampilkan
        </button>

        <a href="{{ route('laporan.cetak', ['bulan' => $bulan]) }}"
           class="btn-sipika-red text-decoration-none">
            Cetak Laporan
        </a>

    </form>


    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card-panel">
                <small class="text-muted">Total Infaq</small>
                <h4 class="mt-2">
                    Rp{{ number_format($totalInfaq, 0, ',', '.') }}
                </h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-panel">
                <small class="text-muted">Total Pengeluaran</small>
                <h4 class="mt-2">
                    Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
                </h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-panel">
                <small class="text-muted">Saldo</small>
                <h4 class="mt-2">
                    Rp{{ number_format($saldo, 0, ',', '.') }}
                </h4>
            </div>
        </div>

    </div>


    {{-- Data Infaq --}}
    <h5 class="mb-3">Data Infaq</h5>

    <table class="sipika-table mb-4">

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

                    <td>{{ $i + 1 }}</td>

                    <td>
                        {{ $infaq->tanggal->format('d/m/Y') }}
                    </td>

                    <td>
                        Rp{{ number_format($infaq->nominal, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ $infaq->status }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Belum ada data infaq pada bulan ini.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- Data Pengeluaran --}}
    <h5 class="mb-3">Data Pengeluaran</h5>

    <table class="sipika-table">

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

                    <td>{{ $i + 1 }}</td>

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
                    <td colspan="4" class="text-center text-muted">
                        Belum ada data pengeluaran pada bulan ini.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection