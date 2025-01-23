<?php

namespace App\Http\Controllers;

use App\Models\Nakes;
use App\Models\NonNakes;
use App\Models\Sewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $hariini = date('Y-m-d');
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $email = Auth::guard('karyawan')->user()->email;
        $presensihariini = DB::table('presensi')->where('email', $email)->where('tgl_presensi', $hariini)->first();
        $historibulanini = DB::table('presensi')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('email', $email)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->orderBy('tgl_presensi', 'desc')
            ->get();

        $rekappresensi = DB::table('presensi')
            ->selectRaw('
            SUM(IF(status="h", 1,0))as jmlhadir,
            SUM(IF(status="i", 1,0))as jmlizin,
            SUM(IF(status="s", 1,0))as jmlsakit,
            SUM(IF(status="c", 1,0))as jmlcuti,
            SUM(IF(jam_in > jam_masuk,1,0)) as jmlterlambat
            ')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('email', $email)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('karyawan', 'presensi.email', 'karyawan.email')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agust", "September", "Oktober", "November", "Desember"];
        return view('dashboard.dashboard', compact('presensihariini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard'));
    }

    public function dashboardadmin()
    {

        $bulanini = date('m');
        $tahunini = date('Y');
        $hariini = date('Y-m-d');

        $oneMonthFromNow = now()->addMonth();
        $sixMonthsFromNow = now()->addMonths(6);
        // Data Nakes
    $expiringNakes = Nakes::whereDate('sip_expiry_date', '<=', $sixMonthsFromNow)->count();
    $validNakes = Nakes::whereDate('sip_expiry_date', '>', $sixMonthsFromNow)->count();

    // Data NonNakes
    $expiringNonNakes = NonNakes::whereDate('habis_kontrak', '<=', $oneMonthFromNow)->count();
    $validNonNakes = NonNakes::whereDate('habis_kontrak', '>', value: $oneMonthFromNow)->count();

    //data sewa
    $expiringSewa = Sewa::whereDate('akir_sewa', '<=', value: $oneMonthFromNow)->count();
    $validSewa = Sewa::whereDate('akir_sewa', '>', value: $oneMonthFromNow)->count();

        $rekappresensi = DB::table(function ($query) use ($hariini) {
            $query->select(
                'presensi.status',
                'presensi.jam_in',
                'jam_kerja.jam_masuk'
            )
                ->from('presensi')
                ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('tgl_presensi', $hariini)

                ->union(
                    // Menambahkan data izin/sakit/cuti yang diapprove
                    DB::table('pengajuan_izin')
                        ->select(
                            'status',
                            DB::raw('NULL as jam_in'),
                            DB::raw('NULL as jam_masuk')
                        )
                        ->where('status_approved', 1)
                        ->where('tgl_izin_dari', '<=', $hariini)
                        ->where('tgl_izin_sampai', '>=', $hariini)
                );
        }, 'combined_data')
            ->selectRaw('
        SUM(IF(status="h", 1, 0)) as jmlhadir,
        SUM(IF(status="i", 1, 0)) as jmlizin,
        SUM(IF(status="s", 1, 0)) as jmlsakit,
        SUM(IF(status="c", 1, 0)) as jmlcuti,
        SUM(IF(jam_in > jam_masuk AND status="h", 1, 0)) as jmlterlambat
    ')
            ->first();


        return view('dashboard.dashboardadmin', compact('rekappresensi', 'expiringNakes', 'validNakes', 'expiringNonNakes', 'validNonNakes', 'expiringSewa', 'validSewa'));
    }
}
