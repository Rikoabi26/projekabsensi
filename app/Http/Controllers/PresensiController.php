<?php

namespace App\Http\Controllers;

use App\Models\IzinWorkflow;
use App\Models\Karyawan;
use App\Models\Pengajuanizin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use function Laravel\Prompts\select;


class PresensiController extends Controller
{

    public function gethari()
    {
        $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak Diketahui";
                break;
        }
        return $hari_ini;
    }
    //
    public function create()
    {
        $hariini = date("Y-m-d");
        $namahari = $this->gethari();
        $email = Auth::guard('karyawan')->user()->email;
        $cek = DB::table('presensi')->where('tgl_presensi')->where('email', $email)->count();
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
        //manggildatadi database
        $jamkerja = DB::table('konfigurasi_jamkerja')
            ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('email', $email)->where('tanggal', date('Y-m-d'))->first();

        if ($jamkerja == null) {
            return view('presensi.notifjadwal');
        } else {
            return view('presensi.create', compact('cek', 'lok_kantor', 'jamkerja'));
        }
    }

    public function store(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;
        $status_location = Auth::guard('karyawan')->user()->status_location;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
        $lok = explode(",", $lok_kantor->lokasi_cabang);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        // dd($lokasi);
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];
        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);
        $namahari = $this->gethari();

        //ambiljamkerjakaryawan
        $jamkerja = DB::table('konfigurasi_jamkerja')
            ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('email', $email)->where('tanggal', date('Y-m-d'))->first();



        $presensi = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('email', $email);
        $cek = $presensi->count();
        $datapresensi = $presensi->first();
        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }

        $image = $request->image;
        // $folderPath = "public/uploads/absensi/";
        $folderPath = base_path('public/assets/new-uploads/absensi/');
        $formatName = $email . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName  = $formatName . ".png";
        $file = $folderPath . $fileName;

        if ($status_location == 1 && $radius > $lok_kantor->radius_cabang) {
            echo "error|Maaf Anda Berada diluar Radius, Jarak anda " . $radius . " meter dari kantor|radius";
        } else {
            if ($cek > 0) {

                if ($jam < $jamkerja->jam_pulang) {
                    echo "error| Belum waktunya pulang|out";
                } else if (!empty($datapresensi->jam_out)) {
                    echo "error|Anda sudah absen pulang|out";
                } else {
                    $data_pulang = [
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('email', $email)->update($data_pulang);
                    if ($update) {
                        echo "success|Terima Kasih, Hati-Hati Di Jalan|out";
                        // Storage::put($file, $image_base64);
                        file_put_contents($file, $image_base64);
                    } else {
                        echo "error|Maaf Gagal Absen|out";
                    }
                }
            } else {
                if ($jam < $jamkerja->awal_jam_masuk) {
                    echo "error| Maaf Belum Waktunya melakukan absensi|in";
                } else if ($jam > $jamkerja->akhir_jam_masuk) {
                    echo "error| Batas absensi sudah lewat, hanya 2 jam dari jam masuk|in";
                } else {
                    $data = [
                        'email' => $email,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_in' => $jam,
                        'foto_in' => $fileName,
                        'lokasi_in' => $lokasi,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                        'status' => 'h '
                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        echo "success|Terima Kasih, Selamat Bekerja|in";
                        file_put_contents($file, $image_base64);

                    } else {
                        echo "error|Maaf Gagal Absen|in";
                    }
                    
                }
            }
        }
    }


    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $email = Auth::guard('karyawan')->user()->email;
        $karyawan = DB::table('karyawan')->where('email', $email)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = $request->password;
        $karyawan = DB::table('karyawan')->where('email', $email)->first();
        $request->validate([
            'foto' => 'image|mimes:png,jpg,jpeg'
        ]);
        if ($request->hasFile('foto')) {
            $foto = $email . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        if (empty($password)) {
            # code...
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto,
            ];
        } else {
            # code...
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto

            ];

            if (!empty($password)) {
                $data['password'] = Hash::make($password);
            }
        }
        $update = DB::table('karyawan')->where('email', $email)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = public_path('assets/new-uploads/karyawan/');
                $request->file('foto')->move($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data berhasil di update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal update']);
        }
    }
    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $email = Auth::guard('karyawan')->user()->email;
        $histori = DB::table('presensi')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('email', $email)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    public function izin(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;

        if (!empty($request->bulan) && !empty($request->tahun)) {
            $dataizin = Pengajuanizin::leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
                ->orderBy('tgl_izin_dari', 'desc')
                ->where('email', $email)
                ->whereRaw('MONTH(tgl_izin_dari)="' . $request->bulan . '"')
                ->whereRaw('YEAR(tgl_izin_dari)="' . $request->tahun . '"')
                ->get();
        } else {
            $dataizin = Pengajuanizin::leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
                ->orderBy('tgl_izin_dari', 'desc')
                ->where('email', $email)->limit(5)->orderBy('tgl_izin_dari', 'desc')
                ->get();
        }

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agust", "September", "Oktober", "November", "Desember"];

        return view('presensi/izin', compact('dataizin', 'namabulan'));
    }

    public function buatizin()
    {
        return view('presensi/buatizin');
    }

    public function storeizin(Request $request)
    {
        $email = Auth::guard('karyawan')->user()->email;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'email' => $email,
            'tgl_izin' => $tgl_izin,
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

    public function monitoring()
    {
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('presensi.monitoring', compact('cabang'));
    }

    public function getpresensi(Request $request)
    {
        $kode_dept = Auth::guard('user')->user()->kode_dept;
        $kode_cabang = Auth::guard('user')->user()->kode_cabang;
        $user = User::find(Auth::guard('user')->user()->id);
        $tanggal = $request->tanggal;

        $query = Karyawan::query();
        $query->selectRaw('karyawan.email, nama_lengkap, karyawan.kode_dept, karyawan.kode_cabang,jam_in,datapresensi.id,jam_out,foto_in,foto_out,lokasi_in,lokasi_out,
    COALESCE(datapresensi.status, pengajuan_izin.status) as status, 
    nama_jam_kerja, jam_masuk, jam_pulang,
    COALESCE(datapresensi.keterangan, pengajuan_izin.keterangan) as keterangan'); // Menggunakan COALESCE untuk mengambil status dari pengajuan_izin jika status presensi null
        $query->leftjoin(
            DB::raw("(
        SELECT 
        presensi.email,presensi.id,jam_in,jam_out,foto_in,foto_out,lokasi_in,lokasi_out,
        presensi.status,nama_jam_kerja, jam_masuk, jam_pulang,
        presensi.kode_izin, keterangan
        FROM presensi
        LEFT JOIN jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
        LEFT JOIN pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
        WHERE tgl_presensi = '$tanggal'
    )datapresensi"),
            function ($join) {
                $join->on('karyawan.email', '=', 'datapresensi.email');
            }
        );

        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }
        // $query->orderBy('nama_lengkap');
        $query->leftJoin('pengajuan_izin', function ($join) use ($tanggal) {
            $join->on('karyawan.email', '=', 'pengajuan_izin.email')
                ->whereRaw("'$tanggal' BETWEEN tgl_izin_dari AND tgl_izin_sampai")
                ->where('status_approved', 1);
        });
        $presensi = $query->get();

        return view('presensi.getpresensi', compact('presensi', 'tanggal'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('karyawan', 'presensi.email', '=', 'karyawan.email')
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }
    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }


    public function cetaklaporan(Request $request)
    {
        $email = $request->email;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->where('email', $email)->first();
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'keterangan', 'jam_kerja.*')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftjoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->where('presensi.email', $email)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();
        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            //mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Presensi Perorangan $time.xls");
            return view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
        }
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Validasi input bulan dan tahun
        if (!$bulan || !$tahun || !checkdate($bulan, 1, $tahun)) {
            return back()->withErrors(['message' => 'Input bulan atau tahun tidak valid']);
        }

        // Pastikan bulan memiliki dua digit (01, 02, ..., 12)
        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        $namabulan = array(
            '01' => "Januari",
            '02' => "Februari",
            '03' => "Maret",
            '04' => "April",
            '05' => "Mei",
            '06' => "Juni",
            '07' => "Juli",
            '08' => "Agustus",
            '09' => "September",
            '10' => "Oktober",
            '11' => "November",
            '12' => "Desember"
        );

        if (!array_key_exists($bulan, $namabulan)) {
            return back()->withErrors(['message' => 'Bulan tidak valid']);
        }

        // $dari = $tahun . "-" . $bulan . "-01"; // Pastikan bulan memiliki 2 digit
        // $sampai = date("Y-m-t", strtotime($dari)); // Mendapatkan akhir bulan

        //tanggal 24-23
        if ($bulan == 1) {
            $bulanlalu = 12;
            $tahunlalu = $tahun - 1;
        } else {
            $bulanlalu = $bulan - 1;
            $tahunlalu = $tahun;
        }
        $dari = $tahunlalu . "-" . $bulanlalu . "-24";
        $sampai = $tahun . "-" . $bulan . "-23";

        $rangetanggal = [];

        // Membuat array tanggal dalam bulan yang diminta
        while (strtotime($dari) <= strtotime($sampai)) {
            $rangetanggal[] = $dari;
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }
        //perbaikan bugs 35
        // Menambahkan NULL untuk tanggal sisa jika kurang dari 31 hari
        $jmlhari = count($rangetanggal);
        $lastrange = $jmlhari - 1;
        $sampai = $rangetanggal[$lastrange];
        for ($i = $jmlhari; $i < 31; $i++) {
            $rangetanggal[] = null;
        }
        $columns = [];

        // Menyiapkan kolom berdasarkan tanggal
        foreach ($rangetanggal as $index => $tanggal) {
            if ($tanggal) {
                $columns[] = "MAX(CASE WHEN tgl_presensi = '$tanggal' THEN 
                CONCAT(
                    IFNULL(jam_in, 'NA'), '|',
                    IFNULL(jam_out, 'NA'), '|',
                    IFNULL(presensi.status, 'NA'), '|',
                    IFNULL(nama_jam_kerja, 'NA'), '|',
                    IFNULL(jam_masuk, 'NA'), '|',
                    IFNULL(jam_pulang, 'NA'), '|',
                    IFNULL(presensi.kode_izin, 'NA'), '|',
                    IFNULL(keterangan, 'NA')
                ) ELSE NULL END) as tgl_" . ($index + 1);
            } else {
                $columns[] = "NULL as tgl_" . ($index + 1);
            }
        }

        // Query utama
        $query = Karyawan::query();
        $query->selectRaw("
            karyawan.email, 
            nama_lengkap, 
            jabatan, 
            " . implode(', ', $columns) . "
        ")
            ->leftJoin('presensi', 'karyawan.email', '=', 'presensi.email')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->groupBy('karyawan.email', 'nama_lengkap', 'jabatan');

        $query->orderBy('nama_lengkap');
        $rekap = $query->get();



        // Handle export ke Excel
        if ($request->has('exportexcel')) {
            $time = date("d-M-Y H:i:s");
            // Fungsi header untuk eksport Excel
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=Rekap_Presensi_Karyawan_$time.xls");
        }

        return view('presensi.cetakrekap', compact('rekap', 'rangetanggal', 'namabulan', 'bulan', 'tahun', 'jmlhari'));
    }

    public function izinsakit(Request $request)
    {

        $kode_cabang = Auth::guard('user')->user()->kode_cabang;
        $user = User::find(Auth::guard('user')->user()->id);

        $query = Pengajuanizin::query();
        $query->select('kode_izin', 'tgl_izin_dari', 'tgl_izin_sampai', 'pengajuan_izin.email', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan', 'doc_sid');
        $query->join('karyawan', 'pengajuan_izin.email', '=', 'karyawan.email');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin_dari', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }
        if ($request->status_approved === "0" || $request->status_approved === "1" || $request->status_approved === "2") {
            $query->where('status_approved', $request->status_approved);
        }


        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        //memberi hasrole sesuai lokasi unit
        if ($user->hasRole('koor unit')) {
            # code...
            $query->where('karyawan.kode_cabang', $kode_cabang);
        }
        $query->orderBy('tgl_izin_dari', 'desc');
        $izinsakit = $query->paginate(10);
        $izinsakit->appends($request->all());
      
       

        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('presensi.izinsakit', compact('izinsakit', 'cabang'));
    }

    public function formApprovalIzin($izin_workflow_id, $kode_izin)
    {
        $izin_workflow = IzinWorkflow::find($izin_workflow_id);
        $izin = Pengajuanizin::where('kode_izin', $kode_izin)->first();
        $status_flows = explode(',', $izin_workflow->workflow->status);

        return view('presensi.form-approval-izin', compact(
            'izin_workflow',
            'izin',
            'status_flows',
        ));
    }

    public function formApprovalIzinStore(Request $request, $izin_workflow_id, $kode_izin)
    {
        $izin_workflow = IzinWorkflow::find($izin_workflow_id);
        $izin = Pengajuanizin::where('kode_izin', $kode_izin)->first();

        $validated = $request->validate([
            'status' => 'required',
            'notes' => 'nullable',
        ]);

        $validated['user_id'] = Auth::guard('user')->user()->id;

        if ($request->status == 'Approve') {
            $validated['active'] = 0;
            $next_ordinal = $izin_workflow->ordinal + 1;
            $next_workflow = IzinWorkflow::where('kode_izin', $kode_izin)->where('ordinal', $next_ordinal)->first();
            if ($next_workflow) {
                $next_workflow->update([
                    'active' => 1
                ]);

                DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                    'status_approved' => 3
                ]);
            } else {
                DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                    'status_approved' => 1
                ]);
            }
        } else {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approved' => 2
            ]);
        }

        $izin_workflow->update($validated);

        return redirect('/presensi/izinsakit')->with('success', $request->status . ' Successfully.');
    }


    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $kode_izin = $request->kode_izin_form;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $email = $dataizin->email;
        $tgl_dari = $dataizin->tgl_izin_dari;
        $tgl_sampai = $dataizin->tgl_izin_sampai;
        $status = $dataizin->status;

        //memproses status
        DB::beginTransaction();
        try {
            if ($status_approved == 1) {
                while (strtotime($tgl_dari) <= strtotime($tgl_sampai)) {
                    DB::table('presensi')->insert([
                        'email' => $email,
                        'tgl_presensi' => $tgl_dari,
                        'status' => $status,
                        'kode_izin' => $kode_izin
                    ]);
                    $tgl_dari = date("Y-m-d", strtotime("+1 days", strtotime($tgl_dari)));
                }
            }

            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approved' => $status_approved
            ]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data berhasil Diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Diproses']);
        }
    }

    public function batalkanizinsakit($kode_izin)
    {
        DB::beginTransaction();
        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approved' => 0
            ]);
            DB::table('presensi')->where('kode_izin', $kode_izin)->delete();
            DB::commit();
            return Redirect::back()->with(['success' => 'Data berhasil dibatalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Di batalkan']);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $email = Auth::guard('karyawan')->user()->email;

        $cek = DB::table('pengajuan_izin')->where('email', $email)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }

    public function showact($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('presensi.showact', compact('dataizin'));
    }

    public function deleteizin($kode_izin)
    {
        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $doc_sid = $cekdataizin->doc_sid;
        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();
            if ($doc_sid != null) {
                Storage::delete('/public/uploads/sid/' . $doc_sid);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil di hapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(key: ['warning' => 'Data Gagal di hapus']);
        }
    }

    public function koreksipresensi(Request $request)
    {
        $email = $request->email;
        $karyawan = DB::table('karyawan')->where('email', $email)->first();
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')->where('email', $email)->where('tgl_presensi', $tanggal)->first();
        $jamkerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('presensi.koreksipresensi', compact('karyawan', 'tanggal', 'jamkerja', 'presensi'));
    }

    public function storekoreksipresensi(Request $request)
    {
        $status = $request->status;
        $email = $request->email;
        $tanggal = $request->tanggal;
        //mengosongkan nilai jika berstatus alfa
        $jam_in = $status == "a" ? NULL : $request->jam_in;
        $jam_out = $status == "a" ? NULL : $request->jam_out;
        $kode_jam_kerja = $status == "a" ? NULL : $request->kode_jam_kerja;


        try {
            $cekpresensi = DB::table('presensi')->where('email', $email)->where('tgl_presensi', $tanggal)->count();
            if ($cekpresensi > 0) {
                DB::table('presensi')
                    ->where('email', $email)
                    ->where('tgl_presensi', $tanggal)
                    ->update([
                        'email' => $email,
                        'tgl_presensi' => $tanggal,
                        'jam_in' => $jam_in,
                        'jam_out' => $jam_out,
                        'kode_jam_kerja' => $kode_jam_kerja,
                        'status' => $status,

                    ]);
            } else {
                DB::table('presensi')->insert([
                    'email' => $email,
                    'tgl_presensi' => $tanggal,
                    'jam_in' => $jam_in,
                    'jam_out' => $jam_out,
                    'kode_jam_kerja' => $kode_jam_kerja,
                    'status' => $status,

                ]);
            }

            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data gagal Disimpan']);
        }
    }
}
