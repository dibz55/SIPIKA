
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('infaqs', function (Blueprint $table) {
            $table->string('keterangan')->nullable()->after('nominal');
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('infaqs', function (Blueprint $table) {
            $table->enum('status', [
                'Sudah Diterima',
                'Belum Diterima',
            ])->default('Sudah Diterima');

            $table->dropColumn('keterangan');
        });
    }
};
