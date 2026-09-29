<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Laporan SIPIKA</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #222;
            margin: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .periode {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        .ringkasan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .ringkasan td {
            border: 1px solid #999;
            padding: 10px;
        }

        .ringkasan .label {
            font-weight: bold;
            width: 35%;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table.data th,
        table.data td {
            border: 1px solid #999;
            padding: 7px 8px;
        }

        table.data th {
            background: #eee;
            text-align: left;
        }

        .judul-section {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .cetak {
            margin-top: 30px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: #168aad;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        @media print {

            .cetak {
                display: none;
            }

            body {
                margin: 15px;
            }

        }

    </style>

</head>

<body>

    <h2>LAPORAN KEUANGAN SIPIKA</h2>

    <div class="periode">
        Periode:
        {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}
    </div>


    {{-- Ringkasan --}}
    <table class="ringkasan">

        <tr>
            <td class="label">
                Total Infaq
            </td>

            <td>
                Rp{{ number_format($totalInfaq, 0, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Total Pengeluaran
            </td>

            <td>
                Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Saldo
            </td>

            <td>
                Rp{{ number_format($saldo, 0, ',', '.') }}
            </td>
        </tr>

    </table>


    {{-- Data Infaq --}}
    <h3 class="judul-section">
        Data Infaq
    </h3>

    <table class="data">

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
                        {{ $infaq->status }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4">
                        Belum ada data infaq.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- Data Pengeluaran --}}
    <h3 class="judul-section">
        Data Pengeluaran
    </h3>

    <table class="data">

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
                    <td colspan="4">
                        Belum ada data pengeluaran.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="cetak">

        <a href="#" class="btn" onclick="window.print()">
            Cetak / Simpan PDF
        </a>

    </div>

</body>

</html>