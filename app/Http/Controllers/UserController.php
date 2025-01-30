<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $role = DB::table('roles')->orderBy('id')->get();
        $query = User::query();
        $user = User::find(Auth::guard('user')->user()->id);
        $kode_cabang = Auth::guard('user')->user()->kode_cabang;

        $query->select('users.id', 'users.name', 'email', 'roles.name as role', 'kode_cabang');
        $query->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id');
        $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');
        if (!empty($request->name)) {
            $query->where('users.name', 'like', '%'. $request->name . '%');
        }
        //memberi hasrole sesuai lokasi unit
        if ($user->hasRole('koor unit')) {
            # code...
            $query->where('users.kode_cabang', $kode_cabang);
        }
        $users = $query->paginate(10);
        $users->appends(request()->all());
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('users.index', compact('users', 'role', 'cabang'));
    }

    public function store(Request $request)
    {
        $nama_user = $request->nama_user;
        $email = $request->email;
        //  $role = $request->role;
        $password = bcrypt($request->password);
        $kode_cabang = $request->kode_cabang;

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $nama_user,
                'email' => $email,
                'password' => $password,
                'kode_cabang' =>$kode_cabang  
            ]);
            $user->assignRole(Role::findById($request->role));

            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil di tambahkan']);
        } catch (\Exception $e) {

            DB::rollBack();
            // dd( $e->getMessage());
            return Redirect::back()->with(['warning' => 'Data gagal di tambahkan']);
        }
    }
    public function edit(Request $request)
    {
        $id_user = $request->id_user;
        $role = DB::table('roles')->orderBy('id')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        $user = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->where('id', $id_user)->first();
            
        return view('users.edit', compact('role', 'user', 'cabang'));
    }

    public function update(Request $request, $id_user)
    {
        $nama_user = $request->nama_user;
        $email = $request->email;
        $role = $request->role;
        $password = bcrypt($request->password);
        $kode_cabang = $request->kode_cabang;

        if (isset($request->password)) {
            $data = [
                'name' => $nama_user,
                'email' => $email,
                'password' => $password,
                'kode_cabang'=> $kode_cabang
            ];
        } else {
            $data = [
                'name' => $nama_user,
                'email' => $email,
                'kode_cabang'=>$kode_cabang
            ]; 
        }
        DB::beginTransaction();
        try {
            //update data user
            DB::table('users')->where('id', $id_user)
                ->update($data);

            //update data role
            DB::table('model_has_roles')->where('model_id', $id_user)
                ->update([
                    'role_id' => $role
                ]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data berhasil di Update']);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data gagal di Update']);
        }
    }

    public function delete($id_user)
    {
        try {
            DB::table('users')->where('id', $id_user)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil di Hapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data gagal di Hapus']);
        }
    }
}
