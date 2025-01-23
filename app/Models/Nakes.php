<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nakes extends Model
{
    use HasFactory;
    protected $fillable = ['SIP','sip_expiry_date', 'nama_lengkap', 'jen_kel', 'kode_cabang'];
}
