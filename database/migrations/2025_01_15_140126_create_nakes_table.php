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
        Schema::create('nakes', function (Blueprint $table) {
            $table->id();
            $table->string('SIP', 30)->primary();
            $table->date('sip_expiry_date');
            $table->string('nama_lengkap', 30);
            $table->enum('jen_kel', ['Laki-laki', 'Perempuan']);
            $table->string('kode_cabang', 3);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nakes');
    }
};
