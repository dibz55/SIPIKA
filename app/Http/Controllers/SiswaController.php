<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return back()->withErrors([
                'file' => 'File CSV tidak bisa dibaca.',
            ]);
        }

        $firstRow = fgets($handle);

        if ($firstRow === false) {
            fclose($handle);

            return back()->withErrors([
                'file' => 'File CSV kosong.',
            ]);
        }

        // Deteksi pemisah kolom.
        $delimiter = substr_count($firstRow, ';') > substr_count($firstRow, ',')
            ? ';'
            : ',';

        rewind($handle);

        $header = fgetcsv($handle, 0, $delimiter);

        if (!$header) {
            fclose($handle);

            return back()->withErrors([
                'file' => 'Header CSV tidak ditemukan.',
            ]);
        }

        // Normalisasi header agar tidak sensitif huruf besar,
        // spasi, underscore, tanda minus, atau BOM.
        $normalisasi = function ($value) {
            $value = preg_replace('/^\xEF\xBB\xBF/', '', (string) $value);
            $value = mb_strtolower(trim($value));

            return preg_replace('/[\s_-]+/', '', $value);
        };

        $headerNormal = array_map($normalisasi, $header);

        $namaIndex = false;

        foreach ([
            'namasiswa',
            'nama',
            'namasiswa',
            'namaanak',
        ] as $namaHeader) {
            $index = array_search($namaHeader, $headerNormal);

            if ($index !== false) {
                $namaIndex = $index;
                break;
            }
        }

        $kelasIndex = array_search('kelas', $headerNormal);

        if ($namaIndex === false || $kelasIndex === false) {
            fclose($handle);

            return back()->withErrors([
                'file' => 'Header CSV tidak cocok. Gunakan kolom Nama Siswa dan Kelas. Periksa baris pertama file CSV.',
            ]);
        }

        $berhasil = 0;
        $dilewati = 0;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $nama = trim((string) ($row[$namaIndex] ?? ''));
                $kelas = trim((string) ($row[$kelasIndex] ?? ''));

                if ($nama === '' || $kelas === '') {
                    $dilewati++;
                    continue;
                }

                // Lewati siswa yang nama dan kelasnya sudah ada.
                $sudahAda = Siswa::whereRaw(
                    'LOWER(nama_siswa) = ?',
                    [mb_strtolower($nama)]
                )->whereRaw(
                    'LOWER(kelas) = ?',
                    [mb_strtolower($kelas)]
                )->exists();

                if ($sudahAda) {
                    $dilewati++;
                    continue;
                }

                Siswa::create([
                    'nama_siswa' => $nama,
                    'kelas' => $kelas,
                ]);

                $berhasil++;
            }

            fclose($handle);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            if (is_resource($handle)) {
                fclose($handle);
            }

            report($e);

            return back()->withErrors([
                'file' => 'Impor gagal. Periksa storage/logs/laravel.log untuk detailnya.',
            ]);
        }

        return back()->with(
            'success',
            "Impor selesai. {$berhasil} siswa ditambahkan, {$dilewati} baris dilewati."
        );
    }
}
