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

    public function GetMidBelumDihitung($dept)
    {
         $hitungan = DB::table('cycle_count')
                        ->select('material' , 'deskripsi')
                        ->where('dept', $dept)
                        ->whereNull('reason')
                        ->where('status', 0)
                        ->whereYear('tgl_upload', date('Y'))
                        ->groupBy('material')
                        ->get()->pluck('material');

            $master = DB::table('cycle_count_master_mid')
                        ->select('material')
                        ->where('gudang', $dept)
                        ->groupBy('material')
                        ->get()->pluck('material');

            $mid_belum_dihitung = array_diff($master->toArray(), $hitungan->toArray());
            $data               = DB::table('cycle_count_master_mid')
                                    ->select('material', 'deskripsi', 's_bin', 'kategori_barang')
                                    ->where('gudang', $dept)
                                    ->whereIn('material', $mid_belum_dihitung)
                                    ->get();
        return Datatables::of($data)->make(true);
    }

    public function GetAllFrekuensi($dept)
    {
        $master = DB::table('cycle_count')
                ->select(DB::Raw('MONTH(tgl_upload) as bulan'),'material', 'deskripsi', 'kategori_barang', 'tgl_upload')
                ->where('dept', $dept)
                ->where('status', 0)
                ->whereYear('tgl_upload', date('Y'))
                ->whereNotNull('material')
                ->get();
        $groupBy = $master->groupBy('material');
        $sampel =  $groupBy->map(function($item) 
            {
                return [
                    'deskripsi' => $item->first()->deskripsi,
                    'kategori_barang' => $item->first()->kategori_barang,
                    'mid' => $item->first()->material,
                   'frekuensi' =>  $item->unique('tgl_upload')->count(),
                    'cycle_count' => $item->unique('bulan')->count(),
                ];
            });
        $sampel = collect($sampel);
        $data = [];  
        foreach($sampel as $key => $value)
        {
            $data[] = [
                        'deskripsi' => $value['deskripsi'],
                        'kategori_barang' => $value['kategori_barang'],
                        'mid' => $value['mid'],
                        'frekuensi' => $value['frekuensi'],
                        'cycle_count' => $value['cycle_count'],
                    ];
        }

        return Datatables::of($data)->make(true);
    }

    public function CounterCount($dept)
    {
        $data = DB::table('cycle_count')->select('reason')->where('status', 0)->where('dept', $dept)->whereYear('tgl_upload', date('Y'))->get();
        $all = number_format($data->count());
        $ok = number_format($data->whereNull('reason')->count());
        $not_ok = number_format($data->whereNotNull('reason')->count());
        return response()->json([
            'status' => 'success',
            'data' => [
                'ok' => $ok,
                'not_ok' => $not_ok,
                'all' => $all,
            ]
        ]);
    }
    public function GetPerformaForeman($nama)
    {
        $master = DB::table('cycle_count')
                ->select(DB::Raw('MONTH(tgl_upload) as bulan'),'reason', 'group', 'foreman', 'tgl_upload', 'shift', 'dept', 'no_urut', 'status')
                ->where('foreman', $nama)
                ->where('status', 0)
                ->whereYear('tgl_upload', date('Y'))
                ->get();

        $loop   = $master->groupBy('tgl_upload');
        $total  = $master->count();
        $diff   = $master->whereNotNull('reason')->count();
        $ok     = $master->whereNull('reason')->count();

        foreach($master->whereNotNull('reason')->groupBy('reason') as $key => $value)
        {
                $categories[] = $key;
                $data_pie[] = [
                    'name' => $key,
                    'data' => [(float) number_format($value->count() / $master->count() * 100, 2)]
                ];
        }
        $categories = json_encode($categories);
        $data_pie = json_encode($data_pie);
        // dd($categories, $data);

        $pie_ok = number_format($ok / $total * 100, 2);
        $pie_diff = number_format($diff / $total * 100, 2);

        for($bulan=1;$bulan < 13;$bulan++)
        {
            $data = $master->filter(function($item) use ($bulan)
            {
                if( (int) explode('-', $item->tgl_upload)[1] == (int) $bulan)
                {
                    return $item;
                }
            });
            $of_month[]         = $data->whereNull('reason')->count();
            $counting_foreman[] = $data->count();
        }
        // dd($of_month, $counting_foreman);
        foreach($of_month as $key => $value)
        {

            if(($counting_foreman[$key] * 100) == 0 ) {
                $permonth[] = 0;
            }else{

                $permonth[] = (float) number_format($value / $counting_foreman[$key] * 100,2);
            }
        }
        $permonth = json_encode($permonth);
        // dd($permonth);

        return view('cycle-count.wrh1.dashboard.foreman', compact('categories', 'data', 'nama', 'total', 'diff', 'ok', 'permonth', 'master', 'pie_ok', 'pie_diff', 'loop', 'data_pie'));
    }

    public function CariFrekuensiYajra($bulan, $tahun,$dept)
    {
        $master = DB::table('cycle_count')
                ->select(DB::Raw('MONTH(tgl_upload) as bulan'),'material', 'deskripsi', 'kategori_barang', 'tgl_upload')
                ->where('dept', $dept)
                ->where('status', 0)
                ->whereYear('tgl_upload', $tahun)
                ->whereMonth('tgl_upload', $bulan)
                ->whereNotNull('material')
                ->get();
        $groupBy = $master->groupBy('material');
        $sampel =  $groupBy->map(function($item) 
            {
                return [
                    'deskripsi' => $item->first()->deskripsi,
                    'kategori_barang' => $item->first()->kategori_barang,
                    'mid' => $item->first()->material,
                   'frekuensi' =>  $item->unique('tgl_upload')->count(),
                    'cycle_count' => $item->unique('bulan')->count(),
                ];
            });
        $sampel = collect($sampel);
        $data = [];  
        foreach($sampel as $key => $value)
        {
            $data[] = [
                        'deskripsi' => $value['deskripsi'],
                        'kategori_barang' => $value['kategori_barang'],
                        'mid' => $value['mid'],
                        'frekuensi' => $value['frekuensi'],
                        'cycle_count' => $value['cycle_count'],
                    ];
        }
        // dd($data);
        // $data = $data->whereNotNull('mid')->toArray();

        return Datatables::of($data)->make(true);
    }

    public function CariFrekuensi($bulan, $tahun,$dept)
    {
          $hitungan = DB::table('cycle_count')
                        // ->select('material' , 'deskripsi')
                        ->where('dept', $dept)
                        // ->whereNull('reason')
                        ->where('status', 0)
                        ->whereYear('tgl_upload', $tahun)
                        ->whereMonth('tgl_upload', $bulan)
                        ->get();
            $groupBy = $hitungan->groupBy('material');
            $hitungan = $groupBy->map(function($item, $key)
            {
                return [
                    'material' => $key,
                    'count' => $item->unique('tgl_upload')->count(),
                    'total' => $item->count(),
                ];
            });
            $pluck = $hitungan->pluck('material');

            $master = DB::table('cycle_count_master_mid')
                        ->select('material')
                        ->where('gudang', $dept)
                        ->groupBy('material')
                        ->get()->pluck('material');
            
            $sudah_dihitung =  $hitungan->count() / $master->count() * 100;
            $sudah_dihitung = abs((float) number_format($sudah_dihitung, 2));
            //------------------------------------------------------------------------------//
            $belum_dihitung = $master->count() - $hitungan->count();
            $belum_dihitung = $belum_dihitung /  $master->count() * 100;
            $belum_dihitung = abs((float) number_format($belum_dihitung, 2));
            //------------------------------------------------------------------------------//
            $jumlah_belum_dihitung = abs($master->count() - $hitungan->count());
            $mid_belum_dihitung = array_diff($master->toArray(), $pluck->toArray());

            $mid_belum_dihitung = DB::table('cycle_count_master_mid')->whereIn('material', $mid_belum_dihitung)->get();

            return view('cycle-count.wrh1.dashboard.frekuensi', compact('sudah_dihitung', 'belum_dihitung', 'jumlah_belum_dihitung', 'bulan', 'tahun', 'dept', 'mid_belum_dihitung'));
    }
}
