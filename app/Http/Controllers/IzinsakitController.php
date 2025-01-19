<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use App\Models\IzinWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IzinsakitController extends Controller
{
    //
    public function create()
    {
        return view('sakit.create');
    }

    public function store(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = "s";
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
        //Simpan File SID

        if ($request->hasFile('sid')) {
            $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
        } else {
            $sid = null;
        }
        $data = [
            'kode_izin' => $kode_izin,
            'email' => $email,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'status' => $status,
            'keterangan' => $keterangan,
            'doc_sid' => $sid

        ];
        $cekpresensi = DB::table('presensi')
            ->whereBetween('tgl_presensi', [$tgl_izin_dari, $tgl_izin_sampai])
            ->where('email', $email)
            ->count();
        $cekpengajuan = DB::table('pengajuan_izin')
            ->whereRaw('"' . $tgl_izin_dari . '" BETWEEN tgl_izin_dari AND tgl_izin_sampai')
            ->where('email', $email)
            ->count();
        if ($cekpresensi > 0) {
            return redirect('/presensi/izin')->with(['warning' => 'Tak bisa melakukan pengajuan di tanggal tersebut, karna ada tanggal yang anda sudah melakukan absen']);
        } else if ($cekpengajuan > 0) {
            return redirect('/presensi/izin')->with(['error' => 'Tak bisa melakukan pengajuan di tanggal tersebut, karna ada tanggal yang sudah di gunakan']);
        } else {
            $simpan = DB::table('pengajuan_izin')->insert($data);
            $pengajuan_izin =  DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
            $workflows = Workflow::where('name', 'Cuti / Izin')->get();
            foreach ($workflows as $workflow) {
                IzinWorkflow::create([
                    'workflow_id' => $workflow->id,
                    'ordinal' => $workflow->ordinal,
                    'role_id' => $workflow->role_id,
                    'kode_izin' => $pengajuan_izin->kode_izin,
                    'active' => $workflow->ordinal == 1 ? 1 : 0,
                ]);
            }

            if ($simpan) {
                if ($request->hasFile('sid')) {
                    $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
                    $folderPath = "public/uploads/sid/";
                    $request->file('sid')->storeAs($folderPath, $sid);
                }
                return redirect('/presensi/izin')->with(['success' => 'Data BERHASIL Diajukan']);
            } else {
                return redirect('/presensi/izin')->with(['error' => 'Data GAGAL Diajukan']);
            }
        }
    }

    public function edit($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('sakit.edit', compact('dataizin'));
    }

    public function update(Request $request, $kode_izin)
    {

        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $keterangan = $request->keterangan;

        //Simpan File SID
        if ($request->hasFile('sid')) {
            $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
        } else {
            $sid = null;
        }
        $data = [

            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'keterangan' => $keterangan,
            'doc_sid' => $sid

        ];

        try {
            //code...
            DB::table('pengajuan_izin')
                ->where('kode_izin', $kode_izin)
                ->update($data);

            if ($request->hasFile('sid')) {
                $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
                $folderPath = "public/uploads/sid/";
                $request->file('sid')->storeAs($folderPath, $sid);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data BERHASIL Diupdate']);
        } catch (\Exception $e) {
            //throw $th;
            return redirect('/presensi/izin')->with(['error' => 'Data GAGAL Diupdate']);
        }
    }
}
