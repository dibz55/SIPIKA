<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use Illuminate\Http\Request;

class InfaqController extends Controller
{
    public function index()
    {
        $infaqs = Infaq::orderByDesc('tanggal')->paginate(10);

        return view('infaq.index', compact('infaqs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        Infaq::create($data);

        return redirect()
            ->route('infaq.index')
            ->with('success', 'Data infaq berhasil ditambahkan.');
    }

    public function update(Request $request, Infaq $infaq)
    {
        $data = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $infaq->update($data);

        return redirect()
            ->route('infaq.index')
            ->with('success', 'Data infaq berhasil diperbarui.');
    }

    public function destroy(Infaq $infaq)
    {
        $infaq->delete();

        return redirect()
            ->route('infaq.index')
            ->with('success', 'Data infaq berhasil dihapus.');
    }
}