<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Store the PPN rate each invoice was issued with, so the PDF prints the right
 * percentage after the default rate changes. Existing invoices used 10%.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('ppn_rate', 5, 2)->default(0)->after('paid_amount');
        });

        DB::table('invoices')->where('ppn_amount', '>', 0)->update(['ppn_rate' => 10]);
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('ppn_rate');
        });
    }
};
