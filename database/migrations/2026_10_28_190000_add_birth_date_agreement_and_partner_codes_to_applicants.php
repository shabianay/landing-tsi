<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->date('birth_date')->nullable();
            $table->string('partner_code', 20)->nullable()->unique();
            $table->string('agreement_version', 20)->nullable();
            $table->timestamp('agreement_signed_at')->nullable();
        });

        Schema::create('partner_code_sequences', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedInteger('next_number')->default(0);
        });

        DB::table('partner_code_sequences')->insert([
            'id' => 1,
            'next_number' => 0,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_code_sequences');

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropUnique(['partner_code']);
            $table->dropColumn(['birth_date', 'partner_code', 'agreement_version', 'agreement_signed_at']);
        });
    }
};
