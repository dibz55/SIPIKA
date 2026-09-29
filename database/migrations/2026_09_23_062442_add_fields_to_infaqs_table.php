<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('infaqs', function (Blueprint $table) {
            $table->date('tanggal')->after('id');
            $table->decimal('nominal', 15, 2)->after('tanggal');
            $table->enum('status', ['Sudah Diterima', 'Belum Diterima'])
                ->default('Sudah Diterima')
                ->after('nominal');
        });
    }

    public function down(): void
    {
        Schema::table('infaqs', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal',
                'nominal',
                'status',
            ]);
        });
    }
};