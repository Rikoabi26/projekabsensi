<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nakes extends Model
{
    use HasFactory;
    protected $fillable = ['SIP', 'nama_lengkap', 'jen_kel', 'kode_cabang'];
}
