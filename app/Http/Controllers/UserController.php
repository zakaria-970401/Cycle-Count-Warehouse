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

class UserController extends Controller
{

    public function index()
    {
        $data = DB::table('users')->where('id', Auth::user()->id)->first();
        return view('user.index', compact('data'));
    }

    public function ApprovePengerjaanOtomatis(Request $request)
    {
        $master = DB::table('cycle_count')
                ->whereIn('blok', $request->blok)
                ->where('dept', $request->dept)
                ->where('no_urut', $request->no_urut)
                ->get();
        // dd($master);
        // dd($master->take(10));
        if(count($request->all()) > 1)
        {
            for($i = 0; $i < count($master); $i++)
            {
                // dd($master, $master[$i]->id);
                $data =  DB::table('cycle_count')
                    ->where('id', $master[$i]->id)
                    ->update([
                        'status' => 0,
                        'qty_lapangan' => $master[$i]->case_qty,
                        'qty_validasi' => $master[$i]->case_qty,
                    ]);
            }

            for($i = 0; $i < count($request->blok); $i++)
            {
                    DB::table('cycle_count_logg')->insert([
                        'konten' => 'Chief WRH1 Menyetujui Permintaan Pengerjaan Otomatis Blok '.$request->blok[$i] . ' Dept '. $request->dept .' Pada Tanggal '.date('d-M-Y') . ' Jam '.date('H:i:s') .'WIB',
                        'type' => 'admin-otomatis',
                        'tanggal' => date('Y-m-d'),
                        'jam' => date('H:i:s'),
                    ]);
            }
             DB::table('cycle_count_logg')->where('type', 'admin-otomatis')
                ->where('status', 2)
                ->update(['status' => 1]);

                Session::flash('success', 'Data Berhasil Di Approve..');
                return back();
        }
        else
        {
            Session::flash('error', 'Pilih Dulu Datanya Ya..');
            return back();
        }
    }

    public function master_data(Request $request)
    {
        $data = DB::table('cycle_count_master_nama')->get();
        
        return view('cycle-count.wrh1.master_data', compact('data'));
    }

    public function cari_section($section)
    {
        $data = DB::table('cycle_count_master_nama')->where('dept', $section)->get();

        return response()->json([
            'status' => 0,
            'data'   => $data
        ]);
    }

    public function DetailHasil($blok, $dept)
    {
        $cross = false;
        if(date('l') != 'Saturday')
        {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        }
        else
        {
            $currentTime = strtotime(date('H:i:s'));
            if($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }

            $shift_number = $shift;
        }

      if($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '".$tgl_sekarang." 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '".date('Y-m-d')." 06:59:59'");
        }else{
            $where = DB::raw("tgl_upload = '".date('Y-m-d')."'");
        }

        $data = DB::table('cycle_count')
                ->whereRaw($where)
                ->where('blok', $blok)
                ->where('dept', $dept)
                ->where('shift', $shift)
                ->get();

        $sc    = DB::table('cycle_count_sc')
                ->where('dept', $data[0]->dept)
                ->where('no_urut', $data[0]->no_urut)
                ->get();


        return response()->json([
                'status' => 0,
                'data'   => [
                    'data' => $data,
                    'sc' => $sc,
                ]
            ]);
    }

    public function detail_spk($dept, $no_urut, $shift)
    {
        $data = DB::table('cycle_count')
                ->where('dept', $dept)
                ->where('no_urut', $no_urut)
                ->where('shift', $shift)
                ->get();

        $blok = $data->groupBy('blok')->toArray();

        $sc    = DB::table('cycle_count_sc')
                ->where('dept', $dept)
                ->where('no_urut', $no_urut)
                ->get();

        return response()->json([
            'status' => 0,
            'data'   => [
                'data' => $data,
                'sc' => $sc,
                'groupby' => $blok
            ]
        ]);
    }

    public function cari_data($dept, $no_urut, $shift, $blok)
    {
        $data = DB::table('cycle_count')
                ->where('dept', $dept)
                ->where('no_urut', $no_urut)
                ->where('shift', $shift)
                ->where('blok', $blok)
                ->get();

        return response()->json([
            'status' => 0,
            'data' => $data,
        ]);
    }

