<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nonnakes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 30);
            $table->string('jen_kel', 30);
            $table->date('awal_kontrak');
            $table->date('habis_kontrak');
            $table->string('lama_kerja', 40);
            $table->string('kode_cabang', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nonnakes');
    }
};
