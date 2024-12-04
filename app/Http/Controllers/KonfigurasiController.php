<?php

namespace App\Http\Controllers;

use App\Models\Setjamkerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function lokasikantor()
    {
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('konfigurasi.lokasikantor', compact('lok_kantor'));
    }

    public function updatelokasikantor(Request $request)
    {
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update = DB::table('konfigurasi_lokasi')->where('id', 1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data berhasil di update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal update']);
        }
    }
    public function jamkerja()
    {
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('konfigurasi.jamkerja', compact('jam_kerja'));
    }

    public function storejamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        $data = [
            'kode_jam_kerja' => $kode_jam_kerja,
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulang' => $jam_pulang
        ];
        // dd($data);
        try {
            DB::table('jam_kerja')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data gagal Disimpan']);
        }
    }

    public function editjamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $jamkerja = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        return view('konfigurasi.editjamkerja', compact('jamkerja'));
    }

    public function updatejamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        $data = [

            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulang' => $jam_pulang
        ];
        try {
            DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->update($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data gagal Diupdate']);
        }
    }
    public function deletejamkerja($kode_jam_kerja)
    {
        $hapus = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'data berhasil di hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'data gagal di hapus']);
        }
    }

    public function setjamkerja($email)
    {
        $karyawan = DB::table('karyawan')->where('email', $email)->first();
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $cekjamkerja = DB::table('konfigurasi_jamkerja')->where('email', $email)->count();
        if ($cekjamkerja > 0) {
            # code...
            $setjamkerja = DB::table('konfigurasi_jamkerja')->where('email', $email)->get();
            return view('konfigurasi.editsetjamkerja', compact('karyawan', 'jamkerja', 'setjamkerja'));
        } else {
            return view('konfigurasi.setjamkerja', compact('karyawan', 'jamkerja'));
        }
    }

    public function storesetjamkerja(Request $request)
    {
        $email = $request->email;
        $tanggal = $request->tanggal;
        $kode_jam_kerja = $request->kode_jam_kerja;

        DB::beginTransaction();
        try {
            DB::table('konfigurasi_jamkerja')->where('email', $email)->delete();

            foreach ($tanggal as $index => $tgl) {
                
                if (!empty($kode_jam_kerja[$index])) {
                    DB::table('konfigurasi_jamkerja')->insert([
                        'email' => $email,
                        'tanggal' => $tgl,
                        'kode_jam_kerja' => $kode_jam_kerja[$index],
                    ]);
                }
            
        }
            DB::commit();
            return redirect('/karyawan')->with(['success' => 'Jam Kerja Berhasil di update']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/karyawan')->with(['warning' => 'Jam Kerja gagal di update. ' . $e->getMessage()]);
        }
    }

    public function updatesetjamkerja(Request $request)
    {
        $email = $request->email;
        $tanggal = $request->tanggal;
        $kode_jam_kerja = $request->kode_jam_kerja;

        $data = [];
        for ($i = 0; $i < count($tanggal); $i++) {
            // Pastikan input tidak kosong
            if (!empty($tanggal[$i]) && !empty($kode_jam_kerja[$i])) {
                $data[] = [
                    'email' => $email,
                    'tanggal' => $tanggal[$i],
                    'kode_jam_kerja' => $kode_jam_kerja[$i]
                ];
            }
        }
        DB::beginTransaction();
        try {
            DB::table('konfigurasi_jamkerja')->where('email', $email)->delete();
            Setjamkerja::insert($data);
            DB::commit();
            return redirect('/karyawan')->with(['success' => 'Jam Kerja Berhasil di update']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/karyawan')->with(['warning' => 'Jam Kerja gagal di update']);
        }
    }
}
