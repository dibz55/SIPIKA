<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_siswa');
            $table->string('kelas', 20);
            $table->timestamps();

            $table->index('kelas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};