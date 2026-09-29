<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->date('tanggal')->after('id');
            $table->string('keterangan', 150)->after('tanggal');
            $table->decimal('nominal', 15, 2)->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal',
                'keterangan',
                'nominal',
            ]);
        });
    }
};
