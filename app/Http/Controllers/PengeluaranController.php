<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PengeluaranController extends Controller
{
    protected GoogleSheetService $googleSheet;

    public function __construct(GoogleSheetService $googleSheet)
    {
        $this->googleSheet = $googleSheet;
    }

    
    public function index()
    {
        $response = $this->googleSheet->get('Pengeluaran');

        if (!($response['success'] ?? false)) {
            throw new \RuntimeException(
                $response['message'] ?? 'Gagal mengambil data pengeluaran.'
            );
        }

        $data = collect($response['data'] ?? [])
            ->map(function ($item) {
                $row = [];

                foreach ((array) $item as $key => $value) {
                    $key = strtolower(trim(
                        preg_replace('/^\xEF\xBB\xBF/', '', (string) $key)
                    ));

                    $row[$key] = $value;
                }

                return (object) [
                    '_row' => $row['_row'] ?? null,
                    'tanggal' => $row['tanggal'] ?? '',
                    'keterangan' => $row['keterangan'] ?? '',
                    'nominal' => $row['nominal'] ?? 0,
                ];
            })
            ->filter(fn ($item) => $item->_row !== null)
            ->sortByDesc('tanggal')
            ->values();

        $perPage = 10;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();

        $items = $data
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $pengeluarans = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $data->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('pengeluaran.index', compact('pengeluarans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:150',
            'nominal' => 'required|numeric|min:0',
        ]);

        $response = $this->googleSheet->post([
            'sheet' => 'Pengeluaran',
            'action' => 'add',
            'tanggal' => $data['tanggal'],
            'keterangan' => $data['keterangan'],
            'nominal' => $data['nominal'],
        ]);

        if (!($response['success'] ?? false)) {
            return back()->withInput()->withErrors([
                'google_sheet' => $response['message']
                    ?? 'Gagal menambahkan data pengeluaran.',
            ]);
        }

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, $pengeluaran)
    {
        $data = $request->validate([
            '_row' => 'required|integer|min:2',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:150',
            'nominal' => 'required|numeric|min:0',
        ]);

        $response = $this->googleSheet->post([
            'sheet' => 'Pengeluaran',
            'action' => 'update',
            '_row' => (int) $data['_row'],
            'tanggal' => $data['tanggal'],
            'keterangan' => $data['keterangan'],
            'nominal' => $data['nominal'],
        ]);

        if (!($response['success'] ?? false)) {
            return back()->withInput()->withErrors([
                'google_sheet' => $response['message']
                    ?? 'Gagal memperbarui data pengeluaran.',
            ]);
        }

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy($pengeluaran)
    {
        $row = request()->input('_row', $pengeluaran);

        if (!is_numeric($row) || (int) $row < 2) {
            return redirect()
                ->route('pengeluaran.index')
                ->withErrors([
                    'google_sheet' => 'Nomor baris data tidak valid.',
                ]);
        }

        $response = $this->googleSheet->post([
            'sheet' => 'Pengeluaran',
            'action' => 'delete',
            '_row' => (int) $row,
        ]);

        if (!($response['success'] ?? false)) {
            return redirect()
                ->route('pengeluaran.index')
                ->withErrors([
                    'google_sheet' => $response['message']
                        ?? 'Gagal menghapus data pengeluaran.',
                ]);
        }

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}

