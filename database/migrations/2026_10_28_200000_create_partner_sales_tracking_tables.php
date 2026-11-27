<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->restrictOnDelete();
            $table->string('full_name', 150);
            $table->string('whatsapp', 20)->unique();
            $table->string('service', 150);
            $table->text('notes')->nullable();
            $table->timestamp('reported_at');
            $table->timestamps();
            $table->index(['applicant_id', 'reported_at']);
        });

        Schema::create('partner_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_lead_id')->constrained()->restrictOnDelete();
            $table->string('description', 150);
            $table->unsignedBigInteger('deal_amount');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('final_amount');
            $table->timestamps();
        });

        Schema::create('partner_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_deal_id')->constrained()->restrictOnDelete();
            $table->string('payment_type', 20);
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('commission_amount');
            $table->timestamp('received_at');
            $table->timestamp('commission_paid_at')->nullable();
            $table->timestamps();
            $table->index(['received_at', 'commission_paid_at']);
        });

        Schema::create('partner_monthly_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->restrictOnDelete();
            $table->date('month');
            $table->unsignedInteger('eligible_client_count');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['applicant_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_monthly_bonuses');
        Schema::dropIfExists('partner_payments');
        Schema::dropIfExists('partner_deals');
        Schema::dropIfExists('partner_leads');
    }
};
