<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infaqs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->decimal('nominal', 12, 2);
            $table->enum('status', [
                'Sudah Diterima',
                'Belum Diterima'
            ])->default('Sudah Diterima');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infaqs');
    }
};