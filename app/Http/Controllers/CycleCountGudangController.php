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
use Str;


class CycleCountGudangController extends Controller
{

    public function hitung()
    {
        return view('gudang.hitung');
    }
    public function getListBlok()
    {
        $data = DB::table('cycle_count')
            ->where('status', 1)
            ->whereDate('upload_at', date('Y-m-d'))
            ->groupBy('blok')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function formHitung($blok, $tgl_upload)
    {
        $tgl_upload = explode(' ', $tgl_upload)[0];
        DB::table('cycle_count')
            ->where('blok', $blok)
            ->whereDate('upload_at', $tgl_upload)
            ->update([
                'status' => 2,
                'count_at' => date('Y-m-d H:i:s'),
                'count_by' => Auth::user()->name
            ]);

        DB::table('cycle_count_logg')->insert([
            'konten' => Auth::user()->name . ' Memulai Perhitungan Cycle Count di BLOK ' . $blok,
            'type'   => 'gudang',
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => Auth::user()->name,
        ]);

        return view('gudang.form_hitung', compact('blok', 'tgl_upload'));
    }

    public function getCycleCount($blok, $tgl_upload)
    {
        $data =  DB::table('cycle_count')
            ->where('blok', $blok)
            ->whereDate('upload_at', $tgl_upload)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function postCycleCount(Request $request)
    {
        for ($i = 0; $i < count($request->qty); $i++) {
            $validasi = Str::contains($request->qty[$i], ',');
            if ($validasi) {
                return response()->json([
                    'status' => 'koma'
                ]);
                break;
            }
        }
        $ok = [];
        $diff = [];
        for ($i = 0; $i < count($request->qty); $i++) {
            $data[] = DB::table('cycle_count')
                ->select('case_qty')
                ->where('id', $request->id[$i])
                ->get();

            $sesuai = $data[$i]->where('case_qty', $request->qty[$i]);

            //update ok
            $oke[] = DB::table('cycle_count')
                ->select('id')
                ->where('id', $request->id[$i])
                ->where('case_qty', $request->qty[$i])
                ->get()
                ->toArray();

            $notok[] = DB::table('cycle_count')
                ->select('id')
                ->where('id', $request->id[$i])
                ->where('case_qty', '!=', $request->qty[$i])
                ->get()
                ->toArray();
            

            if (count($sesuai) <= 0) {
                $diff[] = 1;
            } else {
                $ok[] = 1;
            }
        }
        $diff = array_sum($diff);
        $id_notok = array_filter($notok);
        $id_ok = array_filter($oke);
        //update not oke
        if(count($id_notok) > 0){
            foreach($id_notok as $id){
                DB::table('cycle_count')
                        ->where('id', $id[0]->id)
                        ->update([
                            'status' => 3,
                            'qty_lapangan' => $request->qty[$i],
                            'count_at' => date('Y-m-d H:i:s'),
                            'count_by' => Auth::user()->name
                        ]);
            }
        }

        // update ok
        if(count($id_ok) > 0) {
            foreach($id_ok as $item) {
                DB::table('cycle_count')
                        ->where('id', $item[0]->id)
                        ->update([
                            'status' => 0,
                            'qty_lapangan' => $request->qty[$i],
                            'count_at' => date('Y-m-d H:i:s'),
                            'count_by' => Auth::user()->name
                        ]);
            }
        }

        DB::table('cycle_count_logg')->insert([
            'konten' => Auth::user()->name . ' Menyelesaikan Perhitungan Cycle Count di BLOK ' . $request->blok,
            'type'   => 'gudang',
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $diff
        ]);
    }

    public function revisiCycleCount()
    {
        $data = DB::table('cycle_count')
            ->where('status', 3)
            ->where('count_by', Auth::user()->name)
            ->get();

        return view('gudang.form_revisi', compact('data'));
    }

    public function postrevisiCycleCount(Request $request)
    {
        if ($request->has('reason')) {
            for ($i = 0; $i < count($request->qty); $i++) {
                $validasi = Str::contains($request->qty[$i], ',');
                if ($validasi) {
                    return response()->json([
                        'status' => 'koma'
                    ]);
                    break;
                }
            }
            $ok = [];
            $diff = [];
            for ($i = 0; $i < count($request->qty); $i++) {
                $data[] = DB::table('cycle_count')
                    ->select('case_qty')
                    ->where('id', $request->id[$i])
                    ->get();

                $sesuai = $data[$i]->where('case_qty', $request->qty[$i]);
                if (count($sesuai) <= 0) {
                    $diff[] = 1;
                } else {
                    DB::table('cycle_count')
                        ->where('id', $request->id[$i])
                        ->update([
                            'qty_validasi' => $request->qty[$i],
                            'reason' => $request->reason[$i],
                            'revisi_at' => date('Y-m-d H:i:s'),
                            'status' => 0,
                        ]);

                    DB::table('cycle_count_logg')->insert([
                        'konten' => Auth::user()->name . ' Menyelesaikan Proses Revisi Cycle Count di BLOK ' . $request->blok[$i],
                        'type'   => 'gudang',
                        'created_at'    => date('Y-m-d H:i:s'),
                        'created_by'    => Auth::user()->name,
                    ]);
                }
            }
            $diff = array_sum($diff);

            return response()->json([
                'status' => 'success',
                'data' => $diff
            ]);
        } else {
            return response()->json([
                'status' => 'null',
            ]);
        }
    }
}
