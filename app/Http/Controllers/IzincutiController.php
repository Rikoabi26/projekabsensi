<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzincutiController extends Controller
{
    //
    public function create()
    {
        $mastercuti = DB::table('master_cuti')->orderBy('kode_cuti')->get();
        return view('izincuti.create', compact('mastercuti'));
    }

    public function store(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $kode_cuti = $request->kode_cuti;
        $status = "c";
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
        //hitung jumla hhari yang di ajukan
        $jmlhari = hitunghari($tgl_izin_dari, $tgl_izin_sampai);

        //cek jumlah maksimal cuti
        $cuti = DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->first();

        $jmlmaxcuti = $cuti->jml_hari;

        //cek jml cuti yang sudah digunakan
        $cutidigunakan = DB::table('presensi')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('status', 'c')
            ->where('email', $email)
            ->count();

        //sisa cuti
        $sisacuti = $jmlmaxcuti - $cutidigunakan;

        $data = [
            'kode_izin' => $kode_izin,
            'email' => $email,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'kode_cuti' => $kode_cuti,
            'status' => $status,
            'keterangan' => $keterangan
        ];


        $cekpresensi = DB::table('presensi')
            ->whereBetween('tgl_presensi', [$tgl_izin_dari, $tgl_izin_sampai])
            ->where('email', $email)
            ->count();
        $cekpengajuan = DB::table('pengajuan_izin')
            ->whereRaw('"' . $tgl_izin_dari . '" BETWEEN tgl_izin_dari AND tgl_izin_sampai')
            ->where('email', $email)
            ->count();

        if ($jmlhari > $sisacuti) {
            return redirect('/presensi/izin')->with(['error' => 'Tak bisa melakukan pengajuan cuti jatah hari nya sudah habis, sisa cuti ' . $sisacuti . " Hari"]);
        } else if ($cekpresensi > 0) {
            return redirect('/presensi/izin')->with(['warning' => 'Tak bisa melakukan pengajuan di tanggal tersebut, karna ada tanggal yang anda sudah melakukan absen']);
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
        $mastercuti = DB::table('master_cuti')->orderBy('kode_cuti')->get();
        return view('izincuti.edit', compact('mastercuti', 'dataizin'));
    }

    public function update($kode_izin, Request $request)
    {
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $keterangan = $request->keterangan;
        $kode_cuti = $request->kode_cuti;

        try {
            $data = [
                'tgl_izin_dari' => $tgl_izin_dari,
                'tgl_izin_sampai' => $tgl_izin_sampai,
                'keterangan' => $keterangan,
                'kode_cuti' => $kode_cuti
            ];
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update($data);
            return redirect('/presensi/izin')->with(['success' => 'Data BERHASIL diupdate']);
        } catch (\Exception $e) {
            //throw $th;
            return redirect('/presensi/izin')->with(['error' => 'Data GAGAL Di update']);
        }
    }

    public function getmaxcuti(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;
        $kode_cuti = $request->kode_cuti;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tahun_cuti = date('Y', strtotime($tgl_izin_dari));
        $cuti = DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->first();
        if ($kode_cuti == "C01") {
            $cuti_digunakan = DB::table('presensi')
                ->join('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
                ->where('presensi.status', 'c')
                ->where('kode_cuti', 'C01')
                ->whereRaw('YEAR(tgl_presensi)="' . $tahun_cuti . '"')
                ->where('presensi.email', $email)
                ->count();
            $max_cuti = $cuti->jml_hari - $cuti_digunakan;
        } else {
            $max_cuti = $cuti->jml_hari;
        }
        
        return $max_cuti;
    }
}
