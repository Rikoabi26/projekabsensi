<?php

namespace App\Http\Controllers;

use App\Models\Nakes;
use App\Models\NonNakes;
use App\Models\Sewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KontrakController extends Controller
{
    public function index(Request $request)
    {

        $sixMonthsFromNow = now()->addMonths(6);

        $query = Nakes::query();
        // $query->select('nakes.*', 'nama_cabang');
        // $query->join('cabang', 'nakes.kode_cabang', '=', 'cabang.kode_cabang');
        // $query->orderBy('nama_lengkap');

        $query->select('nakes.*', 'nama_cabang')
            ->join('cabang', 'nakes.kode_cabang', '=', 'cabang.kode_cabang')
            ->orderByRaw("CASE 
            WHEN DATE(sip_expiry_date) <= ? THEN 1
            ELSE 2 
        END, nama_lengkap ASC", [$sixMonthsFromNow]) // Prioritas data expired
            ->orderBy('nama_lengkap');

        if (!empty($request->nama_karyawan)) {
            # code...
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        $nakes = $query->paginate(15);
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
                'sip_expiry_date' => 'required',
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

    public function edit(Request $request, $id)
    {

        $nakes = DB::table('nakes')->where('id', $id)->first();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('nakes.edit', compact('nakes', 'cabang'));
    }

    public function update(Request $request, $id)
    {
        $nakes = Nakes::find($id);
        $validated = $request->validate([
            'SIP' => 'required',
            'sip_expiry_date' => 'required',
            'nama_lengkap' => 'required',
            'jen_kel' => 'required',
            'kode_cabang' => 'required',

        ]);
        $nakes->update($validated);
        return redirect('/nakes')->with('success', 'Data berhasil di ubah');
    }

    public function nonnakes(Request $request)
    {

        $oneMonthFromNow = now()->addMonth();

        $query = NonNakes::query();
        // $query->select('nonnakes.*', 'nama_cabang');
        // $query->join('cabang', 'nonnakes.kode_cabang', '=', 'cabang.kode_cabang');
        // $query->orderBy('nama_lengkap');
        $query->select('nonnakes.*', 'nama_cabang')
            ->join('cabang', 'nonnakes.kode_cabang', '=', 'cabang.kode_cabang')
            ->orderByRaw("CASE 
            WHEN DATE(habis_kontrak) <= ? THEN 1
            ELSE 2 
        END, nama_lengkap ASC", [$oneMonthFromNow]) // Kontrak hampir habis di atas
            ->orderBy('nama_lengkap');

        if (!empty($request->nama_karyawan)) {
            # code...
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        $nonnakes = $query->paginate(15);
        return view('nonnakes.index', compact('nonnakes'));
    }

    public function nonnakestambah()
    {
        $nonnakes = NonNakes::orderBy('nama_lengkap')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('nonnakes.tambah', compact('nonnakes', 'cabang'));
    }

    public function nonnakesstore(Request $request)
    {
        try {
            $validate = $request->validate([
                'nama_lengkap' => 'required',
                'awal_kontrak' => 'required',
                'habis_kontrak' => 'required',
                'jen_kel' => 'required',
                'kode_cabang' => 'required'
            ]);
            //Hitung lama kerja
            $awal = new \DateTime($request->awal_kontrak);
            $habis = new \DateTime($request->habis_kontrak);
            $interval = $awal->diff($habis);

            //buat string lama kerja dengan tahun, bulan, hari

            $lamaKerja = '';
            if ($interval->y > 0) {
                $lamaKerja .= $interval->y . ' Tahun ';
            }
            if ($interval->m > 0 || $interval->y > 0) {
                $lamaKerja .= $interval->m . ' Bulan ';
            }
            $lamaKerja .= $interval->d . ' Hari';

            $validate['lama_kerja'] = trim($lamaKerja);
            NonNakes::create($validate);

            return redirect('/nonnakes')->with(['success' => 'Data berhasil di tambahkan']);
        } catch (\Exception $e) {

            Log::error('Error storing data: ' . $e->getMessage());
            return redirect('/nonnakes')->with(['warning' => 'Data gagal Disimpan']);
        }
    }

    public function nonnakesedit(Request $request, $id)
    {
        $nonnakes = DB::table('nonnakes')->where('id', $id)->first();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('nonnakes.edit', compact('nonnakes', 'cabang'));
    }

    public function nonnakesupdate(Request $request, $id)
    {
        $nonnakes = NonNakes::find($id);
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'awal_kontrak' => 'required',
            'habis_kontrak' => 'required',
            'jen_kel' => 'required',
            'kode_cabang' => 'required',
        ]);
        // Hitung lama kerja berdasarkan awal_kontrak dan habis_kontrak
        $awal = new \DateTime($request->awal_kontrak);
        $habis = new \DateTime($request->habis_kontrak);
        $interval = $awal->diff($habis);

        // Buat string lama kerja dalam format Tahun, Bulan, Hari
        $lamaKerja = '';
        if ($interval->y > 0) {
            $lamaKerja .= $interval->y . ' Tahun ';
        }
        if ($interval->m > 0 || $interval->y > 0) { // Tampilkan bulan jika ada tahun atau bulan
            $lamaKerja .= $interval->m . ' Bulan ';
        }
        $lamaKerja .= $interval->d . ' Hari';

        // Tambahkan lama_kerja ke data yang akan diupdate
        $validated['lama_kerja'] = trim($lamaKerja);

        // Update data NonNakes
        $nonnakes->update($validated);

        // Redirect dengan pesan sukses
        return redirect('/nonnakes')->with('success', 'Data berhasil diupdate');
    }

    public function createsewa(Request $request)
    {
        $oneMonthFromNow = now()->addMonth();

        $query = Sewa::query();
        $query->select('sewa.*', 'nama_cabang')
            ->join('cabang', 'sewa.kode_cabang', '=', 'cabang.kode_cabang')
            ->orderByRaw("CASE 
            WHEN DATE(akir_sewa) <= ? THEN 1
            ELSE 2 
        END, jen_sewa ASC", [$oneMonthFromNow]) // Kontrak hampir habis di atas
            ->orderBy('jen_sewa');

        if (!empty($request->jen_sewa)) {
            # code...
            $query->where('jen_sewa', 'like', '%' . $request->jen_sewa . '%');
        }
        $sewa = $query->paginate(15);
        return view('sewa.index', compact('sewa'));
    }

    public function sewatambah()
    {
        $sewa = Sewa::orderBy('jen_sewa')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('sewa.tambah', compact('sewa',  'cabang'));
    }

    public function sewastore(Request $request)
    {
        try {
            $validated = $request->validate([
                'jen_sewa' => 'required',
                'awal_sewa' => 'required',
                'akir_sewa' => 'required',
                'kode_cabang' => 'required'
            ]);

            Sewa::create($validated);

            return redirect('/sewa')->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            Log::error('Error storing data: ' . $e->getMessage());
            return redirect('/sewa')->with(['warning' => 'Data gagal Disimpan']);
        }
    }

    public function sewaedit(Request $request, $id)
    {
        $sewa = DB::table('sewa')->where('id', $id)->first();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('sewa.edit', compact('sewa', 'cabang'));
    }

    public function sewaupdate(Request $request, $id)
    {
        $sewa = Sewa::find($id);
        $validated = $request->validate([
            'jen_sewa' => 'required',
            'awal_sewa' => 'required',
            'akir_sewa' => 'required',
            'kode_cabang' => 'required',
        ]);

        $sewa->update($validated);
        return redirect('/sewa')->with('success', 'Data berhasil di ubah');
    }

    public function deletesewa($id)
    {
        $cekdatasewa = DB::table('sewa')->where('id', $id)->first();
        try {
            DB::table('sewa')->where('id', $id)->delete();
            return redirect('/sewa')->with(['success' => 'Data berhasil di hapus']);
        } catch (\Exception $e) {
            return redirect('/sewa')->with(['warning' => 'Data gagal di hapus']);
        }
    }
}
