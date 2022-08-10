<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\Datatables;

class CycleCountReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('cycle_count')
                ->where('status', 0)
                ->whereYear('upload_at', date('Y'))
                ->get();

        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $sc = [];
        $alasan = [];
        
        $user = $data->groupBy('count_by');
        $reason = $data->whereNotNull('reason')->groupBy('reason');
        foreach ($user as $key => $value) {
            $sc[$key] = [];
        }
        foreach ($reason as $key => $value) {
            $alasan[$key] = [];
        }

        foreach($bulan as $key => $value) {
            // $data_sc = [];
            foreach ($sc as $_key => $_val ) {;
                $sc[$_key][] = DB::table('cycle_count')
                                ->whereMonth('upload_at', $key)
                                ->where('count_by', $_key)
                                ->count() ;
            }

            foreach ($alasan as $_key => $_val ) {;
                $alasan[$_key][] = DB::table('cycle_count')
                                ->whereMonth('upload_at', $key)
                                ->where('reason', $_key)
                                ->count() ;
            }
        }

        foreach($sc as $name => $count) {
            $series[] = [
                'name' => $name,
                'data' => $count
            ];
        }
        foreach($alasan as $name => $count) {
            $series_reason[] = [
                'name' => $name,
                'data' => $count
            ];
        }

        $series = json_encode($series);
        $series_reason = json_encode($series_reason);

        return view('report.index', compact('series', 'bulan', 'series_reason'));
    }

    public function searchByTahun($tahun)
    {
        $data = DB::table('cycle_count')
                ->where('status', 0)
                ->whereYear('upload_at', $tahun)
                ->get();
        if($data->count() > 0) {
            $bulan = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];

            $sc = [];
            $alasan = [];
            
            $user = $data->groupBy('count_by');
            $reason = $data->whereNotNull('reason')->groupBy('reason');
            foreach ($user as $key => $value) {
                $sc[$key] = [];
            }
            foreach ($reason as $key => $value) {
                $alasan[$key] = [];
            }

            foreach($bulan as $key => $value) {
                // $data_sc = [];
                foreach ($sc as $_key => $_val ) {;
                    $sc[$_key][] = DB::table('cycle_count')
                                    ->whereMonth('upload_at', $key)
                                    ->where('count_by', $_key)
                                    ->count() ;
                }

                foreach ($alasan as $_key => $_val ) {;
                    $alasan[$_key][] = DB::table('cycle_count')
                                    ->whereMonth('upload_at', $key)
                                    ->where('reason', $_key)
                                    ->count() ;
                }
            }

            foreach($sc as $name => $count) {
                $series[] = [
                    'name' => $name,
                    'data' => $count
                ];
            }
            foreach($alasan as $name => $count) {
                $series_reason[] = [
                    'name' => $name,
                    'data' => $count
                ];
            }
            $series = json_encode($series);
            $series_reason = json_encode($series_reason);
            
            return response()->json([
                'data' => [
                    'series' => $series,
                    'series_reason' => $series_reason,
                    'bulan' => $bulan,
                ],
            ]);
        }else{
            return response()->json([
                'status' => 'not found',
            ]);
        }
    }
}
