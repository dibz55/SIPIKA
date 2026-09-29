@extends('layouts.app')

@section('title', 'Data Infaq')

@section('content')

<div class="card-panel">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="mb-0">DATA INFAQ</h5>

        <button
            class="btn-sipika-green"
            data-bs-toggle="modal"
            data-bs-target="#tambahInfaqModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Infaq
        </button>

    </div>

    <table class="sipika-table">

        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse($infaqs as $i => $infaq)

                <tr>

                    <td>
                        {{ $infaqs->firstItem() + $i }}
                    </td>

                    <td>
                        {{ $infaq->tanggal->format('d/m/Y') }}
                    </td>

                    <td>
                        Rp{{ number_format($infaq->nominal, 0, ',', '.') }}
                    </td>

                    <td>

                        @if($infaq->status === 'Sudah Diterima')

                            <span class="badge bg-success">
                                Sudah Diterima
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                Belum Diterima
                            </span>

                        @endif

                    </td>

                    <td>

                        <button
                            class="btn-sipika-yellow"
                            data-bs-toggle="modal"
                            data-bs-target="#editInfaqModal{{ $infaq->id }}">
                            Edit
                        </button>

                        <form
                            action="{{ route('infaq.destroy', $infaq) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Hapus data ini?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn-sipika-red">
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>


                {{-- MODAL EDIT --}}

                <div
                    class="modal fade"
                    id="editInfaqModal{{ $infaq->id }}"
                    tabindex="-1">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <form
                                method="POST"
                                action="{{ route('infaq.update', $infaq) }}">

                                @csrf
                                @method('PUT')

                                <div class="modal-header">

                                    <h6 class="modal-title">
                                        Edit Infaq
                                    </h6>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <label class="form-label">
                                        Tanggal
                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal"
                                        class="form-control mb-3"
                                        value="{{ $infaq->tanggal->format('Y-m-d') }}"
                                        required>


                                    <label class="form-label">
                                        Nominal
                                    </label>

                                    <input
                                        type="number"
                                        name="nominal"
                                        class="form-control mb-3"
                                        value="{{ $infaq->nominal }}"
                                        min="0"
                                        required>


                                    <label class="form-label">
                                        Status
                                    </label>

                                    <select
                                        name="status"
                                        class="form-select"
                                        required>

                                        <option
                                            value="Sudah Diterima"
                                            @selected($infaq->status === 'Sudah Diterima')>
                                            Sudah Diterima
                                        </option>

                                        <option
                                            value="Belum Diterima"
                                            @selected($infaq->status === 'Belum Diterima')>
                                            Belum Diterima
                                        </option>

                                    </select>

                                </div>

                                <div class="modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                        Batal
                                    </button>

                                    <button
                                        type="submit"
                                        class="btn-sipika-green">
                                        Simpan
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="text-center text-muted">

                        Belum ada data

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

    <div class="mt-3">
        {{ $infaqs->links() }}
    </div>

</div>


{{-- MODAL TAMBAH --}}

<div
    class="modal fade"
    id="tambahInfaqModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('infaq.store') }}">

                @csrf

                <div class="modal-header">

                    <h6 class="modal-title">
                        Tambah Infaq
                    </h6>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        class="form-control mb-3"
                        value="{{ old('tanggal', date('Y-m-d')) }}"
                        required>


                    <label class="form-label">
                        Nominal
                    </label>

                    <input
                        type="number"
                        name="nominal"
                        class="form-control mb-3"
                        min="0"
                        placeholder="Masukkan nominal"
                        value="{{ old('nominal') }}"
                        required>


                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required>

                        <option value="Sudah Diterima"
                            {{ old('status', 'Sudah Diterima') === 'Sudah Diterima' ? 'selected' : '' }}>
                            Sudah Diterima
                        </option>

                        <option value="Belum Diterima"
                            {{ old('status') === 'Belum Diterima' ? 'selected' : '' }}>
                            Belum Diterima
                        </option>

                    </select>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn-sipika-green">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection