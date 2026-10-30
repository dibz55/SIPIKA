
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('infaqs', 'keterangan')) {
            Schema::table('infaqs', function (Blueprint $table) {
                $table->string('keterangan')->nullable()->after('nominal');
            });
        }

        if (Schema::hasColumn('infaqs', 'status')) {
            Schema::table('infaqs', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down(): void
    {
        // Tidak membatalkan perubahan secara otomatis
        // agar data yang sudah ada tetap aman.
    }
};
