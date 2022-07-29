@extends('layouts.base')

@section('judul', 'Upload Excel')
@section('judul_konten', 'Halaman Upload Excel')


@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }
    </style>
    <div class="container-xxl" id="kt_content_container">
        <div class="row g-5 g-xl-8">
            <div class="col-xxl-4">
                @if (count($data) == 0)
                    <form action="{{ url('cycle-count/admin/upload') }}" method="post" enctype="multipart/form-data"
                        id="form-upload">
                        @csrf
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body mb-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="">PILIH FILE EXCEL</label>
                                        <div class="form-group mt-4">
                                            <input type="file" class="form-control-file fileUpload" name="file"
                                                id="" required placeholder="" aria-describedby="fileHelpId">
                                        </div>
                                        <small class="text-danger">*xls, *xlsx</small>
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="{{ asset('format-master.xlsx') }}" class="btn btn-lg text-white"
                                            style="border-radius: 16px; background-color: green"><i
                                                class="fas fa-file-excel text-white"></i> Download Master</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                    <button type="submit" class="btn btn-info btn-lg btnUpload hide"><i
                                            class="fas fa-save"></i>
                                        UPLOAD</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="card mb-5 mb-xl-8">
                        <form action="{{ url('cycle-count/admin/delete') }}" method="post" id="form-delete">
                            @csrf
                            <div class="card-header">
                                <div class="card-toolbar">
                                    {{-- <div class="float-right">
                                    <button class="btn btn-lg btn-danger"><i class="fas fa-trash-alt"></i> Add</button>
                                </div> --}}
                                </div>
                                <div class="card-toolbar">
                                    <div class="float-right">
                                        <button class="btn btn-lg btn-danger" onclick="deleteMaterial()"><i
                                                class="fas fa-trash-alt"></i>
                                            Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body mb-4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>NO.</th>
                                                    <th>
                                                        <input type="checkbox" id=""
                                                            class="checkbox checkbox-primary checkbox-lg checkAll"
                                                            style="zoom: 140%;">
                                                    </th>
                                                    <th>BLOK</th>
                                                    <th>KLOTER</th>
                                                    <th>MATERIAL</th>
                                                    <th>DESCRIPTION</th>
                                                    <th>CASE QTY</th>
                                                    <th>CASE UOM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $item)
                                                    <tr class="text-center">
                                                        <td scope="row">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <input type="checkbox" name="id[]" id=""
                                                                class="checkbox checkbox-primary checkbox-lg"
                                                                style="zoom: 180%;" value="{{ $item->id }}">
                                                        </td>
                                                        <td>{{ $item->blok }}</td>
                                                        <td>{{ $item->kloter }}</td>
                                                        <td>{{ $item->material }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td>{{ $item->case_qty }}</td>
                                                        <td>{{ $item->case_uom }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </form>
                    </div>
            </div>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $('.fileUpload').on('change', function() {
            $('.btnUpload').removeClass('hide');
        });
        $('#form-upload').on('submit', function() {
            $('.btnUpload').addClass('hide');
            $('.btnUpload').html('<i class="fas fa-spinner fa-spin"></i> UPLOADING...');
            $('.btnUpload').attr('disabled', true);
        });

        function deleteMaterial() {
            var check = confirm('Apakah anda yakin ingin menghapus data ini?');
            if (check) {
                $('#form-delete').submit();
            }
        }
        $(".checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
@endsection
