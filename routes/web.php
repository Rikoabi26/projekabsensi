<?php

use App\Http\Controllers\KontrakController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\UserController;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KonfigurasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});
Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
    Route::post('/loginadmin', [AuthController::class, 'loginadmin']);
});

Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);


    ///presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    //Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/{email}/updateprofile', [PresensiController::class, 'updateprofile']);
    //histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class, 'gethistori']);

    //izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);

    //izinabsen
    Route::get('/izinabsen', action: [IzinabsenController::class, 'create']);
    Route::post('/izinabsen/store', [IzinabsenController::class, 'store']);
    Route::get('/izinabsen/{kode_izin}/edit', [IzinabsenController::class, 'edit']);
    Route::post('/izinabsen/{kode_izin}/update', [IzinabsenController::class, 'update']);

    //izin Sakit
    Route::get('/izinsakit', [IzinsakitController::class, 'create']);
    Route::post('/izinsakit/store', [IzinsakitController::class, 'store']);
    Route::get('/izinsakit/{kode_izin}/edit', [IzinsakitController::class, 'edit']);
    Route::post('/izinsakit/{kode_izin}/update', [IzinsakitController::class, 'update']);

    //izincuti
    Route::get('izincuti', [IzincutiController::class, 'create']);
    Route::post('/izincuti/store', [IzincutiController::class, 'store']);
    Route::get('/izincuti/{kode_izin}/edit', [IzincutiController::class, 'edit']);
    Route::post('/izincuti/{kode_izin}/update', [IzincutiController::class, 'update']);
    Route::post('/izincuti/getmaxcuti', [IzincutiController::class, 'getmaxcuti']);


    Route::get('/izin/{kode_izin}/showact', [PresensiController::class, 'showact']);
    Route::get('/izin/{kode_izin}/delete', [PresensiController::class, 'deleteizin']);
});

//Route yang bisa di akses oleh admin dan koor unit
Route::group(['middleware' => ['role:administrator|koor unit,user']], function () {
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{kode_izin}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);

    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{email}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{email}/delete', [KaryawanController::class, 'delete']);
    Route::get('/karyawan/{email}/resetpassword', [KaryawanController::class, 'resetpassword']);
    Route::get('/konfigurasi/{nik}/setjamkerja', [KonfigurasiController::class, 'setjamkerja']);
    Route::post('/konfigurasi/storesetjamkerja', [KonfigurasiController::class, 'storesetjamkerja']);
    Route::post('/konfigurasi/updatesetjamkerja', [KonfigurasiController::class, 'updatesetjamkerja']);
    Route::post('/konfigurasi/getjadwal', [KonfigurasiController::class, 'getjadwal']);

    

    Route::get('/presensi/form-approval-izin/{izin_workflow_id}/{kode_izin}', [PresensiController::class, 'formApprovalIzin']);
    Route::post('/presensi/form-approval-izin/store/{izin_workflow_id}/{kode_izin}', [PresensiController::class, 'formApprovalIzinStore']);

    //user
   
});




//Route yang hanya bisa di akses oleh admin 
Route::group(['middleware' => ['role:administrator,user']], function () {
    //Karyawan
    Route::get('/karyawan/{email}/lockandunlocklocation', [KaryawanController::class, 'lockandunlocklocation']);

    //departemen
    Route::get('/departemen', [DepartemenController::class, 'index'])->middleware('permission:view-departemen,user');
    Route::post('/departemen/store', [DepartemenController::class, 'store']);
    Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);

    //presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::post('/koreksipresensi', [PresensiController::class, 'koreksipresensi']);
    Route::post('/storekoreksipresensi', [PresensiController::class, 'storekoreksipresensi']);


    //cabang
    Route::get('/cabang', [CabangController::class, 'index']);
    Route::post('/cabang/store', [CabangController::class, 'store']);
    Route::post('/cabang/edit', [CabangController::class, 'edit']);
    Route::post('/cabang/update', [CabangController::class, 'update']);
    Route::post('/cabang/{kode_cabang}/delete', [CabangController::class, 'delete']);


    //konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::class, 'jamkerja']);
    Route::post('/konfigurasi/storejamkerja', [KonfigurasiController::class, 'storejamkerja']);
    Route::post('/konfigurasi/editjamkerja', [KonfigurasiController::class, 'editjamkerja']);
    Route::post('/konfigurasi/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);
    Route::post('/konfigurasi/{kode_jam_kerja}/delete', [KonfigurasiController::class, 'deletejamkerja']);
  
     //users
     Route::get('/konfigurasi/users', [UserController::class, 'index']);
     Route::post('/konfigurasi/users/store', [UserController::class, 'store']);
     Route::post('/konfigurasi/users/edit', [UserController::class, 'edit']);
     Route::post('/konfigurasi/users/{id_user}/update', [UserController::class, 'update']);
     Route::post('/konfigurasi/users/{id_user}/delete', [UserController::class, 'delete']);
    
    Route::get('/workflow', [WorkflowController::class, 'index']);
    Route::get('/workflow/tambah', [WorkflowController::class, 'tambah']);
    Route::post('/workflow/store', [WorkflowController::class, 'store']);
    Route::get('/workflow/edit/{id}', [WorkflowController::class, 'edit']);
    Route::put('/workflow/update/{id}', [WorkflowController::class, 'update']);
    Route::delete('/workflow/delete/{id}', [WorkflowController::class, 'delete']);

    //cuti
    Route::get('/cuti', [CutiController::class, 'index']);
    Route::post('/cuti/store', [CutiController::class, 'store']);
    Route::post('/cuti/edit', [CutiController::class, 'edit']);
    Route::post('/cuti/{kode_cuti}/update', [CutiController::class, 'update']);
    Route::post('/cuti/{kode_cuti}/delete', [CutiController::class, 'delete']);

    //Kontrak
    Route::get(uri: '/nakes', action: [KontrakController::class,'index']);
    Route::get('/nakes/tambah', [KontrakController::class, 'tambah']);
    Route::post('/nakes/store', [KontrakController::class, 'store']);
    Route::get('/nakes/edit/{id}', [KontrakController::class, 'edit']);
});


//spatie role permission
Route::get('/createrolepermission', function () {

    try {
        //code...
        Role::create(['name' => 'koor unit']);
        // Permission::create(['name' => 'view-karyawan']);
        // Permission::create(['name' => 'view-departemen']);
        echo "sukses";
    } catch (\Exception $th) {
        //throw $e;
        echo "Error" . $th->getMessage();
    }
});

Route::get('/give-user-role', function () {
    try {
        $user = User::findOrFail(1);
        $user->assignRole('administrator');
        echo "Sukses";
    } catch (\Exception $th) {
        //throw $th;
        echo "Error";
    }
});

Route::get('/give-user-permission', function () {
    try {

        // angka 1 merupakan id administrator
        $role = Role::findOrFail(1);
        $role->givePermissionTo('view-departemen');
        echo "Sukses";
    } catch (\Exception $th) {
        //throw $th;
        echo "Error";
    }
});
