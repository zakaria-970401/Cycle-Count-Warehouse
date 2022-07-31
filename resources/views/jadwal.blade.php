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
                                                    <a href="javascript:void(0)" onclick="showJadwal('{{$item->upload_at}}')">
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Cycle Count</h5>
                </div>
                <div class="modal-body">
                    Body
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    function showJadwal(tanggal){
        alert(tanggal)
    }
    </script>
@endsection
