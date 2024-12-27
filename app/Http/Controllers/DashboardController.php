<?php

namespace App\Http\Controllers;

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
            ->orderBy('jam_in',)
            ->get();
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agust", "September", "Oktober", "November", "Desember"];
        return view('dashboard.dashboard', compact('presensihariini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard'));
    }

    public function dashboardadmin()
    {
        $bulanini = date('m');
        $tahunini = date('Y');
        $hariini = date('Y-m-d');

        $rekappresensi = DB::table('presensi')
            ->selectRaw('
            SUM(IF(status="h", 1,0))as jmlhadir,
            SUM(IF(status="i", 1,0))as jmlizin,
            SUM(IF(status="s", 1,0))as jmlsakit,
            SUM(IF(status="c", 1,0))as jmlcuti,
            SUM(IF(jam_in > jam_masuk,1,0)) as jmlterlambat
            ')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tgl_presensi', $hariini)
            ->first();


        return view('dashboard.dashboardadmin', compact('rekappresensi'));
       
    }
}
