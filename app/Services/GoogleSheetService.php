<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleSheetService
{
    protected string $url;

    public function __construct()
    {
        $this->url = config('services.google_sheet.url');
    }

    public function get(string $sheet)
    {
        return Http::timeout(15)
            ->get($this->url, [
                'sheet' => $sheet,
            ])
            ->json();
    }

    public function post(array $data)
    {
        return Http::timeout(15)
            ->post($this->url, $data)
            ->json();
    }
}