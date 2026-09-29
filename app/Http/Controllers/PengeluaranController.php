<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::orderByDesc('tanggal')->paginate(10);

        return view('pengeluaran.index', compact('pengeluarans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:150',
            'nominal' => 'required|numeric|min:0',
        ]);

        Pengeluaran::create($data);

        return back()->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:150',
            'nominal' => 'required|numeric|min:0',
        ]);

        $pengeluaran->update($data);

        return back()->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return back()->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}