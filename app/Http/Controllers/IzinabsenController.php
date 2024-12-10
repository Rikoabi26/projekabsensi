<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzinabsenController extends Controller
{
    public function create (){
        return view('izin.create');
    }
    public function store(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = "i";
        $keterangan = $request->keterangan;

        $data = [
            'email' => $email,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'status' => $status,
            'keterangan' => $keterangan
        ];
        $simpan = DB::table('pengajuan_izin')->insert($data);


        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data BERHASIL Diajukan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data GAGAL Diajukan']);
        }
    }

}
