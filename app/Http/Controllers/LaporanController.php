<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LaporanController extends Controller
{
    protected GoogleSheetService $googleSheet;

    public function __construct(GoogleSheetService $googleSheet)
    {
        $this->googleSheet = $googleSheet;
    }

    public function index(Request $request)
    {
        $request->validate([
            'bulan' => 'nullable|date_format:Y-m',
        ]);

        $bulan = $request->input('bulan', now()->format('Y-m'));

        $laporan = $this->ambilDataLaporan($bulan);

        return view('laporan.index', array_merge(
            ['bulan' => $bulan],
            $laporan
        ));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'bulan' => 'nullable|date_format:Y-m',
        ]);

        $bulan = $request->input('bulan', now()->format('Y-m'));

        $laporan = $this->ambilDataLaporan($bulan);

        return view('laporan.pdf', array_merge(
            ['bulan' => $bulan],
            $laporan
        ));
    }

    private function ambilDataLaporan(string $bulan): array
    {
        $responseInfaq = $this->googleSheet->get('Infaq');
        $responsePengeluaran = $this->googleSheet->get('Pengeluaran');

        if (!($responseInfaq['success'] ?? false)) {
            throw new \RuntimeException(
                $responseInfaq['message'] ?? 'Gagal mengambil data infaq.'
            );
        }

        if (!($responsePengeluaran['success'] ?? false)) {
            throw new \RuntimeException(
                $responsePengeluaran['message'] ?? 'Gagal mengambil data pengeluaran.'
            );
        }

        $infaqs = collect($responseInfaq['data'] ?? [])
            ->map(function ($item) {
                $tanggal = $this->normalisasiTanggal(
                    $item['Tanggal'] ?? ''
                );

                return (object) [
                    'nama' => $item['Nama'] ?? '',
                    'nama_siswa' => $item['Nama'] ?? '',
                    'kelas' => $item['Kelas'] ?? '',
                    'tanggal' => $tanggal,
                    'nominal' => (float) ($item['Nominal'] ?? 0),
                    'keterangan' => $item['Keterangan'] ?? '',
                ];
            })
            ->filter(fn ($item) => $item->tanggal !== ''
                && substr($item->tanggal, 0, 7) === $bulan)
            ->sortBy('tanggal')
            ->values();

        $pengeluarans = collect($responsePengeluaran['data'] ?? [])
            ->map(function ($item) {
                return (object) [
                    'tanggal' => $this->normalisasiTanggal(
                        $item['Tanggal'] ?? ''
                    ),
                    'keterangan' => $item['Keterangan'] ?? '',
                    'nominal' => (float) ($item['Nominal'] ?? 0),
                ];
            })
            ->filter(fn ($item) => $item->tanggal !== ''
                && substr($item->tanggal, 0, 7) === $bulan)
            ->sortBy('tanggal')
            ->values();

        $totalInfaq = $infaqs->sum('nominal');
        $totalPengeluaran = $pengeluarans->sum('nominal');
        $saldo = $totalInfaq - $totalPengeluaran;

        return compact(
            'infaqs',
            'pengeluarans',
            'totalInfaq',
            'totalPengeluaran',
            'saldo'
        );
    }

    private function normalisasiTanggal($tanggal): string
    {
        if (empty($tanggal)) {
            return '';
        }

        try {
            $tanggal = (string) $tanggal;

            // Tanggal ISO dari Google Sheets dapat menggunakan UTC.
            if (preg_match('/T.*(?:Z|[+-]\d{2}:\d{2})$/', $tanggal)) {
                return Carbon::parse($tanggal)
                    ->setTimezone(config('app.timezone'))
                    ->format('Y-m-d');
            }

            return Carbon::parse($tanggal)->format('Y-m-d');
        } catch (\Throwable $e) {
            return '';
        }
    }
}

