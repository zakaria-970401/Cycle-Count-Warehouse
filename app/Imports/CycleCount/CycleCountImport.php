<?php

namespace App\Imports\CycleCount;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\WarehouseMIDModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Facades\Validator;

class CycleCountImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        $data = [];
        $logg = [];

        $validasi = Validator::make($rows->toArray(), [
             '*.case_qty' => 'numeric',
         ]);

        if($validasi->fails()){
        //  dd('validasi gagal');
        toastr()->error('Case Qty Masih Berupa Rumus cek kembali file excel anda');
        return back();
        }
        else
        {
            foreach($rows as $row)
            {
                $data[] =  array(
                'blok'      => $row['blok'],
                'material'  => $row['material'],
                'description' => $row['description'],
                'case_uom'  => $row['case_uom'],
                'case_qty'  => $row['case_qty'],
                'upload_at' => date('Y-m-d H:i:s'),
                'upload_by' => Auth::user()->name,
              );
            }
        }
        DB::table('cycle_count')->insert($data);
  }
}
