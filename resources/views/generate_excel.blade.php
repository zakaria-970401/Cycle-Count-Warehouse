@extends('layouts.base')

@section('judul', 'Generate Excel')
@section('judul_konten', 'Halaman Generate Cycle Count')

@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }
    </style>
    <div class="container-xxl" id="kt_content_container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <input type="date" class="form-control tgl_mulai" name="tgl_mulai" id=""
                                        aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted">Tanggal Mulai </small>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <input type="date" class="form-control tgl_selesai" name="tgl_selesai" id=""
                                        aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted">Tanggal Selesai </small>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <button class="btn btn-dark" id="btn-search"><i class="fas fa-search"></i>
                                        Cari</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row appendResult">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#btn-search').on('click', function() {
            var tgl_mulai = $('.tgl_mulai').val();
            var tgl_selesai = $('.tgl_selesai').val();
            if (tgl_mulai == '' || tgl_selesai == '') {
                alert('Tanggal tidak boleh kosong');
                return false;
            } else {
                $.ajax({
                    url: "{{ url('cycle-count/cariData') }}/" + tgl_mulai + "/" + tgl_selesai,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        tgl_mulai: tgl_mulai,
                        tgl_selesai: tgl_selesai
                    },
                    success: function(response) {
                        $('.appendResult').html('');
                        $('.appendResult').append(`<div class="col-sm-12">
                                <div class="table table-responsive">
                                     <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="table">
                                        <thead>
                                            <tr>
                                               <th>NO</th>
                                                <th>COUNT BY</th>
                                                <th>MID</th>
                                                <th>DESCRIPTION</th>
                                                <th>BLOK</th>
                                                <th>CASE QTY</th>
                                                <th>QTY LAPANGAN</th>
                                                <th>QTY VALIDASI</th>
                                                <th>REASON</th>
                                                <th>STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>    
                                </div>
                            </div>`);
                        var table = $('#table').DataTable({
                            columnDefs: [{
                                "defaultContent": "-",
                                "targets": "_all"
                            }], 
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'excel',
                            ]
                        }).draw();

                        $.each(response.data, function(key, value) {
                            if(value.status == 1){
                                var status_hitung = '<span class="badge badge-danger">Belum Di Hitung</span>';
                            }else if(value.status == 3 ){
                                var status_hitung = '<span class="badge badge-warning">Proses Revisi</span>';
                            }else{
                                var status_hitung = '<span class="badge badge-success">Sudah Di Hitung</span>';
                            }
                            table.row.add([
                                parseInt(key + 1),
                                value.name,
                                value.material,
                                value.description,
                                value.blok,
                                value.case_qty,
                                value.qty_lapangan,
                                value.qty_validasi,
                                value.reason,
                                status_hitung
                            ]).draw();
                        });
                    }
                });

            }
        });
    </script>
@endsection
