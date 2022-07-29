<?php

namespace App\Http\Controllers\CycleCount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Imports\CycleCount\CycleCountImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use Session;


class SCController extends Controller
{

    public function index()
    {
        // dd('aloo');
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

            $shift_number = $shift;
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
                    ->where('status', '!=', 99)
                    ->orderBy('id', 'DESC')
                    ->get();

        return view('cycle-count.sc.index', compact('master', 'shift', 'dept'));
    }
    public function kerjakan($dept, $no_urut, $shift, $blok, $kloter)
    {
       $data =  DB::table('cycle_count')
                ->where('dept', $dept)
                ->where('no_urut', $no_urut)
                ->where('shift', $shift)
                ->where('blok', $blok)
                ->where('kloter', $kloter)
                ->whereNotIn('status', [99, 0])
                ->whereDate('tgl_upload', date('Y-m-d'))
                ->get();
                // dd($data);

        return view('cycle-count.sc.list_proses', compact('data', 'dept', 'no_urut', 'shift', 'blok'));
    }

    public function post_proses(Request $request)
    {
        $id = $request->id;
        $ok           = [];
        $not_ok       = [];
        $filter_count = [];
        $hitung       = 0;
        for($i = 0; $i < count($id); $i++)
        {
            $hasil[] = DB::table('cycle_count')->where('id', $id[$i])->get();

            $sesuai = $hasil[$i]->where('case_qty', $request->qty_aktual[$i]);
            
            if(count($sesuai) <= 0) {
                $not_ok[] = 1; 
            }else{
                $ok[] = 1;
            }

            DB::table('cycle_count')->where('id', $id[$i])->update(
                [
                    'jam_sc' => date('H:i:s'),
                    'tgl_sc' => date('Y-m-d'),
                    'qty_lapangan' => $request->qty_aktual[$i],
                    'status' => $hasil[$i][0]->case_qty == $request->qty_aktual[$i] ? 0 : 11 ,
                ]
            );
        }

        $hitung = array_sum($not_ok);

        if($hitung > 0)
        {
            $not_ok = $hitung;
        }
        else
        {
            $ok = 'ok';
        }
        DB::table('cycle_count_logg')->insert([
            'konten' => 'STOK CONTROL ' .$request->dept .' Selesai Mengerjakan Blok ' . $request->blok. ' Pada Tanggal ' . date('d-M-Y') . ' Jam ' . date('H:i'),
            'tanggal' => date('Y-m-d'),
            'jam'   => date('H:i:s'),
            'dept'  => $request->dept,
            'type'   => 'sc'
        ]);

        return response()->json([
            'data' => [
                'ok'     => $ok,
                'not_ok' => $not_ok,
            ],
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

        if($dept == 'WRH')
        {
            $data = DB::table('cycle_count')
                    ->whereRaw($where)
                    ->whereNotIn('status', [1, 99])
                    ->whereNotNull('qty_lapangan')
                    ->get();
        }
        else
        {
            $data = DB::table('cycle_count')
                    ->whereRaw($where)
                    ->where('dept', $dept)
                    ->where('shift', $shift)
                    ->whereNotIn('status', [1, 99])
                    ->whereNotNull('qty_lapangan')
                    ->get();
        }
        return Datatables::of($data)->make(true);
    }

    public function sorting_qty($dept)
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
                ->where('status', '!=', 99)
                ->get();
                return response()->json([
                    'data' => $data
                ]);
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
                    $validasi[] = DB::table('cycle_count')->select('id', 'case_qty')->where('id', $id[$i])->get();
                    // dd($validasi[$i][0]->id);
                    if($validasi[$i][0]->case_qty !=  $request->qty_validasi[$i] )
                    {
                        Session::flash('error', 'Masih Ada Selisih QTY, QTY Harus Sama Dengan Qty SAP..');
                        return back();
                    }
                    else
                    {
                        DB::table('cycle_count')->where('id', $validasi[$i][0]->id)->update([
                            'tgl_revisi' => date('Y-m-d'),
                            'jam_revisi' => date('H:i:s'),
                            'status'     => 0,
                            'reason'     => $request->reason[$i],
                            'qty_validasi'     => $request->qty_validasi[$i],
                        ]);
                    }
                }

                DB::table('cycle_count_logg')->insert([
                    'konten' => 'STOK CONTROL ' .$request->dept .' Selesai Mengerjakan Revisi Pada Blok ' . $request->blok. ' Pada Tanggal ' . date('d-M-Y') . ' Jam ' . date('H:i'),
                    'tanggal' => date('Y-m-d'),
                    'jam'   => date('H:i:s'),
                    'dept'  => $request->dept,
                    'type'   => 'sc'
                ]);

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

    public function cari_mid($material)
    {
         $data = DB::table('cycle_count_master_mid')
                    ->where('material', $material)
                    ->first();

        $batch = DB::table('cycle_count')
                    ->where('material', $material)
                    ->value('batch');

        return response()->json([
            'data' => [
                'data' => $data,
                'batch' => $batch
            ]
        ]);
    }

    public function post_adjusment(Request $request)
    {
        if($request->material == null)
        {
            Session::flash('error', 'Human Error..');
            return back();
        }
        else
        {
            $last = DB::table('cycle_count')->where('dept', $request->dept)->orderBy('id', 'DESC')->first();

            $data = DB::table('cycle_count')
                        ->insert([
                            'dept'              => $request->dept,
                            'no_urut'           => $last->no_urut,
                            'kategori_barang'   => '-',
                            'shift'             => $last->shift,
                            'group'             => $last->group,
                            'blok'              => $request->blok,
                            'kloter'            => $last->kloter,
                            'material'          => $request->material,
                            'batch'             => $request->batch,
                            'deskripsi'         => $request->deskripsi,
                            's_bin'             => $request->s_bin,
                            'case_qty'          => $request->qty_lapangan,
                            'case_uom'          => $request->case_uom,
                            'jam_upload'        => $last->jam_upload,
                            'tgl_upload'        => $last->tgl_upload,
                            'foreman'           => $last->foreman,
                            'qty_lapangan'      => $request->qty_lapangan,
                            'qty_lapangan'      => $request->qty_lapangan,
                            'status'            => 0,
                        ]);

                DB::table('cycle_count_logg')->insert([
                    'konten' => 'STOK CONTROL ' .$request->dept .' Membuat Invetory Adjusment | ' . $request->material. '-' . $request->deskripsi . '-'. $request->s_bin . '-'. $request->case_uom.' Dengan Qty : '. $request->qty_lapangan .' | Pada Tanggal ' . date('d-M-Y') . ' Jam ' . date('H:i'),
                    'tanggal' => date('Y-m-d'),
                    'jam'   => date('H:i:s'),
                    'dept'  => $request->dept,
                    'type'   => 'sc'
                ]);

            Session::flash('info', 'Data Berhasil Di Simpan..');
            return back();
        }    
    }
}
