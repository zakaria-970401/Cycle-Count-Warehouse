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

    public function detail_spk($dept, $no_urut, $shift)
    {
        $data = DB::table('cycle_count')
            ->where('dept', $dept)
            ->where('no_urut', $no_urut)
            ->where('shift', $shift)
            ->get();
        // dd($data->where('status',  0 ));

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

    public function cariData($tgl_mulai, $tgl_selesai)
    {
        $data = DB::table('cycle_count')
            ->whereBetween('upload_at', [$tgl_mulai, $tgl_selesai])
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function hapus_file($dept, $no_urut, $alasan)
    {
        DB::table('cycle_count')->where('dept', $dept)->where('no_urut', $no_urut)->update([
            'status'     => 99,
            'keterangan' => $alasan,
        ]);

        DB::table('cycle_count_sc')->where('dept', $dept)->where('no_urut', $no_urut)->update([
            'status'     => 99,
        ]);

        DB::table('cycle_count_logg')->insert([
            'konten' => 'Admin ' . $dept . ' Menghapus SPK dengan alasan ' . $alasan . ' di Tanggal ' . date('d-M-Y') . ' Jam ' . date('H:i'),
            'tanggal' => date('Y-m-d'),
            'jam'   => date('H:i:s'),
            'dept'   => $dept,
            'type'   => 'admin'
        ]);

        return response()->json([
            'status' => 0,
        ]);
    }

    public function GetAllJadwal($dept)
    {
        $data = DB::table('cycle_count')->where('dept', $dept)->orderBy('tgl_upload', 'DESC')->groupBy('no_urut')->get()->toArray();
        return Datatables::of($data)->make(true);
    }

    public function GetListPermintaanOtomatis()
    {
        $data = DB::table('cycle_count')
            ->where('otomatis', 'y')
            ->where('status', 22)
            ->groupBy('blok')
            ->orderBy('id', 'DESC')
            ->get();

        return Datatables::of($data)->make(true);
    }

    public function GetListPermintaanOtomatisAjax()
    {
        $data = DB::table('cycle_count')
            ->where('otomatis', 'y')
            ->where('status', 22)
            ->groupBy('blok')
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function DirectListPermintaanOtomatis()
    {
        return view('cycle-count.ListPengerjaanOtimatis');
    }

    public function GetListProses($dept)
    {
        $cross = false;

        if (date('l') != 'Saturday') {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if ($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00")) {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00")) {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00")) {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        } else {
            $currentTime = strtotime(date('H:i:s'));
            if ($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00")) {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00")) {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00")) {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }

            $shift_number = $shift;
        }

        if ($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '" . $tgl_sekarang . " 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '" . date('Y-m-d') . " 06:59:59'");
        } else {
            $where = DB::raw("tgl_upload = '" . date('Y-m-d') . "'");
        }

        $data = DB::table('cycle_count')
            ->whereRaw($where)
            ->where('shift', $shift)
            ->where('dept', $dept)
            ->where('status', '!=', 99)
            ->groupBy('blok')
            ->get();
        // dd($data);
        return Datatables::of($data)->make(true);
    }

    public function DeleteRow($id, $dept)
    {
        $material = DB::table('cycle_count')->where('id', $id)->first();
        DB::table('cycle_count')->where('id', $id)->delete();

        DB::table('cycle_count_logg')->insert([
            'konten' => 'Admin ' . $dept . ' Menghapus 1 Row Baris SPK BLOK ' . $material->blok . '-KLOTER ' . $material->kloter . '-' . $material->deskripsi . '-' . $material->material . ' di Tanggal ' . date('d-M-Y') . ' Jam ' . date('H:i'),
            'tanggal' => date('Y-m-d'),
            'jam'   => date('H:i:s'),
            'dept'   => $dept,
            'type'   => 'admin'
        ]);

        $cross = false;

        if (date('l') != 'Saturday') {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if ($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00")) {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00")) {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00")) {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        } else {
            $currentTime = strtotime(date('H:i:s'));
            if ($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00")) {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00")) {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00")) {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }

            $shift_number = $shift;
        }

        if ($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '" . $tgl_sekarang . " 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '" . date('Y-m-d') . " 06:59:59'");
        } else {
            $where = DB::raw("tgl_upload = '" . date('Y-m-d') . "'");
        }

        $data = DB::table('cycle_count')
            ->whereRaw($where)
            ->where('shift', $shift)
            ->where('dept', $dept)
            ->where('status', '!=', 99)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function PilihKloter($dept, $no_urut, $shift, $kloter)
    {
        $data = DB::table('cycle_count')
            ->where('shift', $shift)
            ->where('dept', $dept)
            ->where('no_urut', $no_urut)
            ->where('kloter', $kloter)
            ->where('status', 1)
            ->groupBy('blok')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function KloterPengerjaanOtomatis($dept)
    {
        $data = DB::table('cycle_count')
            ->where('dept', $dept)
            ->whereNotIn('status', [99, 0])
            ->orderBy('id', 'DESC')
            ->groupBy('tgl_upload')
            ->get();
        // dd($data);

        return response()->json([
            'status' => 1,
            'data'   => $data,
        ]);
    }

    public function CariBlokPengerjaanOtomatis($tgl_upload, $dept)
    {
        $data = DB::table('cycle_count')
            ->whereDate('tgl_upload', $tgl_upload)
            ->where('dept', $dept)
            ->whereNotIn('status', [99, 0])
            ->orderBy('id', 'DESC')
            ->groupBy('blok')
            ->get();

        return response()->json([
            'status' => 1,
            'data'   => $data,
        ]);
    }

    public function GetListPengerjaanOtomatis($blok, $dept, $no_urut)
    {
        $data = DB::table('cycle_count')
            ->where('blok', $blok)
            ->where('dept', $dept)
            ->where('no_urut', $no_urut)
            ->whereNotIn('status', [99, 0, 22])
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'status' => 1,
            'data'   => $data,
        ]);
    }

    public function post_pengerjaan_otomatis(Request $request)
    {
        // dd($request->all());
        $chief = DB::table('cycle_count_master_nama')->select('nik_chief')->first();
        $mail  = DB::table('users')->select('email', 'name')->where('username', $chief->nik_chief)->first();
        $dept = DB::table('users')->select('departments.name')->join('departments', 'users.dept_id', '=', 'departments.id')->where('departments.id', Auth::user()->dept_id)->first();

        if ($mail != NULL) {
            Mail::to($mail->email)->send(new CycleCountMail('CYCLE COUNT APPS', 'PERMINTAAN PENGERJAAN OTOMATIS', 'Halo, ' . $mail->name . ',' . ' ' . Auth::user()->name . ' Baru Saja Mengirimkan Permintaan Pengerjaan Otomatis Cycle Count. Silahkan Cek Dalam Sistem MY PAS Online'));
        }

        for ($i = 0; $i < count($request->blok); $i++) {
            $data = DB::table('cycle_count')
                ->where('blok', $request->blok[$i])
                ->where('dept', $request->dept)
                ->where('no_urut', $request->no_urut)
                ->update([
                    'otomatis'     => 'y',
                    'status'   => 22,
                ]);
        }

        DB::table('cycle_count_logg')->insert([
            'konten' => 'Admin ' . $dept->name . ' Meminta Permohonan Pengerjaan Otomatis SPK di Tanggal ' . date('d-M-Y') . ' Jam ' . date('H:i'),
            'tanggal' => date('Y-m-d'),
            'jam'   => date('H:i:s'),
            'dept'   => $dept->name,
            'type'   => 'admin-otomatis',
            'status'    => 2
        ]);

        Session::flash('info', 'Berhasil, Sistem Akan Mengirim Notifikasi Ke Chief.. ');
        return back();
    }

    public function GetListAllMid()
    {
        $dept = DB::table('departments')->select('departments.name')->join('users', 'departments.id', '=', 'users.dept_id')->where('departments.id', Auth::user()->dept_id)->first();
        $data = DB::table('cycle_count_master_mid')->where('gudang', $dept->name)->groupBy('material')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function EditRow($id)
    {
        $data = DB::table('cycle_count')->where('id', $id)->first();
        return response()->json([
            'data' => $data
        ]);
    }

    public function UpdateRow(Request $request)
    {
        // dd($request->all());
        $data = DB::table('cycle_count')->where('id', $request->id)->update(
            [
                'blok' => $request->blok,
                'kloter' => $request->kloter,
                's_bin' => $request->s_bin,
                'case_uom' => $request->case_uom,
                'case_qty' => $request->case_qty,
                'batch' => $request->batch,
            ]
        );
        $data = DB::table('cycle_count')
            ->where('dept', $request->dept)
            ->where('no_urut', $request->no_urut)
            ->where('shift', $request->shift)
            ->first();

        return response()->json([
            'status' => 1,
            'data'   => $data,
        ]);
    }

    public function monitoring_section()
    {
        $cross = false;
        if (date('l') != 'Saturday') {
            $currentTime = strtotime(date('H:i:s'));
            $startTime   =  strtotime("00:00:01");
            $endTime     = strtotime("06:59:00");

            if ($currentTime >= strtotime("06:59:01") and  $currentTime <= strtotime("15:00:00")) {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("15:00:01") and  $currentTime <= strtotime("23:00:00")) {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("23:00:01") and  $currentTime <= strtotime("23:59:00")) {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= $startTime && $currentTime <= $endTime) {
                $tgl_sekarang = date('Y-m-d', strtotime('-1 day'));
                $shift = 3;
                $cross = true;
            }
            $shift_number = $shift;
        } else {
            $currentTime = strtotime(date('H:i:s'));
            if ($currentTime >= strtotime("06:30:01") and  $currentTime <= strtotime("11:59:00")) {
                $shift = 1;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("12:00:01") and  $currentTime <= strtotime("17:00:00")) {
                $shift = 2;
                $tgl_sekarang = date('Y-m-d');
            } else if ($currentTime >= strtotime("17:00:01") and  $currentTime <= strtotime("22:01:00")) {
                $shift = 3;
                $tgl_sekarang = date('Y-m-d');
            }

            $shift_number = $shift;
        }

        if ($cross) {
            $where = DB::raw("CONCAT(tgl_upload, ' ', jam_upload) >= '" . $tgl_sekarang . " 23:00:00' AND CONCAT(tgl_upload, ' ', jam_upload) <= '" . date('Y-m-d') . " 06:59:59'");
        } else {
            $where = DB::raw("tgl_upload = '" . date('Y-m-d') . "'");
        }

        $dept = DB::table('departments')->select('departments.name')->join('users', 'departments.id', '=', 'users.dept_id')->where('departments.id', Auth::user()->dept_id)->first();

        $counting = DB::table('cycle_count')
            ->whereRaw($where)
            ->where('shift', $shift)
            ->where('dept', $dept->name)
            ->get();

        $data = DB::table('cycle_count_logg')
            ->where('tanggal', date('Y-m-d'))
            ->where('dept', $dept->name)
            ->orderBy('id', 'DESC')
            ->get();


        $admin = $data->filter(function ($item) {
            return false !== stripos($item->type, 'admin');
        });
        $sc = $data->filter(function ($item) {
            return false !== stripos($item->type, 'sc');
        });

        return view('cycle-count.spv.index', compact('data', 'admin', 'sc', 'counting', 'dept'));
    }

    public function UbahStatusNotif()
    {
        $dept = DB::table('departments')->select('departments.name')->join('users', 'departments.id', '=', 'users.dept_id')->where('departments.id', Auth::user()->dept_id)->first();
        DB::table('cycle_count_logg')->where('status', 1)->where('type', 'admin-otomatis')->where('dept', $dept->name)->update(['status' => 0]);
        return back();
    }
}
