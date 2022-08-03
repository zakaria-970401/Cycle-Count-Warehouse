@extends('layouts.base')

@section('judul', 'Hitung Cycle Count')
@section('judul_konten', 'Halaman Hitung Cycle Count')


@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }
    </style>
    <div class="container-xxl" id="kt_content_container">

        <form action="{{ url('cycle-count/gudang/postCycleCount') }}" method="post" id="postCycleCount">
            @csrf
            <div class="row">
                <div class="col-sm-12">
                    <div class="row appendList">

                    </div>
                </div>
            </div>
            <div class="float-end">
                <button type="submit" class="btn btn-success btn-lg adminActions"><i class="fas fa-save"></i>
                    Simpan</button>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $('.appendList').html("")
        $.ajax({
            url: "{{ url('cycle-count/gudang/getCycleCount') }}/" + '{{ $kloter }}' + '/' +
                '{{ $blok }}' + '/' + '{{ $tgl_upload }}',
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                $.each(response.data, function(index, value) {
                    $('.appendList').append(`<div class="col-md-6 col-xl-4">
                                    <div class="card bg-light-warning mt-4" style="border: solid; border-radius: 13px;">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h5 class="text-dark">${value.description}</h5>
                                            </div>
                                            <div class="card-toolbar">
                                                <span class="badge text-white badge-dark fw-bold me-auto px-4 py-3">MID : ${value.material} | BLOK : ${value.blok}</span> 
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <input type="text" required class="form-control" name="qty[]" id=""
                                                    aria-describedby="helpId" placeholder="Masukan Qty">
                                                <small id="helpId" class="form-text text-danger">*Gunakan titik jika angka pecahan
                                                    ex: 3.5</small>
                                                    <input type="hidden" required class="form-control" name="id[]" id=""
                                                    aria-describedby="helpId" value="${value.id}">
                                                    <input type="hidden" required class="form-control" name="blok" id=""
                                                    aria-describedby="helpId" value="{{ $blok }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>`)
                    $('.appendbutton').append(`
                                  <div class="adminActions">
                                    <button class="adminButton" href="#" onclick="postCycleCount()"><i
                                            class="fas fa-save text-white"></i></button>
                                </div>`)
                })
            }
        });

        $('#postCycleCount').on('submit', function(e) {
            $('.adminActions').hide('fast');
            e.preventDefault();
            $.ajax({
                url: "{{ url('cycle-count/gudang/postCycleCount') }}",
                type: "POST",
                data: $('#postCycleCount').serialize(),
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
                    }
                    if (response.data == 0) {
                        Swal.fire({
                            title: 'Good Joob!',
                            text: 'Tidak Ada Selisih Pada Perhitungan Cycle Count',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                window.location.href =
                                    "{{ url('cycle-count/gudang/hitung') }}";
                            }
                        })
                    } else if (response.data > 0) {
                        Swal.fire({
                            title: 'Selisih Perhitungan!',
                            text: 'Ada ' + response.data +
                                ' Data Yang Selisih Pada Perhitungan Cycle Count',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                window.location.href =
                                    "{{ url('cycle-count/gudang/hitung') }}";
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
