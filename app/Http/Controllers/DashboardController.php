<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use App\Models\Pengeluaran;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPemasukan = Infaq::sum('nominal');

        $totalBulanIni = Infaq::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        $totalBulanLalu = Infaq::whereMonth('tanggal', now()->subMonth()->month)
            ->whereYear('tanggal', now()->subMonth()->year)
            ->sum('nominal');

        $totalPengeluaranBulanIni = Pengeluaran::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        $grafik = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);

            $grafik[] = [
                'label' => $bulan->translatedFormat('M Y'),
                'total' => Infaq::whereMonth('tanggal', $bulan->month)
                    ->whereYear('tanggal', $bulan->year)
                    ->sum('nominal'),
            ];
        }

        return view('dashboard', compact(
            'totalPemasukan',
            'totalBulanIni',
            'totalBulanLalu',
            'totalPengeluaranBulanIni',
            'grafik'
        ));
    }
}