@extends('layouts.base')

@section('judul', 'Jadwal')
@section('judul_konten', 'Halaman Jadwal Cycle Count')

@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }
    </style>
    <div class="container-xxl" id="kt_content_container">
        <div class="row gy-5 g-xl-8">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($data as $item)
                                <div class="col-xl-4">
                                    <div class="card card-xl-stretch mb-5 mb-xl-0 bg-light-info mt-4"
                                        style="border-radius: 14px;">
                                        <div class="card-body d-flex flex-column">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center pe-2 mb-5">
                                                    <a href="javascript:void(0)"
                                                        onclick="showJadwal('{{ $item->upload_at }}')">
                                                        <span
                                                            class="text-danger fw-bold fs-5 flex-grow-1">{{ \Carbon\Carbon::parse($item->upload_at)->format('d M Y') }}</span>
                                                    </a>
                                                    {{-- <div class="symbol symbol-50px">
                                                        <span class="symbol-label bg-light">
                                                            <a href="" class="btn btn-md btn-dark"><i
                                                                    class="fas fa-eye"></i></a>
                                                        </span>
                                                    </div> --}}
                                                </div>
                                                {{-- <div class="d-flex align-items-center">
                                                    <a href="#" class="symbol symbol-35px me-2"
                                                        data-bs-toggle="tooltip" title="Ana Stone">
                                                        <img src="{{ asset('assets/media/avatars/300-6.jpg') }}"
                                                            alt="" />
                                                    </a>
                                                    <a href="#" class="symbol symbol-35px me-2"
                                                        data-bs-toggle="tooltip" title="Mark Larson">
                                                        <img src="{{ asset('assets/media/avatars/300-5.jpg') }}"
                                                            alt="" />
                                                    </a>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Cycle Count</h5>
                </div>
                <div class="modal-body">
                    <div class="row appendKonten"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function showJadwal(tanggal) {
            $.ajax({
                url: "{{ url('cycle-count/showJadwal') }}/" + tanggal,
                type: 'GET',
                dataType: 'json',
                data: {
                    tanggal: tanggal
                },
                success: function(response) {
                    $('.appendKonten').html('');
                    $('#detail').modal('show');
                    $('.appendKonten').append(`<div class="table table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="text-center text-bold">
                                            <th>NO</th>
                                            <th>COUNT BY</th>
                                            <th>MID</th>
                                            <th>DESCRIPTION</th>
                                            <th>BLOK</th>
                                            <th>CASE QTY</th>
                                            <th class="bg-warning text-dark">QTY LAPANGAN</th>
                                            <th class="bg-warning text-dark">QTY VALIDASI</th>
                                            <th class="bg-warning text-dark">REASON</th>
                                            <th class="">STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyTable">
                                    </tbody>
                                    </table>
                            </div>`);

                    $('#bodyTable').html("");
                    $.each(response.data, function(index, item) {
                        if (item.status == 0) {
                            var status =
                                '<span class="badge badge-success"> Selesai Perhitungan</span>';
                        } else if (item.status == 1) {
                            var status = '<span class="badge badge-dark"> Belum Di Hitung</span>';
                        } else {
                            var status =
                                '<span class="badge badge-danger"> Proses Revisi Hitungan</span>';
                        }
                        $('#bodyTable').append(`<tr>
                                                <td>${index + 1}</td>
                                                <td>${item.material}</td>
                                                <td>${item.material}</td>
                                                <td>${item.description}</td>
                                                <td>${item.blok}</td>
                                                <td>${item.case_qty}</td>
                                                <td class="bg-warning text-dark">${item.qty_lapangan}</td>
                                                <td class="bg-warning text-dark">${item.qty_validasi}</td>
                                                <td class="bg-warning text-dark">${item.reason}</td>
                                                <td>${status}</td>
                                            </tr>`);
                    });
                }
            });
        }
    </script>
@endsection
