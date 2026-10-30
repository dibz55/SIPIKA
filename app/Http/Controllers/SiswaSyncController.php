<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class SiswaSyncController extends Controller
{
    public function sync(GoogleSheetService $googleSheet)
    {
        try {
            // Ambil data terbaru dari Google Sheets.
            $response = $googleSheet->get('Siswa');

            if (
                !is_array($response) ||
                !($response['success'] ?? false)
            ) {
                throw new RuntimeException(
                    $response['message'] ?? 'Gagal mengambil data siswa dari Google Sheets.'
                );
            }

            // Rapikan nama kolom dan data siswa.
            $data = collect($response['data'] ?? [])
                ->map(function ($item) {
                    $row = [];

                    foreach ((array) $item as $key => $value) {
                        $key = mb_strtolower(trim((string) $key));
                        $key = preg_replace('/[^a-z0-9]/', '', $key);

                        $row[$key] = trim((string) $value);
                    }

                    return [
                        'nama_siswa' => $row['namasiswa']
                            ?? $row['nama']
                            ?? '',
                        'kelas' => $row['kelas'] ?? '',
                    ];
                })
                ->filter(function ($item) {
                    return $item['nama_siswa'] !== ''
                        && $item['kelas'] !== '';
                })
                ->unique(function ($item) {
                    return mb_strtolower($item['nama_siswa'])
                        . '|'
                        . mb_strtolower($item['kelas']);
                })
                ->values();

            // Jangan mengubah database jika data kosong.
            if ($data->isEmpty()) {
                throw new RuntimeException(
                    'Data siswa kosong atau header Google Sheets tidak sesuai.'
                );
            }

            DB::transaction(function () use ($data) {

                // Nonaktifkan semua siswa lama.
                Siswa::query()->update([
                    'aktif' => false,
                ]);

                // Aktifkan siswa yang masih ada di Google Sheets.
                foreach ($data as $item) {
                    Siswa::updateOrCreate(
                        [
                            'nama_siswa' => $item['nama_siswa'],
                            'kelas' => $item['kelas'],
                        ],
                        [
                            'aktif' => true,
                        ]
                    );
                }
            });

            return redirect()
                ->route('infaq.massal')
                ->with(
                    'success',
                    'Sinkronisasi berhasil! '
                    . $data->count()
                    . ' siswa aktif telah diperbarui dari Google Sheets.'
                );

        } catch (Throwable $e) {

            Log::error('Sinkronisasi siswa gagal', [
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'sync' => 'Sinkronisasi gagal: ' . $e->getMessage(),
            ]);
        }
    }
}
