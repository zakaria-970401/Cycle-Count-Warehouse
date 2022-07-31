@extends('layouts.base')

@section('judul', 'Hitung Cycle Count')
@section('judul_konten', 'Halaman Hitung Cycle Count')


@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }

        .adminActions {
            position: fixed;
            bottom: 35px;
            right: 35px;
        }

        .adminButton {
            height: 70px;
            width: 70px;
            background-color: rgba(43, 184, 0, 0.8);
            border-radius: 50%;
            display: block;
            color: #fff;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .adminButton i {
            font-size: 22px;
        }

        .adminButtons {
            position: absolute;
            width: 140%;
            bottom: 120%;
            text-align: center;
        }

        .adminButtons a {
            display: block;
            width: 145px;
            height: 145px;
            border-radius: 50%;
            text-decoration: none;
            margin: 10px auto 0;
            line-height: 1.15;
            color: #fff;
            opacity: 0;
            visibility: hidden;
            position: relative;
            box-shadow: 0 0 5px 1px rgba(51, 51, 51, .3);
        }

        .adminActions a i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .adminToggle {
            -webkit-appearance: none;
            position: absolute;
            border-radius: 50%;
            top: 0;
            left: 0;
            margin: 0;
            width: 150%;
            height: 100%;
            cursor: pointer;
            background-color: transparent;
            border: none;
            outline: none;
            z-index: 2;
            transition: box-shadow .2s ease-in-out;
            box-shadow: 0 3px 5px 1px rgba(51, 51, 51, .3);
        }
    </style>
    <div class="container-xxl" id="kt_content_container">

        <div class="row">
            <form action="{{ url('cycle-count/gudang/postCycleCount') }}" method="post" id="postCycleCount">
                @csrf
                <div class="col-sm-12">
                    <div class="row appendList">

                    </div>
                </div>
                <div class="appendbutton">

                </div>
            </form>
        </div>
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

        function postCycleCount() {
            submit()
        }

        function submit() {
            $('#postCycleCount').on('submit', function(e) {
                $('.adminActions').hide('fast');
                e.preventDefault();
                $.ajax({
                    url: "{{ url('cycle-count/gudang/postCycleCount') }}",
                    type: "POST",
                    data: $('#postCycleCount').serialize(),
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data berhasil disimpan',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href =
                                        "{{ url('cycle-count/gudang/index') }}";
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
        }
    </script>
@endsection
