<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzinabsenController extends Controller
{
    public function create()
    {
        return view('izin.create');
    }
    public function store(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = "i";
        $keterangan = $request->keterangan;

        $bulan = date("m", strtotime($tgl_izin_dari));
        $tahun = date("Y", strtotime($tgl_izin_dari));
        $thn = substr($tahun, 2, 2);

        $lastizin = DB::table('pengajuan_izin')
            ->whereRaw('MONTH(tgl_izin_dari)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_izin_dari)="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->first();
        $lastkodeizin = $lastizin != null ? $lastizin->kode_izin : "";
        $format = "IZ" . $bulan . $thn;
        $kode_izin = buatkode($lastkodeizin, $format, 3);

        $data = [
            'kode_izin' => $kode_izin,
            'email' => $email,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'status' => $status,
            'keterangan' => $keterangan
        ];
        //cerk sudah absen atau belum
        $cekpresensi = DB::table('presensi')
            ->whereBetween('tgl_presensi', [$tgl_izin_dari, $tgl_izin_sampai])
            ->where('email', $email)
            ->count();

        //cek sudah di ajukan atau belum
        $cekpengajuan = DB::table('pengajuan_izin')
            ->whereRaw('"' . $tgl_izin_dari . '" BETWEEN tgl_izin_dari AND tgl_izin_sampai')
            ->where('email', $email)
            ->count();


        if ($cekpresensi > 0) {
            return redirect('/presensi/izin')->with(['error' => 'Tak bisa melakukan pengajuan di tanggal tersebut, karna ada tanggal yang anda sudah melakukan absen']);
        } else if ($cekpengajuan > 0) {
            return redirect('/presensi/izin')->with(['error' => 'Tak bisa melakukan pengajuan di tanggal tersebut, karna ada tanggal yang sudah di gunakan']);
        } else {
            $simpan = DB::table('pengajuan_izin')->insert($data);

            if ($simpan) {
                return redirect('/presensi/izin')->with(['success' => 'Data BERHASIL Diajukan']);
            } else {
                return redirect('/presensi/izin')->with(['error' => 'Data GAGAL Diajukan']);
            }
        }
    }
    public function edit($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        // dd($dataizin);
        return view('izin.edit', compact('dataizin'));
    }

    public function update($kode_izin, Request $request)
    {
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $keterangan = $request->keterangan;

        try {
            $data = [
                'tgl_izin_dari' => $tgl_izin_dari,
                'tgl_izin_sampai' => $tgl_izin_sampai,
                'keterangan' => $keterangan
            ];
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update($data);
            return redirect('/presensi/izin')->with(['success' => 'Data BERHASIL diupdate']);
        } catch (\Exception $e) {
            //throw $th;
            return redirect('/presensi/izin')->with(['error' => 'Data GAGAL Di update']);
        }
    }
}
