<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('whatsapp', 20)->unique();
            $table->string('domicile', 100)->index();
            $table->string('current_activity', 20);
            $table->text('experience')->nullable();
            $table->text('motivation');
            $table->string('social_link', 255);
            $table->boolean('agreed_commission');
            $table->string('status', 30)->default('baru')->index();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('applicant_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['applicant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_status_logs');
        Schema::dropIfExists('applicants');
    }
};
