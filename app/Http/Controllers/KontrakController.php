<?php

namespace App\Http\Controllers;

use App\Models\Nakes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KontrakController extends Controller
{
    public function index(Request $request)
    {
        $query = Nakes::query();
        $query->select('nakes.*', 'nama_cabang');
        $query->join('cabang', 'nakes.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('nama_lengkap');
        if (!empty($request->nama_karyawan)) {
            # code...
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        $nakes = $query->paginate(10);
        return view('nakes.index', compact('nakes'));
    }

    public function tambah()
    {
        $nakes = Nakes::orderBy('nama_lengkap')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('nakes.tambah', compact('nakes', 'cabang'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'SIP' => 'required',
                'nama_lengkap' => 'required',
                'jen_kel' => 'required',
                'kode_cabang' => 'required'
            ]);

            Nakes::create($validated);

            return redirect('/nakes')->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            Log::error('Error storing data: ' . $e->getMessage());
            return redirect('/nakes')->with(['warning' => 'Data gagal Disimpan']);
        }
    }

    public function edit(Request $request, $id){
      
        $nakes = DB::table('nakes')->where('id', $id)->first();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('nakes.edit', compact('nakes', 'cabang'));
    }
}
