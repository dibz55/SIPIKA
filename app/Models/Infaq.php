<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infaq extends Model
{
    protected $fillable = [
        'nama',
        'kelas',
        'tanggal',
        'nominal',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}