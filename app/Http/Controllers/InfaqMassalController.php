<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Services\GoogleSheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class InfaqMassalController extends Controller
{
    protected GoogleSheetService $googleSheet;

    public function __construct(GoogleSheetService $googleSheet)
    {
        $this->googleSheet = $googleSheet;
    }

    public function index()
    {
        $kelasList = Siswa::query()
            ->where('aktif', true)
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');
        return view('infaq.massal', compact('kelasList'));
    }

    public function siswa(Request $request)
    {
        $request->validate([
            'kelas' => ['required', 'string', 'max:255'],
        ]);

        $siswaList = Siswa::query()
        ->where('aktif', true)
        ->where('kelas', $request->kelas)
        ->orderBy('nama_siswa')
        ->get(['id', 'nama_siswa', 'kelas']);

        return response()->json($siswaList);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'kelas' => ['required', 'string', 'max:255'],
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['required', 'integer', 'distinct'],
            'nominal' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

       $siswaList = Siswa::query()
        ->where('aktif', true)
        ->where('kelas', $data['kelas'])
        ->whereIn('id', $data['siswa_ids'])
        ->orderBy('nama_siswa')
        ->get();

        if ($siswaList->count() !== count($data['siswa_ids'])) {
            throw ValidationException::withMessages([
                'siswa_ids' => 'Ada siswa yang tidak ditemukan atau bukan dari kelas yang dipilih.',
            ]);
        }

        $berhasil = 0;

        try {
            foreach ($siswaList as $siswa) {
                $response = $this->googleSheet->post([
                    'action' => 'add',
                    'sheet' => 'Infaq',
                    'nama' => $siswa->nama_siswa,
                    'kelas' => $siswa->kelas,
                    'tanggal' => $data['tanggal'],
                    'nominal' => (int) $data['nominal'],
                    'keterangan' => $data['keterangan'] ?? '',
                ]);

                // Apps Script yang mengembalikan status gagal
                // tidak boleh dihitung sebagai transaksi berhasil.
                if (
                    is_array($response) &&
                    (
                        ($response['success'] ?? null) === false ||
                        ($response['status'] ?? null) === 'error'
                    )
                ) {
                    throw new \RuntimeException(
                        'Google Sheets menolak transaksi untuk ' .
                        $siswa->nama_siswa
                    );
                }

                $berhasil++;
            }
        } catch (Throwable $e) {
            Log::error('Input infaq massal gagal', [
                'pesan' => $e->getMessage(),
                'jumlah_terkirim' => $berhasil,
                'jumlah_dipilih' => $siswaList->count(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'warning',
                    "Proses berhenti setelah {$berhasil} dari " .
                    $siswaList->count() .
                    ' transaksi terkirim. Periksa Google Sheets sebelum mencoba lagi agar tidak terjadi data ganda.'
                );
        }

        return redirect()
            ->route('infaq.massal')
            ->with(
                'success',
                "Berhasil mencatat infaq {$berhasil} siswa dengan total Rp" .
                number_format(
                    $berhasil * (int) $data['nominal'],
                    0,
                    ',',
                    '.'
                ) . '.'
            );
    }
}
