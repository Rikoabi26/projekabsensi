<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DepartemenController extends Controller
{
    //
    public function index(Request $request)
    {
        $nama_dept = $request->nama_dept;
        $query = Departemen::query();
        $query->select('*');
        if (!empty($nama_dept)) {
            $query->where('nama_dept', 'like', '%' . $nama_dept . '%');
        }
        $departemen = $query->get();
        // $departemen = DB::table('departemen')->orderBy('kode_dept')->get();
        return view('departemen.index', compact('departemen'));
    }

    public function store(Request $request)
    {
        $nama_dept = $request->nama_dept;
        $kode_dept = $request->kode_dept;
        $data = [
            'nama_dept' => $nama_dept,
            'kode_dept' => $kode_dept,
        ];
        $cek = DB::table('departemen')->where('kode_dept', $kode_dept)->count();
        if ($cek > 0 ){
            return Redirect::back()->with(['warning'=>'Data dengan kode '."$kode_dept". ' Sudah ada' ]);
        }
        $simpan = DB::table('departemen')->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'data berhasil disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'data gagal disimpan']);
        }
    }
    public function edit(Request $request)
    {
        $kode_dept = $request->kode_dept;
        $departemen = DB::table('departemen')->where('kode_dept', $kode_dept)->first();
        return view('departemen.edit', compact('departemen'));
    }

    public function update($kode_dept, Request $request)
    {
        $nama_dept = $request->nama_dept;
        $data = [
            'nama_dept' => $nama_dept
        ];
        $update = DB::table('departemen')->where('kode_dept', $kode_dept)->update($data);
        if ($update) {
            return Redirect::back()->with(['success' => 'data berhasil di update']);
        } else {
            return Redirect::back()->with(['warning' => 'data gagal di update']);
        }
    }

    public function delete($kode_dept)
    {
        $hapus = DB::table('departemen')->where('kode_dept', $kode_dept)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'data berhasil di hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'data gagal di hapus']);
        }
    }
}
