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
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:Sudah Diterima,Belum Diterima',
        ]);

        Infaq::create($data);

        return back()->with('success', 'Data infaq berhasil ditambahkan.');
    }

    public function update(Request $request, Infaq $infaq)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:Sudah Diterima,Belum Diterima',
        ]);

        $infaq->update($data);

        return back()->with('success', 'Data infaq berhasil diperbarui.');
    }

    public function destroy(Infaq $infaq)
    {
        $infaq->delete();

        return back()->with('success', 'Data infaq berhasil dihapus.');
    }
}