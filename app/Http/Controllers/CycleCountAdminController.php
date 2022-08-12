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
use App\Mail\CycleCountMail;
use Illuminate\Support\Facades\Mail;
use Validator;

class CycleCountAdminController extends Controller
{
    public function upload_excel()
    {
        $data = DB::table('cycle_count')->whereDate('upload_at', date('Y-m-d'))->get();
        return view('admin.upload_excel', compact('data'));
    }

    public function aktifitas()
    {
        $data = DB::table('cycle_count_logg')->orderBy('id', 'DESC')->whereDate('created_at', date('Y-m-d'))->get();
        return view('aktifitas', compact('data'));
    }

    public function delete(Request $request)
    {
        if ($request->has('id')) {
            for ($i = 0; $i < count($request->id); $i++) {
                DB::table('cycle_count')->where('id', $request->id[$i])->delete();
            }
            toastr()->success('Data berhasil dihapus');
            return back();
        } else {
            toastr()->error('Tidak ada data yang dipilih');
            return back();
        }
    }

    public function jadwal()
    {
        $data = DB::table('cycle_count')
            ->whereMonth('upload_at', date('m'))
            ->groupBy(DB::raw('Date(upload_at)'))
            ->orderBy('upload_at', 'DESC')
            ->get();
        return view('jadwal', compact('data'));
    }

    public function showJadwal($tgl_upload)
    {
        $tgl_upload = explode(' ', $tgl_upload)[0];
        $data = DB::table('cycle_count')
            ->whereDate('upload_at', $tgl_upload)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function post_upload_excel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);
        // dd($validator->fails(), $request->all());

        if ($validator->fails()) {
            toastr()->error('Format file yang diperbolehkan hanya xls atau xlsx');
            return back();
        }

        $excel = $request->file('file');
        Excel::import(new CycleCountImport, $excel);
        DB::table('cycle_count_logg')->insert([
            'konten' => Auth::user()->name . ' Mengupload data excel',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->name,
            'type' => 'admin',
        ]);
        toastr()->success('File berhasil diupload');
        return back();
    }

    public function generateExcel()
    {
        return view('generate_excel');
    }

    public function cariData($tgl_mulai, $tgl_selesai)
    {
        $data = DB::table('cycle_count')
            ->whereBetween('upload_at', [$tgl_mulai. ' 00:00:00', $tgl_selesai. ' 23:59:59'])
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