    public function GetAllJadwal($dept)
    {
        $data = DB::table('cycle_count')->where('dept', $dept)->groupBy('no_urut')->orderBy('id', 'DESC')->get();
        return Datatables::of($data)->make(true);
    }

    public function GetListHasil($dept)
    {
        $cross = false;
        if(date('l') != 'Saturday')
        {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        }
        else
        {
            $currentTime = strtotime(date('H:i:s'));
            if($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }

            $shift_number = $shift;
        }

      if($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '".$tgl_sekarang." 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '".date('Y-m-d')." 06:59:59'");
        }else{
            $where = DB::raw("tgl_upload = '".date('Y-m-d')."'");
        }

        $data = DB::table('cycle_count')
                ->whereRaw($where)
                ->where('dept', $dept)
                ->where('shift', $shift)
                ->where('status', '!=', 1 or 99)
                ->get();

        return Datatables::of($data)->make(true);
    }

    public function GetListRevisi($dept)
    {
        $cross = false;
        if(date('l') != 'Saturday')
        {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        }
        else
        {
            $currentTime = strtotime(date('H:i:s'));
            if($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }

            $shift_number = $shift;
        }

      if($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '".$tgl_sekarang." 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '".date('Y-m-d')." 06:59:59'");
        }else{
            $where = DB::raw("tgl_upload = '".date('Y-m-d')."'");
        }

        $data = DB::table('cycle_count')
                ->whereRaw($where)
                ->where('dept', $dept)
                ->where('shift', $shift)
                ->where('status', 11)
                ->get();

        return Datatables::of($data)->make(true);
    }

    public function post_revisi(Request $request)
    {
        $id = $request->id;
            if($id == null)
            {
                Session::flash('error', 'Data Not Found..');
                return back();
            }
            else
            {
                for($i = 0; $i < count($id); $i++)
                {
                    DB::table('cycle_count')->where('id', $id[$i])->update([
                        'tgl_revisi' => date('Y-m-d'),
                        'jam_revisi' => date('H:i:s'),
                        'status'     => 0,
                        'reason'     => $request->reason[$i],
                        'qty_validasi'     => $request->qty_validasi[$i],
                    ]);
                }
                Session::flash('info', 'Qty Berhasil Di Update..');
                return back();
            }
    }

