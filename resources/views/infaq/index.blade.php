@extends('layouts.app')

@section('content')

<div class="infaq-page">

    {{-- HEADER --}}
    <div class="infaq-header">
        <h1>Data Infaq</h1>

        <button
            type="button"
            class="btn-tambah-infaq"
            data-bs-toggle="modal"
            data-bs-target="#modalTambahInfaq">
            <i class="bi bi-plus-circle-fill"></i>
            Tambah Infaq
        </button>
    </div>

    {{-- PESAN --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($infaqs as $index => $infaq)
                    <tr>
                        <td>
                            {{ $infaqs->firstItem() + $index }}
                        </td>

                        <td>{{ $infaq->nama }}</td>

                        <td>{{ $infaq->kelas }}</td>

                        <td>
                            @php
                                try {
                                    $tanggalTampil = \Carbon\Carbon::parse(
                                        $infaq->tanggal
                                    )->format('d-m-Y');
                                } catch (\Throwable $e) {
                                    $tanggalTampil = $infaq->tanggal;
                                }
                            @endphp

                            {{ $tanggalTampil }}
                        </td>

                        <td>
                            Rp {{ number_format((float) $infaq->nominal, 0, ',', '.') }}
                        </td>

                        <td>{{ $infaq->keterangan }}</td>

                        <td>
                            <div class="aksi-column">

                                {{-- EDIT --}}
                                <button
                                    type="button"
                                    class="btn-edit-infaq"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $infaq->_row }}">
                                    Edit
                                </button>

                                {{-- HAPUS --}}
                                <form
                                    action="{{ route('infaq.destroy', $infaq->_row) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data infaq ini?');">

                                    @csrf
                                    @method('DELETE')

                                    <input
                                        type="hidden"
                                        name="_row"
                                        value="{{ $infaq->_row }}">

                                    <button
                                        type="submit"
                                        class="btn-hapus-infaq">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada data infaq.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if ($infaqs->hasPages())
        <div class="infaq-pagination">
            {{ $infaqs->links() }}
        </div>
    @endif

</div>


{{-- MODAL TAMBAH --}}
<div
    class="modal fade"
    id="modalTambahInfaq"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Infaq</h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>

            <form action="{{ route('infaq.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            placeholder="Contoh: Agus"
                            value="{{ old('nama') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input
                            type="text"
                            name="kelas"
                            class="form-control"
                            placeholder="Contoh: 2B"
                            value="{{ old('kelas') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="{{ old('tanggal', date('Y-m-d')) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <input
                            type="number"
                            name="nominal"
                            class="form-control"
                            placeholder="Contoh: 5000"
                            min="0"
                            step="1"
                            value="{{ old('nominal') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input
                            type="text"
                            name="keterangan"
                            class="form-control"
                            placeholder="Contoh: Infaq"
                            value="{{ old('keterangan', 'Infaq') }}"
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

                    <button type="submit" class="btn btn-success">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


{{-- MODAL EDIT --}}
@foreach ($infaqs as $infaq)
    <div
        class="modal fade"
        id="modalEdit{{ $infaq->_row }}"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Infaq</h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <form
                    action="{{ route('infaq.update', $infaq->_row) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <input
                        type="hidden"
                        name="_row"
                        value="{{ $infaq->_row }}">

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="{{ $infaq->nama }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <input
                                type="text"
                                name="kelas"
                                class="form-control"
                                value="{{ $infaq->kelas }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input
                                type="date"
                                name="tanggal"
                                class="form-control"
                                value="{{ old('tanggal', \Carbon\Carbon::parse($infaq->tanggal)->format('Y-m-d')) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nominal</label>
                            <input
                                type="number"
                                name="nominal"
                                class="form-control"
                                value="{{ $infaq->nominal }}"
                                min="0"
                                step="1"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <input
                                type="text"
                                name="keterangan"
                                class="form-control"
                                value="{{ $infaq->keterangan }}"
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

                        <button type="submit" class="btn btn-success">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endforeach

@endsection

