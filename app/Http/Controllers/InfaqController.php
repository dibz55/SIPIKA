<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InfaqController extends Controller
{
    protected GoogleSheetService $googleSheet;

    public function __construct(GoogleSheetService $googleSheet)
    {
        $this->googleSheet = $googleSheet;
    }

    public function index()
    {
        $response = $this->googleSheet->get('Infaq');

        if (!($response['success'] ?? false)) {
            throw new \RuntimeException(
                $response['message'] ?? 'Gagal mengambil data infaq.'
            );
        }

        $data = collect($response['data'] ?? [])
            ->sortByDesc('Tanggal')
            ->values();

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $data->slice(($currentPage - 1) * $perPage, $perPage)
            ->values()
            ->map(fn ($item) => (object) [
                '_row' => $item['_row'],
                'nama' => $item['Nama'] ?? '',
                'kelas' => $item['Kelas'] ?? '',
                'tanggal' => $item['Tanggal'] ?? '',
                'nominal' => $item['Nominal'] ?? 0,
                'keterangan' => $item['Keterangan'] ?? '',
            ]);

        $infaqs = new LengthAwarePaginator(
            $items,
            $data->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('infaq.index', compact('infaqs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        $response = $this->googleSheet->post([
            'sheet' => 'Infaq',
            'action' => 'add',
            'nama' => $data['nama'],
            'kelas' => $data['kelas'],
            'tanggal' => $data['tanggal'],
            'nominal' => $data['nominal'],
            'keterangan' => $data['keterangan'],
        ]);

        if (!($response['success'] ?? false)) {
            return back()->withInput()->withErrors([
                'google_sheet' => $response['message'] ?? 'Gagal menambahkan data infaq.',
            ]);
        }

        return redirect()
            ->route('infaq.index')
            ->with('success', 'Data infaq berhasil ditambahkan.');
    }

    public function update(Request $request, $infaq)
    {
        $data = $request->validate([
            '_row' => 'required|integer|min:2',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        $response = $this->googleSheet->post([
            'sheet' => 'Infaq',
            'action' => 'update',
            '_row' => $data['_row'],
            'nama' => $data['nama'],
            'kelas' => $data['kelas'],
            'tanggal' => $data['tanggal'],
            'nominal' => $data['nominal'],
            'keterangan' => $data['keterangan'],
        ]);

        if (!($response['success'] ?? false)) {
            return back()->withInput()->withErrors([
                'google_sheet' => $response['message'] ?? 'Gagal memperbarui data infaq.',
            ]);
        }

        return redirect()
            ->route('infaq.index')
            ->with('success', 'Data infaq berhasil diperbarui.');
    }

    public function destroy($infaq)
    {
        $row = request()->input('_row', $infaq);

        if (!is_numeric($row) || (int) $row < 2) {
            return redirect()
                ->route('infaq.index')
                ->withErrors([
                    'google_sheet' => 'Nomor baris data tidak valid.',
                ]);
        }

        $response = $this->googleSheet->post([
            'sheet' => 'Infaq',
            'action' => 'delete',
            '_row' => (int) $row,
        ]);

        if (!($response['success'] ?? false)) {
            return redirect()
                ->route('infaq.index')
                ->withErrors([
                    'google_sheet' => $response['message'] ?? 'Gagal menghapus data infaq.',
                ]);
        }

        return redirect()
            ->route('infaq.index')
            ->with('success', 'Data infaq berhasil dihapus.');
    }
}

