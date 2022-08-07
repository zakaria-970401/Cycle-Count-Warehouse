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
        return view('report.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CariReportDashboard($dept, $kategori)
    {
        if($kategori == 'performa_section')
        {
            $data = DB::table('cycle_count')->select('reason', 'dept', 'status', 'tgl_upload')->where('dept', $dept)->where('status', 0)->whereYear('tgl_upload', date('Y'))->get();

            $years_ok = (float) number_format($data->whereNull('reason')->count() / $data->count() * 100, 2);
            $years_not_ok = (float) number_format($data->whereNotNull('reason')->count() / $data->count() * 100, 2);
            $categories = $data->whereNotNull('reason')->groupBy('reason');

            foreach($categories as $key => $value)
            {
                $categories_name[] = $key;
                $categories_count[] = (float) number_format($value->count() / $data->count() * 100, 2);
            }
            //performance section of month
            for($bulan=1;$bulan < 13;$bulan++)
            { 
                $_data = $data->filter(function($item) use ($bulan)
                {
                    if((int)explode('-', $item->tgl_upload)[1] == (int)$bulan)
                    {
                        return $item;
                    }
                });
                $of_month[] = $_data->whereNull('reason')->count();
                $counting_foreman[] = $_data->count();
            }
            foreach($of_month as $key => $value)
            {
                if(($counting_foreman[$key] * 100) == 0 ) {
                    $month_ok[] = 0;
                }else{

                    $month_ok[] = (float) number_format($value / $counting_foreman[$key] * 100, 2);
                }
            }
        // dd($month_ok, $counting_foreman);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'years_ok' => $years_ok,
                    'years_not_ok' => $years_not_ok,
                    'month_ok' => $month_ok,
                    'kategorinya' => 'performa_section',
                    'categories_name' => $categories_name,
                    'categories_count' => $categories_count,
                    'data' => $data
                ]
            ]);
    }

        if($kategori == 'performa_foreman')
        {
            $data = DB::table('cycle_count')
                    ->where('status', 0)
                    ->where('dept', $dept)
                    ->whereYear('tgl_upload', date('Y'))->get();
            $all = $data;
            $categories = $data->groupBy('foreman');
            $data_grafik = [];

            foreach($categories as $key => $value)
            {
                $ok[] = $data->where('foreman', $key)->whereNull('reason')->count();
                $not_ok[] = $data->where('foreman', $key)->whereNotNull('reason')->count();
            }
            foreach($ok as $key => $value)
            {
                $data_grafik[] = (float) number_format($value / ($ok[$key] + $not_ok[$key]) * 100, 2);
            }
            
            // $data_grafik = array_map('floatval', $data_grafik);
            $summary_ok = number_format(array_sum($ok));
            $summary_not_ok = number_format(array_sum($not_ok));

            $ok = array_map(function($number) use ($ok) {
                return number_format($number);
            }, $ok);

            $not_ok = array_map(function($number) use ($not_ok) {
                return number_format($number);
            }, $not_ok);

            //performance section of year
            $years_ok = (float) number_format($data->whereNull('reason')->count() / $data->count() * 100, 2);
            $years_not_ok = (float) number_format($data->whereNotNull('reason')->count() / $data->count() * 100, 2);

            for($bulan=1;$bulan < 13;$bulan++)
            {
                 $_data = $data->filter(function($item) use ($bulan)
                {
                    if((int)explode('-', $item->tgl_upload)[1] == (int)$bulan)
                    {
                        return $item;
                    }
                });
                $of_month[] = $_data->whereNull('reason')->count();
                $counting_foreman[] = $_data->count();
            }
            foreach($of_month as $key => $value)
            {

                if(($counting_foreman[$key] * 100) == 0 ) {
                    $month_ok[] = 0;
                }else{

                    $month_ok[] = (float) number_format($value / $counting_foreman[$key] * 100, 2);
                }
            }
            $categories = $categories->keys();
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'categories' => $categories,
                        'all' => $all,
                        'ok' => $ok,
                        'not_ok' => $not_ok,
                        'summary_ok' => $summary_ok,
                        'summary_not_ok' => $summary_not_ok,
                        'data_grafik' => $data_grafik,
                        'years_ok' => $years_ok,
                        'years_not_ok' => $years_not_ok,
                        'month_ok' => $month_ok,
                        'kategorinya' => 'performa_foreman',
                        // 'pie_foreman' => $pie_foreman,
                    ]
                ]);
        }
        if($kategori == 'frekuensi')
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

            $sudah_dihitung =  $hitungan->count() / $master->count() * 100;
            $sudah_dihitung = abs((float) number_format($sudah_dihitung, 2));
            //------------------------------------------------------------------------------//
            $belum_dihitung = $master->count() - $hitungan->count();
            $belum_dihitung = $belum_dihitung /  $master->count() * 100;
            $belum_dihitung = abs((float) number_format($belum_dihitung, 2));
            //------------------------------------------------------------------------------//
            $jumlah_belum_dihitung = abs($master->count() - $hitungan->count());

            return response()->json([
                'status' => 'success',
                'data' => [
                    'sudah_dihitung' => $sudah_dihitung,
                    'belum_dihitung' => $belum_dihitung,
                    'jumlah_belum_dihitung' => $jumlah_belum_dihitung,
                ]
            ]);
        }
        if($kategori ==  'aktifitas')
        {
            $data = DB::table('cycle_count')->where('dept', $dept)->whereYear('tgl_upload', date('Y'))->get();

            $total_aktifitas = $data->where('status', 0)->count();
            $belum_selesai = $data->where('status', 1)->count();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_aktifitas' => $total_aktifitas,
                    'belum_selesai' => $belum_selesai,
                ]
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
