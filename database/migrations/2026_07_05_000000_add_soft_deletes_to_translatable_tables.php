<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('process_steps', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('partners', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('popups', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('process_steps', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('partners', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('popups', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
