@extends('layouts.base')

@section('judul', 'Revisi Cycle Count')
@section('judul_konten', 'Halaman Revisi Cycle Count')


@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }
    </style>
    <div class="container-xxl" id="kt_content_container">

        <form action="{{ url('cycle-count/gudang/revisiCycleCount') }}" method="post" id="revisiCycleCount">
            @csrf
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-reponsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="text-center">
                                            <th>NO</th>
                                            <th>MID</th>
                                            <th>DESCRIPTION</th>
                                            <th>BLOK</th>
                                            <th>REASON</th>
                                            <th>QTY REVISI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr class="text-center">
                                                <td scope="row">{{ $loop->iteration }}</td>
                                                <td scope="row">{{ $item->material }}</td>
                                                <td scope="row">{{ $item->description }}</td>
                                                <td scope="row">{{ $item->blok }}</td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" name="reason[]" required
                                                            id="">
                                                            <option value="" selected disabled>PILIH REASON</option>
                                                            <option value="SALAH INPUT">SALAH INPUT</option>
                                                            <option value="SALAH PACKING">SALAH PACKING</option>
                                                            <option value="TERSELIP">TERSELIP</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="qty[]"
                                                            id="" aria-describedby="helpId" required
                                                            placeholder="Masukan Qty Revisi">
                                                        <input type="hidden" name="id[]" value="{{ $item->id }}">
                                                        <input type="hidden" name="blok[]" value="{{ $item->blok }}">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-end">
                                <button type="submit" class="btn btn-dark btn-lg adminActions"><i class="fas fa-save"></i>
                                    Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $('#revisiCycleCount').on('submit', function(e) {
            $('.adminActions').hide('fast');
            e.preventDefault();
            $.ajax({
                url: "{{ url('cycle-count/gudang/revisiCycleCount') }}",
                type: "POST",
                data: $('#revisiCycleCount').serialize(),
                dataType: "JSON",
                success: function(response) {
                    if (response.status == 'koma') {
                        $('.adminActions').show('fast');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Inputan kamu masih ada koma..',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                    } else if (response.status == 'null') {
                        $('.adminActions').show('fast');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Tidak Ada Data Yang Di Olah..',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                    }
                    if (response.data == 0) {
                        Swal.fire({
                            title: 'Good Joob!',
                            text: 'Data Berhasil Di Simpan',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                window.location.href =
                                    "{{ url('cycle-count/gudang/revisiCycleCount') }}";
                            }
                        })
                    } else if (response.data > 0) {
                        Swal.fire({
                            title: 'Selisih!',
                            text: 'Ada ' + response.data +
                                ' Data Yang Selisih Pada Proses Revisi Cycle Count',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                window.location.href =
                                    "{{ url('cycle-count/gudang/revisiCycleCount') }}";
                            }
                        })
                    }
                },
                error: function(error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Internal Server Error, please refresh this page',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    $('.adminActions').show('fast');
                }
            });
        });
    </script>
@endsection
