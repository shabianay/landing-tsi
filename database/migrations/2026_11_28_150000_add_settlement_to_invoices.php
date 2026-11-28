<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * DP + pelunasan as two invoices (the studio's way of working):
 * - settles_invoice_id: on the pelunasan invoice, the DP invoice it closes (one each).
 * - credit_amount: the DP already paid, deducted on the pelunasan invoice.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('settles_invoice_id')->nullable()->unique()->after('order_id')
                ->constrained('invoices')->nullOnDelete();
            $table->decimal('credit_amount', 15, 2)->default(0)->after('discount_type');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('settles_invoice_id');
            $table->dropColumn('credit_amount');
        });
    }
};
