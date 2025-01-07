<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_dept');
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->orderBy('nama_lengkap');
        if (!empty($request->nama_karyawan)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }
        $karyawan = $query->paginate(10);
        $departemen = DB::table('departemen')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('karyawan.index', compact('karyawan', 'departemen', 'cabang'));
    }

    public function store(Request $request)
    {
        $email = $request->email;
        $nama_lengkap = $request->nama_lengkap;
        $passowrd = Hash::make('Lisna2024');
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $kode_cabang = $request->kode_cabang;
        if ($request->hasFile('foto')) {
            $foto = $email . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }
        try {
            //code...
            $data = [
                'email' => $email,
                'nama_lengkap' => $nama_lengkap,
                "jabatan" => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $passowrd,
                'kode_cabang' => $kode_cabang
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/karyawan/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data berhasil disimpan']);
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan email = " . $email . " Sudah Ada";
            }
            return Redirect::back()->with(['warning' => 'Data Gagal disimpan ' . $message]);
        }
    }
    public function edit(Request $request)
    {
        $email = $request->email;
        $departemen = DB::table('departemen')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();

        $karyawan = DB::table('karyawan')->where('email', $email)->first();
        return view('karyawan.edit', compact('departemen', 'karyawan', 'cabang'));
    }

    public function update($email, Request $request)
    {
        $email = $request->email;
        $nama_lengkap = $request->nama_lengkap;
        $password = Hash::make('Lisna2024');
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $old_foto = $request->old_foto;
        $kode_cabang = $request->kode_cabang;
        if ($request->hasFile('foto')) {
            $foto = $email . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }
        try {
            //code...
            $data = [

                'nama_lengkap' => $nama_lengkap,
                "jabatan" => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password,
                'kode_cabang' => $kode_cabang
            ];
            $update = DB::table('karyawan')->where('email', $email)->update($data);
            if ($update) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/karyawan/";
                    $folderPathOld = $folderPath . $old_foto;

                    // Hapus file lama jika ada
                    if (Storage::exists($folderPathOld)) {
                        Storage::delete($folderPathOld);
                    }
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data berhasil Update']);
            }
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['warning' => 'Data Gagal Update']);
        }
    }

    public function delete($email)
    {
        $delete = DB::table('karyawan')->where('email', $email)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data berhasil di hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal di hapus']);
        }
    }

    public function resetpassword($email)
    {
        $email = Crypt::decrypt($email);
        $password = Hash::make('Lisna2024');
        $reset = DB::table('karyawan')->where('email', $email)->update([
            'password' => $password
        ]);

        if ($reset) {
            return Redirect::back()->with(['success' => 'Data Berhasil di Reset']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal di Reset']);
        }
    }

    public function lockandunlocklocation($email)
    {
        try {
            $karyawan = DB::table('karyawan')->where('email', $email)->first();
            $status_location = $karyawan->status_location;
            if ($status_location == '1') {
                DB::table('karyawan')->where('email', $email)->update([
                    'status_location' => '0'
                ]);
            } else {
                DB::table('karyawan')->where('email', $email)->update([
                    'status_location' => '1'
                ]);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil di update']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal di update']);
        }
    }
}
