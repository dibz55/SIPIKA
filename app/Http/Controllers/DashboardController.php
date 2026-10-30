<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected GoogleSheetService $googleSheet;

    public function __construct(GoogleSheetService $googleSheet)
    {
        $this->googleSheet = $googleSheet;
    }

    public function index()
    {
        $responseInfaq = $this->googleSheet->get('Infaq');
        $responsePengeluaran = $this->googleSheet->get('Pengeluaran');

        if (!($responseInfaq['success'] ?? false)) {
            throw new \RuntimeException(
                $responseInfaq['message'] ?? 'Gagal mengambil data Infaq.'
            );
        }

        if (!($responsePengeluaran['success'] ?? false)) {
            throw new \RuntimeException(
                $responsePengeluaran['message'] ?? 'Gagal mengambil data Pengeluaran.'
            );
        }

        // Normalisasi nama header Google Sheets.
        $normalisasi = function ($item) {
            $hasil = [];

            foreach ((array) $item as $key => $value) {
                $key = strtolower(trim((string) $key));
                $hasil[$key] = $value;
            }

            return $hasil;
        };

        $dataInfaq = collect($responseInfaq['data'] ?? [])
            ->map($normalisasi);

        $dataPengeluaran = collect($responsePengeluaran['data'] ?? [])
            ->map($normalisasi);

        // Konversi nominal menjadi angka.
        // Mendukung format 5000, 5.000, Rp5.000, dan 5.000,50.
        $nominalAngka = function ($nilai): float {
            if (is_numeric($nilai)) {
                return (float) $nilai;
            }

            $nilai = trim((string) $nilai);
            $nilai = preg_replace('/[^\d,.-]/u', '', $nilai);

            if ($nilai === '' || $nilai === null) {
                return 0;
            }

            if (str_contains($nilai, ',')) {
                $nilai = str_replace('.', '', $nilai);
                $nilai = str_replace(',', '.', $nilai);
            } else {
                $nilai = str_replace('.', '', $nilai);
            }

            return is_numeric($nilai) ? (float) $nilai : 0;
        };

        $bulanSekarang = now()->startOfMonth();
        $bulanLalu = now()->subMonth()->startOfMonth();

        // Menghitung total berdasarkan bulan transaksi.
        $jumlahBulan = function ($data, Carbon $bulan) use ($nominalAngka) {
            return $data
                ->filter(fn ($item) => $this->tanggalSesuaiBulan(
                    $item['tanggal'] ?? null,
                    $bulan
                ))
                ->sum(fn ($item) => $nominalAngka(
                    $item['nominal'] ?? 0
                ));
        };

        // TOTAL SELURUH PEMASUKAN.
        $totalPemasukan = $dataInfaq->sum(
            fn ($item) => $nominalAngka($item['nominal'] ?? 0)
        );

        // TOTAL SELURUH PENGELUARAN.
        $totalPengeluaran = $dataPengeluaran->sum(
            fn ($item) => $nominalAngka($item['nominal'] ?? 0)
        );

        // Statistik pemasukan bulanan.
        $totalBulanIni = $jumlahBulan(
            $dataInfaq,
            $bulanSekarang
        );

        $totalBulanLalu = $jumlahBulan(
            $dataInfaq,
            $bulanLalu
        );

        // Statistik pengeluaran bulan ini.
        $totalPengeluaranBulanIni = $jumlahBulan(
            $dataPengeluaran,
            $bulanSekarang
        );

        // Grafik pemasukan enam bulan terakhir.
        $grafik = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->startOfMonth()->subMonths($i);

            $grafik[] = [
                'label' => $bulan->translatedFormat('M Y'),
                'total' => $jumlahBulan($dataInfaq, $bulan),
            ];
        }

        return view('dashboard', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'totalBulanIni',
            'totalBulanLalu',
            'totalPengeluaranBulanIni',
            'grafik'
        ));
    }

    private function tanggalSesuaiBulan($tanggal, Carbon $bulan): bool
    {
        if (empty($tanggal)) {
            return false;
        }

        try {
            $tanggal = Carbon::parse($tanggal);

            return $tanggal->month === $bulan->month
                && $tanggal->year === $bulan->year;
        } catch (\Throwable $e) {
            return false;
        }
    }
}