    public function PilihKloter($dept, $no_urut, $shift, $kloter)
    {
         $data = DB::table('cycle_count')
                    ->where('shift', $shift)
                    ->where('dept', $dept)
                    ->where('no_urut', $no_urut)
                    ->where('kloter', $kloter)
                    ->groupBy('blok')
                    ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function add_user(Request $request)
    {
        DB::table('cycle_count_master_nama')->insert([
            'nama' => $request->nama,
            'dept' => $request->dept,
            'jabatan' => $request->jabatan,
        ]);

        Session::flash('info', 'Data Berhasil Di Simpan..');
        return back();
    }

    public function hapus_user($id)
    {
        DB::table('cycle_count_master_nama')->where('id', $id)->delete();
        Session::flash('info', 'Data Berhasil Di Hapus..');
        return back();
    }

    public function edit_user($id)
    {
        $data = DB::table('cycle_count_master_nama')->where('id', $id)->first();
        return response()->json([
            'data' => $data
        ]);
    }

    public function update_user(Request $request)
    {
        $data = DB::table('cycle_count_master_nama')->where('id', $request->id)->update([
            'nama' => $request->nama,
            'dept' => $request->dept,
            'jabatan' => $request->jabatan,
        ]);

        Session::flash('info', 'Data Berhasil Di Update..');
        return back();
    }

    public function cari_aktifitas($dept, $tgl_mulai, $tgl_selesai)
    {
        $data = DB::table('cycle_count_logg')->where('dept', $dept)->whereBetween('tanggal', [$tgl_mulai, $tgl_selesai])->orderBy('id', 'DESC')->get();
        $admin = $data->filter(function ($item){
            return false !== stripos($item->type, 'admin');
        });
        $sc = $data->filter(function ($item){
            return false !== stripos($item->type, 'sc');
        });

        $counting = DB::table('cycle_count')
                    ->orWhere('dept', $dept)
                    ->whereBetween('tgl_upload', [$tgl_mulai, $tgl_selesai])
                    ->where('status', '!=', 99)
                    ->orderBy('id', 'DESC')
                    ->get();
       $groupByCounting = $counting->groupBy('material')->count(); 
    //    $groupByCounting = $groupByCounting + 1;

        return response()->json([
            'data' => [
                'data' => $data,
                'admin' => $admin,
                'sc' => $sc,
                'counting' => $counting,
                'groupByCounting' => $groupByCounting,
            ]
        ]);
    }
    
    public function persediaan()
    {
        $cross = false;
        $dept = DB::table('departments')->where('id', Auth::user()->dept_id)->first();
       
        if(date('l') != 'Saturday')
        {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        }
        else
        {
            $currentTime = strtotime(date('H:i:s'));
            if($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }
        }

      if($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '".$tgl_sekarang." 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '".date('Y-m-d')." 06:59:59'");
        }else{
            $where = DB::raw("tgl_upload = '".date('Y-m-d')."'");
        }

         $master = DB::table('cycle_count')
                    ->whereRaw($where)
                    ->where('shift', $shift)
                    // ->where('dept', $dept->name)
                    ->where('status', '!=', 99)
                    // ->where('status', '!=', 1)
                    ->orderBy('id', 'DESC')
                    ->get();

        return view('cycle-count.wrh1.persediaan', compact('master', 'dept', 'shift'));
    }

    public function CariData($dept, $tgl_mulai, $tgl_selesai)
    {
        $departemen = DB::table('departments')->where('name', $dept)->first();
        $data = DB::table('cycle_count')
                ->where('dept', $dept)
                ->whereBetween('tgl_upload', [$tgl_mulai, $tgl_selesai])
                ->orderBy('id', 'DESC')
                ->whereNotIn('status', [99, 1])
                ->get();
        $sc = DB::table('cycle_count_sc')->where('status', '!=', 99)->get();

        return view('cycle-count.wrh1.cari-data', compact('data', 'departemen', 'sc'));
    }

    public function spv_index()
    {
        $dept = DB::table('departments')->select('departments.name')->join('users', 'departments.id', '=', 'users.dept_id')
                ->where('departments.id', Auth::user()->dept_id)
                ->first();

        $cross = false;
        if(date('l') != 'Saturday')
        {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        }
        else
        {
            $currentTime = strtotime(date('H:i:s'));
            if($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }

            $shift_number = $shift;
        }

      if($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '".$tgl_sekarang." 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '".date('Y-m-d')." 06:59:59'");
        }else{
            $where = DB::raw("tgl_upload = '".date('Y-m-d')."'");
        }

        $counting = DB::table('cycle_count')
                ->whereRaw($where)
                ->where('dept', $dept->name)
                ->where('shift', $shift)
                ->get();

        $logg = DB::table('cycle_count_logg')
                ->where('tanggal', date('Y-m-d'))
                ->where('dept', $dept->name)
                ->orderBy('id', 'DESC')
                ->get();

        $admin = $logg->filter(function ($item){
            return false !== stripos($item->type, 'admin');
        });
        $sc = $logg->filter(function ($item){
            return false !== stripos($item->type, 'sc');
        });

        return view('cycle-count.spv.index', compact('counting', 'logg', 'admin', 'sc', 'dept'));
    }

    public function spv_dashboard()
    {
        $dept = DB::table('departments')->select('departments.name')->join('users', 'departments.id', '=', 'users.dept_id')
            ->where('departments.id', Auth::user()->dept_id)
            ->first();

        return view('cycle-count.spv.dashboard.index', compact('dept'));
    }
    public function spv_persediaan()
    {
        $cross = false;
        $dept = DB::table('departments')->where('id', Auth::user()->dept_id)->first();
       
        if(date('l') != 'Saturday')
        {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        }
        else
        {
            $currentTime = strtotime(date('H:i:s'));
            if($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00") )
            {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00"))
            {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            }
            else if($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00"))
            {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }
        }

      if($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '".$tgl_sekarang." 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '".date('Y-m-d')." 06:59:59'");
        }else{
            $where = DB::raw("tgl_upload = '".date('Y-m-d')."'");
        }

         $master = DB::table('cycle_count')
                    ->whereRaw($where)
                    ->where('shift', $shift)
                    ->where('dept', $dept->name)
                    ->whereNotIn('status', [99])
                    ->orderBy('id', 'DESC')
                    ->get();

        return view('cycle-count.spv.persediaan', compact('master', 'dept', 'shift'));
    }

}
