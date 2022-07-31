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
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row appendList">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.appendList').html("")
        $.ajax({
            url: "{{ url('cycle-count/gudang/getListBlok') }}",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                $.each(response.data, function(index, value) {
                    if (value.status == 1) {
                        var status = 'BELUM DI HITUNG'
                        var badge_status = 'badge-warning'
                        var button_kerjakan =
                            '<div class="fs-3 fw-bold text-dark"><a href="{{ url('cycle-count/gudang/formHitung') }}/' +
                            value.kloter + '/' + value.blok + '/' + value.upload_at +
                            '")}}" class="btn btn-dark btn-lg"><i class="fas fa-check-circle"></i> Hitung Sekarang</a></div>'
                    } else if (value.status == 2) {
                        var status = 'PROSES PERHITUNGAN'
                        var badge_status = 'badge-light-info'
                        var button_kerjakan =
                            '<div class="fs-3 fw-bold text-dark">Proses Perhitungan Oleh : ' + value
                            .count_by + ' </div>'
                    } else {
                        var status = 'SUDAH DI HITUNG'
                        var badge_status = 'badge-success'
                        var button_kerjakan =
                            '<div class="fs-3 fw-bold text-dark">Sudah Perhitungan Oleh : ' + value
                            .count_by + ' </div>'
                    }
                    $('.appendList').append(`<div class="col-md-6 col-xl-4">
									<div class="card bg-light-danger mt-3" style="border-radius: 15px;">
										<div class="card-header border-0 pt-9">
											<!--begin::Card Title-->
											<div class="card-title m-0">
												<!--begin::Avatar-->
												<div class="symbol symbol-190px w-50px bg-light">
													<span class="badge badge-bs-pill badge-primary badge-lg">BLOK : ${value.blok} </span>
												</div>
											</div>
											<div class="card-toolbar">
												<span class="badge badge-bs-pill ${badge_status} fw-bold me-auto px-4 py-3">${status}</span>
											</div>
										</div>
										<div class="card-body p-9">
											${button_kerjakan}
										</div>
									</div>
								</div>`)
                })
            }
        });
    </script>
@endsection
