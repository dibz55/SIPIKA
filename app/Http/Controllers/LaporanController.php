<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        [$tahun, $bulanAngka] = explode('-', $bulan);

        $infaqs = Infaq::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanAngka)
            ->orderBy('tanggal')
            ->get();

        $pengeluarans = Pengeluaran::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanAngka)
            ->orderBy('tanggal')
            ->get();

        $totalInfaq = $infaqs->sum('nominal');

        $totalPengeluaran = $pengeluarans->sum('nominal');

        $saldo = $totalInfaq - $totalPengeluaran;

        return view('laporan.index', compact(
            'bulan',
            'infaqs',
            'pengeluarans',
            'totalInfaq',
            'totalPengeluaran',
            'saldo'
        ));
    }

    public function cetak(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        [$tahun, $bulanAngka] = explode('-', $bulan);

        $infaqs = Infaq::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanAngka)
            ->orderBy('tanggal')
            ->get();

        $pengeluarans = Pengeluaran::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanAngka)
            ->orderBy('tanggal')
            ->get();

        $totalInfaq = $infaqs->sum('nominal');

        $totalPengeluaran = $pengeluarans->sum('nominal');

        $saldo = $totalInfaq - $totalPengeluaran;

        return view('laporan.pdf', compact(
            'bulan',
            'infaqs',
            'pengeluarans',
            'totalInfaq',
            'totalPengeluaran',
            'saldo'
        ));
    }
}