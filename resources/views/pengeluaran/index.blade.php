@extends('layouts.app')

@section('title', 'Data Pengeluaran')

@section('content')

<div class="card-panel">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="mb-0">DATA PENGELUARAN</h5>

        <button
            class="btn-sipika-green"
            data-bs-toggle="modal"
            data-bs-target="#tambahPengeluaranModal">

            <i class="bi bi-plus-lg"></i>
            Tambah Pengeluaran

        </button>

    </div>


    <table class="sipika-table">

        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse($pengeluarans as $i => $p)

                <tr>

                    <td>
                        {{ $pengeluarans->firstItem() + $i }}
                    </td>

                    <td>
                        {{ $p->tanggal->format('d/m/Y') }}
                    </td>

                    <td>
                        {{ $p->keterangan }}
                    </td>

                    <td>
                        Rp{{ number_format($p->nominal, 0, ',', '.') }}
                    </td>

                    <td>

                        <button
                            class="btn-sipika-yellow"
                            data-bs-toggle="modal"
                            data-bs-target="#editPengeluaranModal{{ $p->id }}">

                            Edit

                        </button>

                        <form
                            action="{{ route('pengeluaran.destroy', $p) }}"
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
                    id="editPengeluaranModal{{ $p->id }}"
                    tabindex="-1">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <form
                                method="POST"
                                action="{{ route('pengeluaran.update', $p) }}">

                                @csrf
                                @method('PUT')

                                <div class="modal-header">

                                    <h6 class="modal-title">
                                        Edit Pengeluaran
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
                                        value="{{ $p->tanggal->format('Y-m-d') }}"
                                        required>


                                    <label class="form-label">
                                        Keterangan
                                    </label>

                                    <input
                                        type="text"
                                        name="keterangan"
                                        class="form-control mb-3"
                                        value="{{ $p->keterangan }}"
                                        maxlength="150"
                                        required>


                                    <label class="form-label">
                                        Nominal
                                    </label>

                                    <input
                                        type="number"
                                        name="nominal"
                                        class="form-control"
                                        value="{{ $p->nominal }}"
                                        min="0"
                                        required>

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
        {{ $pengeluarans->links() }}
    </div>

</div>


{{-- MODAL TAMBAH --}}

<div
    class="modal fade"
    id="tambahPengeluaranModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('pengeluaran.store') }}">

                @csrf

                <div class="modal-header">

                    <h6 class="modal-title">
                        Tambah Pengeluaran
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
                        Keterangan
                    </label>

                    <input
                        type="text"
                        name="keterangan"
                        class="form-control mb-3"
                        placeholder="Contoh: Beli ATK, Biaya operasional"
                        maxlength="150"
                        value="{{ old('keterangan') }}"
                        required>


                    <label class="form-label">
                        Nominal
                    </label>

                    <input
                        type="number"
                        name="nominal"
                        class="form-control"
                        min="0"
                        placeholder="Masukkan nominal"
                        value="{{ old('nominal') }}"
                        required>

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