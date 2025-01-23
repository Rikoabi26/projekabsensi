<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonNakes extends Model
{
    use HasFactory;
    protected $table = 'nonnakes';

    protected $fillable = [
        'nama_lengkap',
        'awal_kontrak',
        'habis_kontrak',
        'lama_kerja',
        'jen_kel',
        'kode_cabang',
    ];
}
