<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Client-order management ported from SIM-Proyek.
 * Tables are prefixed/named so they do not collide with the website's own
 * `projects` (portfolio) and `clients` (portfolio logos) tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('company')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('client_name');
            $table->string('task_type');
            $table->text('description')->nullable();
            $table->date('deadline');
            $table->decimal('price', 15, 2)->default(0);
            $table->string('status', 20)->default('Pending')->index();
            $table->string('payment_method', 30)->nullable();
            $table->date('payment_date')->nullable();
            $table->date('payment_lunas_date')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date')->index();
            $table->string('client_name');
            $table->text('client_address')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('ppn_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->string('discount_type', 10)->default('fixed');
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->unsignedInteger('item_qty');
            $table->decimal('item_price', 15, 2);
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 20);
            $table->string('model_type', 40);
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_clients');
    }
};
