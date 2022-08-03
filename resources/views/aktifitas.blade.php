@extends('layouts.base')

@section('judul', 'Aktifitas')
@section('judul_konten', 'Halaman Aktifitas Cycle Count')


@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }
    </style>
    <div class="container-xxl" id="kt_content_container">
        <div class="row gy-5 g-xl-8">
            <div class="col-xl-6">
                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header align-items-center border-0 mt-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="fw-bold mb-2 text-dark">Aktifitas Admin</span>
                            <span class="text-muted fw-semibold fs-7">Tanggal
                                {{ date('d M Y') }}</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        @foreach ($data->where('type', 'admin') as $item)
                            <div class="timeline-label mt-4">
                                <div class="timeline-item mt-4">
                                    <div class="timeline-label fw-bold text-gray-800 fs-6 mr-4 mt-4">
                                        @php
                                            $jam = explode(' ', $item->created_at)[1];
                                            $jam = substr($jam, 0, -3);
                                        @endphp
                                        {{ $jam }}</div>
                                    <div class="timeline-badge ml-4 mt-4">
                                        <i class="fa fa-genderless text-warning fs-1"></i>
                                    </div>
                                    <div class="fw-mormal timeline-content text-dark ps-3"><b>{{ $item->konten }}</b>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header align-items-center border-0 mt-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="fw-bold mb-2 text-dark">Aktifitas Gudang</span>
                            <span class="text-muted fw-semibold fs-7">Tanggal
                                {{ date('d M Y') }}</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        @foreach ($data->where('type', 'gudang') as $item)
                            <div class="timeline-label">
                                <div class="timeline-item">
                                    <div class="timeline-label fw-bold text-gray-800 fs-6 mr-4">
                                        @php
                                            $jam = explode(' ', $item->created_at)[1];
                                            $jam = substr($jam, 0, -3);
                                        @endphp
                                        {{ $jam }}</div>
                                    <div class="timeline-badge ml-4">
                                        <i class="fa fa-genderless text-warning fs-1"></i>
                                    </div>
                                    <div class="fw-mormal timeline-content text-dark ps-3"><b>{{ $item->konten }}</b>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
