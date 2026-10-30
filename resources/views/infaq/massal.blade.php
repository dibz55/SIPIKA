
@extends('layouts.app')

@section('title', 'Input Infaq Massal')

@section('content')
<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Input Infaq Massal</h4>
            <p class="text-muted mb-0">
                Catat infaq beberapa siswa sekaligus.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <form action="{{ route('siswa.sync') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-arrow-repeat me-1"></i>
                    Sinkronkan Daftar Siswa
                </button>
            </form>

            <a href="{{ route('infaq.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Data Infaq
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong><i class="bi bi-exclamation-circle me-1"></i>
                Periksa kembali data berikut:
            </strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM INPUT MASSAL --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('infaq.massal.store') }}"
                  id="formInfaqMassal">
                @csrf

                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <label for="tanggal" class="form-label fw-semibold">
                            Tanggal Infaq
                        </label>
                        <input type="date"
                               class="form-control"
                               id="tanggal"
                               name="tanggal"
                               value="{{ old('tanggal', date('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="col-md-8">
                        <label for="kelas" class="form-label fw-semibold">
                            Pilih Kelas
                        </label>
                        <select class="form-select"
                                id="kelas"
                                name="kelas"
                                required>
                            <option value="">-- Pilih kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas }}"
                                    @selected(old('kelas') == $kelas)>
                                    {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="nominal" class="form-label fw-semibold">
                            Nominal per Siswa (Rp)
                        </label>
                        <input type="number"
                               class="form-control"
                               id="nominal"
                               name="nominal"
                               min="1"
                               step="1"
                               value="{{ old('nominal', 2000) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="keterangan" class="form-label fw-semibold">
                            Keterangan
                        </label>
                        <input type="text"
                               class="form-control"
                               id="keterangan"
                               name="keterangan"
                               maxlength="500"
                               value="{{ old('keterangan') }}"
                               placeholder="Contoh: Infaq harian">
                    </div>

                </div>

                {{-- DAFTAR SISWA --}}
                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Daftar Siswa</h5>
                        <small class="text-muted">
                            Pilih siswa yang akan dicatat infaqnya.
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button"
                                class="btn btn-sm btn-outline-success"
                                id="pilihSemua"
                                disabled>
                            <i class="bi bi-check-all me-1"></i>
                            Pilih Semua
                        </button>

                        <button type="button"
                                class="btn btn-sm btn-outline-secondary"
                                id="hapusPilihan"
                                disabled>
                            <i class="bi bi-x-circle me-1"></i>
                            Hapus Pilihan
                        </button>
                    </div>
                </div>

                <div id="statusSiswa" class="alert alert-light border">
                    <i class="bi bi-info-circle me-1"></i>
                    Pilih kelas untuk menampilkan daftar siswa.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="60">Pilih</th>
                                <th width="60">No.</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody id="daftarSiswa">
                            <tr>
                                <td colspan="4"
                                    class="text-center text-muted py-4">
                                    Belum ada siswa ditampilkan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- RINGKASAN --}}
                <div class="card bg-light border-0 my-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">
                                    Jumlah siswa terpilih
                                </div>
                                <div class="fs-4 fw-bold"
                                     id="jumlahSiswa">0 siswa</div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">
                                    Total infaq
                                </div>
                                <div class="fs-4 fw-bold text-success"
                                     id="totalInfaq">Rp0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="btn btn-success px-4"
                        id="simpanInfaq"
                        disabled>
                    <i class="bi bi-save me-1"></i>
                    Simpan Infaq Massal
                </button>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const kelas = document.getElementById('kelas');
    const nominal = document.getElementById('nominal');
    const daftar = document.getElementById('daftarSiswa');
    const status = document.getElementById('statusSiswa');
    const jumlah = document.getElementById('jumlahSiswa');
    const total = document.getElementById('totalInfaq');
    const pilihSemua = document.getElementById('pilihSemua');
    const hapusPilihan = document.getElementById('hapusPilihan');
    const simpan = document.getElementById('simpanInfaq');
    const form = document.getElementById('formInfaqMassal');

    let siswa = [];

    const idsLama = @json(array_map(
        'strval',
        old('siswa_ids', [])
    ));

    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    function hitungTotal() {
        const terpilih = daftar.querySelectorAll(
            '.pilih-siswa:checked'
        ).length;

        const nilai = Math.max(0, Number(nominal.value) || 0);

        jumlah.textContent = terpilih + ' siswa';
        total.textContent = formatRupiah(terpilih * nilai);

        simpan.disabled = terpilih === 0 || nilai < 1;
        pilihSemua.disabled = siswa.length === 0;
        hapusPilihan.disabled = terpilih === 0;
    }

    function tampilkanSiswa() {
        daftar.innerHTML = '';

        if (siswa.length === 0) {
            daftar.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        Tidak ada siswa di kelas ini.
                    </td>
                </tr>`;

            status.textContent = 'Tidak ada siswa ditemukan di kelas ini.';
            status.className = 'alert alert-warning';
            hitungTotal();
            return;
        }

        siswa.forEach(function (item, index) {
            const tr = document.createElement('tr');

            const tdPilih = document.createElement('td');
            const checkbox = document.createElement('input');

            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input pilih-siswa';
            checkbox.name = 'siswa_ids[]';
            checkbox.value = item.id;
            checkbox.checked = idsLama.includes(String(item.id));
            checkbox.addEventListener('change', hitungTotal);

            tdPilih.appendChild(checkbox);

            const tdNo = document.createElement('td');
            tdNo.textContent = index + 1;

            const tdNama = document.createElement('td');
            tdNama.textContent = item.nama_siswa;

            const tdKelas = document.createElement('td');
            tdKelas.textContent = item.kelas;

            tr.append(tdPilih, tdNo, tdNama, tdKelas);
            daftar.appendChild(tr);
        });

        status.textContent = siswa.length + ' siswa ditemukan.';
        status.className = 'alert alert-success';

        hitungTotal();
    }

    kelas.addEventListener('change', async function () {
        siswa = [];
        daftar.innerHTML = '';

        jumlah.textContent = '0 siswa';
        total.textContent = 'Rp0';

        simpan.disabled = true;
        pilihSemua.disabled = true;
        hapusPilihan.disabled = true;

        if (!kelas.value) {
            status.textContent =
                'Pilih kelas untuk menampilkan daftar siswa.';
            status.className = 'alert alert-light border';
            return;
        }

        status.textContent = 'Memuat daftar siswa...';
        status.className = 'alert alert-info';

        try {
            const url = new URL(
                @json(route('infaq.massal.siswa')),
                window.location.origin
            );

            url.searchParams.set('kelas', kelas.value);

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil daftar siswa.');
            }

            siswa = await response.json();
            tampilkanSiswa();

        } catch (error) {
            status.textContent =
                'Gagal memuat siswa. Muat ulang halaman dan coba lagi.';
            status.className = 'alert alert-danger';
        }
    });

    pilihSemua.addEventListener('click', function () {
        daftar.querySelectorAll('.pilih-siswa').forEach(function (cb) {
            cb.checked = true;
        });

        hitungTotal();
    });

    hapusPilihan.addEventListener('click', function () {
        daftar.querySelectorAll('.pilih-siswa').forEach(function (cb) {
            cb.checked = false;
        });

        hitungTotal();
    });

    nominal.addEventListener('input', hitungTotal);

    form.addEventListener('submit', function (event) {
        if (!daftar.querySelector('.pilih-siswa:checked')) {
            event.preventDefault();
            alert('Pilih minimal satu siswa terlebih dahulu.');
            return;
        }

        if (!confirm('Simpan transaksi infaq untuk semua siswa terpilih?')) {
            event.preventDefault();
        }
    });

    if (kelas.value) {
        kelas.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
