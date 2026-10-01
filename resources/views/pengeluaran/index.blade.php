@extends('layouts.app')

@section('content')

<div class="infaq-page">

    {{-- HEADER --}}
    <div class="infaq-header">

        <h1>Data Pengeluaran</h1>

        <button
            type="button"
            class="btn-tambah-infaq"
            data-bs-toggle="modal"
            data-bs-target="#tambahPengeluaranModal">

            <i class="bi bi-plus-circle-fill"></i>

            Tambah Pengeluaran

        </button>

    </div>


    {{-- ERROR --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- TABEL --}}
    <div class="infaq-table-wrapper">

        <table class="infaq-table">

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

                @forelse ($pengeluarans as $i => $p)

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
                            Rp {{ number_format($p->nominal, 0, ',', '.') }}
                        </td>

                        <td>

                            <div class="aksi-column">

                                {{-- EDIT --}}
                                <button
                                    type="button"
                                    class="btn-edit-infaq"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPengeluaranModal{{ $p->id }}">

                                    Edit

                                </button>


                                {{-- HAPUS --}}
                                <form
                                    action="{{ route('pengeluaran.destroy', $p) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data pengeluaran ini?');">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn-hapus-infaq">

                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>


                    {{-- ============================== --}}
                    {{-- MODAL EDIT --}}
                    {{-- ============================== --}}

                    <div
                        class="modal fade"
                        id="editPengeluaranModal{{ $p->id }}"
                        tabindex="-1"
                        aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">

                                <form
                                    method="POST"
                                    action="{{ route('pengeluaran.update', $p) }}">

                                    @csrf

                                    @method('PUT')


                                    <div class="modal-header">

                                        <h5 class="modal-title">
                                            Edit Pengeluaran
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>

                                    </div>


                                    <div class="modal-body">


                                        {{-- TANGGAL --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Tanggal
                                            </label>

                                            <input
                                                type="date"
                                                name="tanggal"
                                                class="form-control"
                                                value="{{ $p->tanggal->format('Y-m-d') }}"
                                                required>

                                        </div>


                                        {{-- KETERANGAN --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Keterangan
                                            </label>

                                            <input
                                                type="text"
                                                name="keterangan"
                                                class="form-control"
                                                value="{{ $p->keterangan }}"
                                                maxlength="150"
                                                required>

                                        </div>


                                        {{-- NOMINAL --}}
                                        <div class="mb-3">

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
                                            class="btn btn-success">

                                            Simpan Perubahan

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
                            class="text-center">

                            Belum ada data pengeluaran.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    @if ($pengeluarans->hasPages())

        <div class="infaq-pagination">

            {{ $pengeluarans->links() }}

        </div>

    @endif

</div>



{{-- ================================================== --}}
{{-- MODAL TAMBAH PENGELUARAN --}}
{{-- ================================================== --}}

<div
    class="modal fade"
    id="tambahPengeluaranModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('pengeluaran.store') }}">

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Pengeluaran
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">


                    {{-- TANGGAL --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="{{ old('tanggal', date('Y-m-d')) }}"
                            required>

                    </div>


                    {{-- KETERANGAN --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Keterangan
                        </label>

                        <input
                            type="text"
                            name="keterangan"
                            class="form-control"
                            placeholder="Contoh: Beli ATK, Biaya operasional"
                            maxlength="150"
                            value="{{ old('keterangan') }}"
                            required>

                    </div>


                    {{-- NOMINAL --}}
                    <div class="mb-3">

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
                        class="btn btn-success">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection