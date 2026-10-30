<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    protected GoogleSheetService $googleSheet;

    public function __construct(GoogleSheetService $googleSheet)
    {
        $this->googleSheet = $googleSheet;
    }

    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $bulan = now()->format('Y-m');
        }

        return view('laporan.index', $this->ambilDataLaporan($bulan));
    }

    public function cetak(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $bulan = now()->format('Y-m');
        }

        return view('laporan.pdf', $this->ambilDataLaporan($bulan));
    }

    private function ambilDataLaporan(string $bulan): array
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

        $normalisasi = function ($item) {
            $hasil = [];

            foreach ((array) $item as $key => $value) {
                $key = strtolower(trim(
                    preg_replace('/^\xEF\xBB\xBF/', '', (string) $key)
                ));

                $hasil[$key] = $value;
            }

            return $hasil;
        };

        $dataInfaq = collect($responseInfaq['data'] ?? [])
            ->map($normalisasi);

        $dataPengeluaran = collect($responsePengeluaran['data'] ?? [])
            ->map($normalisasi);

        $nominalAngka = function ($nilai): float {
            if (is_numeric($nilai)) {
                return (float) $nilai;
            }

            $nilai = preg_replace('/[^\d,.-]/u', '', (string) $nilai);

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

        $awalBulan = Carbon::createFromFormat('!Y-m', $bulan);
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $dalamBulan = function ($item) use ($awalBulan, $akhirBulan) {
            $tanggal = $item['tanggal'] ?? null;

            if (empty($tanggal)) {
                return false;
            }

            try {
                $tanggal = Carbon::parse($tanggal);

                return $tanggal->betweenIncluded($awalBulan, $akhirBulan);
            } catch (\Throwable $e) {
                return false;
            }
        };

        $infaqs = $dataInfaq
            ->filter($dalamBulan)
            ->map(fn ($item) => (object) [
                'nama' => $item['nama'] ?? '',
                'kelas' => $item['kelas'] ?? '',
                'tanggal' => $item['tanggal'] ?? '',
                'nominal' => $nominalAngka($item['nominal'] ?? 0),
                'keterangan' => $item['keterangan'] ?? '',
            ])
            ->values();

        $pengeluarans = $dataPengeluaran
            ->filter($dalamBulan)
            ->map(fn ($item) => (object) [
                'tanggal' => $item['tanggal'] ?? '',
                'keterangan' => $item['keterangan'] ?? '',
                'nominal' => $nominalAngka($item['nominal'] ?? 0),
            ])
            ->values();

        $totalInfaq = $infaqs->sum('nominal');
        $totalPengeluaran = $pengeluarans->sum('nominal');
        $saldo = $totalInfaq - $totalPengeluaran;

        return compact(
            'bulan',
            'infaqs',
            'pengeluarans',
            'totalInfaq',
            'totalPengeluaran',
            'saldo'
        );
    }
}