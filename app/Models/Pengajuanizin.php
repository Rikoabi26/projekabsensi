<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuanizin extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'pengajuan_izin';


    public function izinWorkflow($kode_izin)
    {
        $izin_workflow = IzinWorkflow::where('kode_izin', $kode_izin)->orderBy('ordinal')->get();
        return $izin_workflow;
    }
}
