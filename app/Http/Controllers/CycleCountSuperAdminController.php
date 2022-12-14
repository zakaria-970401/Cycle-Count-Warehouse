<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Imports\CycleCount\CycleCountImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use Session;

class CycleCountSuperAdminController extends Controller
{

    public function masterUser()
    {
        $data = DB::table('users')->get();
        return view('super_admin.user', compact('data'));
    }

    public function postUser(Request $request)
    {
        $validasi = DB::table('users')->where('username', $request->username)->first();
        if ($validasi) {
            Session::flash('error', 'Username ' . $request->username . ' Sudah dimiliki oleh ' . $validasi->name);
            return back();
        } else {
            DB::table('users')->insert([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'auth_group' => $request->auth_group,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->name,
            ]);
        }
        Session::flash('success', 'Data berhasil ditambahkan');
        return back();
    }

    public function showUser($id)
    {
        $data = DB::table('users')
            ->join('auth_group', 'users.auth_group', '=', 'auth_group.id')
            ->select('users.*', 'auth_group.name as group_name')
            ->where('users.id', $id)
            ->first();
        return response()->json([
            'data' => $data
        ]);
    }

    public function updateUser(Request $request)
    {
        DB::table('users')->where('id', $request->id_user)->update([
            'name' => $request->name,
            'username' => $request->username,
            'auth_group' => $request->auth_group,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->name,
        ]);
        Session::flash('success', 'User berhasil diubah');
        return back();
    }

    public function resetPassword($id)
    {
        DB::table('users')->where('id', $id)->update([
            'password' => bcrypt('123456'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->name,
        ]);
        Session::flash('success', 'Password berhasil diubah');
        return back();
    }

    public function aksesMenu()
    {
        $permission = DB::table('auth_permission')->get();
        $auth_group = DB::table('auth_group')->get();
        return view('super_admin.menu', compact('permission', 'auth_group'));
    }

    public function deleteUser($id)
    {
        DB::table('users')->where('id', $id)->delete();
        Session::flash('success', 'User berhasil dihapus');
        return back();
    }

    public function showMenu($id)
    {
        $auth_group = DB::table('auth_group')->where('id', $id)->first();
        $permission = DB::table('auth_permission')->get()->pluck('id')->toArray();

        $list_exist = DB::table('auth_permission')
            ->join('auth_group_permission', 'auth_group_permission.permission_id', '=', 'auth_permission.id')
            ->where('group_id', $auth_group->id)
            ->get();

        $list = DB::table('auth_permission')
            ->join('auth_group_permission', 'auth_group_permission.permission_id', '=', 'auth_permission.id')
            ->where('group_id', $auth_group->id)
            ->get()->pluck('permission_id')->toArray();;

        $list = array_diff($permission, $list);
        $list_kosong = [];
        foreach ($list as $_val) {
            $list_kosong[] = DB::table('auth_permission')->where('id', $_val)->get();
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'auth_group' => $auth_group,
                'permission' => $permission,
                'list_exist' => $list_exist,
                'list_kosong' => $list_kosong,
            ],
        ]);
    }

    public function change_password(Request $request){
        $validate = $request->password != $request->password_konfirm;
        if($validate){
           return response()->json([
               'status' => 'gagal',
           ]);
        }else if(strlen($request->password) < 6){
            return response()->json([
                'status' => 'kurang',
            ]);
        }else{
            DB::table('users')->where('id', Auth::user()->id)->update([
                'password' => bcrypt($request->password),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->name,
            ]);
            Auth::logout();
            return response()->json([
                'status' => 'ok',
            ]);
            // return back();
        }
    }
}
