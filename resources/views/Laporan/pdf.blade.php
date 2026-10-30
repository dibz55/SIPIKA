<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan SIPIS</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #222;
            margin: 0;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            padding-bottom: 12px;
            margin-bottom: 20px;
            border-bottom: 2px solid #087052;
        }

        .header h1 {
            font-size: 19px;
            margin: 0 0 5px;
            color: #087052;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        .periode {
            margin-top: 10px;
            font-weight: bold;
            font-size: 12px;
        }

        .judul-section {
            font-size: 13px;
            margin: 20px 0 10px;
            color: #087052;
        }

        table.ringkasan,
        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.ringkasan {
            margin-bottom: 20px;
        }

        .ringkasan td {
            border: 1px solid #ccc;
            padding: 9px 10px;
        }

        .ringkasan .label {
            width: 55%;
            font-weight: bold;
            background: #f3f6f4;
        }

        .ringkasan .nominal {
            text-align: right;
            font-weight: bold;
        }

        .ringkasan .saldo td {
            background: #e5f4ec;
            color: #087052;
            font-weight: bold;
        }

        table.data {
            margin-bottom: 20px;
        }

        table.data thead {
            display: table-header-group;
        }

        table.data tfoot {
            display: table-row-group;
        }

        table.data tr {
            page-break-inside: avoid;
        }

        table.data th,
        table.data td {
            border: 1px solid #bbb;
            padding: 7px 8px;
            vertical-align: top;
        }

        table.data th {
            background: #eaf2ee;
            color: #222;
            text-align: left;
        }

        .kolom-no {
            width: 35px;
            text-align: center !important;
        }

        .kolom-tanggal {
            width: 85px;
            white-space: nowrap;
        }

        .kolom-nominal {
            width: 115px;
            text-align: right;
            white-space: nowrap;
        }

        .total-row td {
            font-weight: bold;
            background: #f3f6f4;
        }

        .kosong {
            text-align: center;
            color: #666;
            padding: 12px !important;
        }

        .catatan {
            font-size: 10px;
            color: #555;
            margin-top: 15px;
        }

        .pengesahan {
            width: 100%;
            border-collapse: collapse;
            margin-top: 35px;
            page-break-inside: avoid;
        }

        .pengesahan td {
            width: 50%;
            border: none;
            padding: 5px 10px;
            text-align: center;
            vertical-align: top;
        }

        .jabatan {
            font-weight: bold;
        }

        .ruang-ttd {
            height: 75px;
        }

        .nama-pejabat {
            font-weight: bold;
            text-decoration: underline;
        }

        .cetak {
            margin-top: 25px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 9px 16px;
            background: #087052;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        @media print {
            .cetak {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>LAPORAN KEUANGAN SIPIS</h1>
        <p>Sistem Pendataan Infaq Siswa</p>

        <div class="periode">
            Periode:
            {{ \Carbon\Carbon::parse($bulan . '-01')->locale('id')->translatedFormat('F Y') }}
        </div>
    </div>

    {{-- RINGKASAN KEUANGAN --}}
    <h2 class="judul-section">Ringkasan Keuangan</h2>

    <table class="ringkasan">
        <tbody>
            <tr>
                <td class="label">Total Infaq</td>
                <td class="nominal">
                    Rp{{ number_format($totalInfaq, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td class="label">Total Pengeluaran</td>
                <td class="nominal">
                    Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
                </td>
            </tr>

            <tr class="saldo">
                <td class="label">Saldo Akhir</td>
                <td class="nominal">
                    Rp{{ number_format($saldo, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- RINCIAN PENGELUARAN --}}
    <h2 class="judul-section">Rincian Pengeluaran</h2>

    <table class="data">
        <thead>
            <tr>
                <th class="kolom-no">No</th>
                <th class="kolom-tanggal">Tanggal</th>
                <th>Keterangan Penggunaan</th>
                <th class="kolom-nominal">Nominal</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pengeluarans as $i => $pengeluaran)
                <tr>
                    <td class="kolom-no">{{ $i + 1 }}</td>

                    <td class="kolom-tanggal">
                        {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}
                    </td>

                    <td>
                        {{ $pengeluaran->keterangan ?: '-' }}
                    </td>

                    <td class="kolom-nominal">
                        Rp{{ number_format($pengeluaran->nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="kosong">
                        Tidak ada pengeluaran pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">
                    TOTAL PENGELUARAN
                </td>
                <td class="kolom-nominal">
                    Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- CATATAN --}}
    <p class="catatan">
        Laporan ini memuat ringkasan keuangan dan rincian penggunaan dana.
        Data lengkap infaq siswa tetap tersimpan dalam arsip SIPIS.
    </p>

    {{-- TANDA TANGAN --}}
    <table class="pengesahan">
        <tbody>
            <tr>
                <td>
                    <div class="jabatan">Bendahara Sekolah</div>
                    <div class="ruang-ttd"></div>
                    <div class="nama-pejabat">
                        (........................................)
                    </div>
                </td>

                <td>
                    <div class="jabatan">Kepala Madrasah</div>
                    <div class="ruang-ttd"></div>
                    <div class="nama-pejabat">
                        (........................................)
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- CETAK --}}
    <div class="cetak">
        <a href="#" class="btn"
           onclick="window.print(); return false;">
            Cetak / Simpan PDF
        </a>
    </div>

</body>
</html>
