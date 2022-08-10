@extends('layouts.base')

@section('judul', 'Home')
@section('judul_konten', 'Halaman Home')

@section('content')
    <div class="container-xxl" id="kt_content_container">
        <!--begin::Row-->
        <div class="row g-5 g-xl-8">
            <!--begin::Col-->
            <div class="col-xxl-4">
                <!--begin::Mixed Widget 5-->
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-body d-flex flex-column flex-center">
                        <!--begin::Heading-->
                        <div class="mb-2">
                            <!--begin::Title-->
                            <h1 class="fw-semibold text-gray-800 text-center lh-lg">Quick form to
                                <br />
                                <span class="fw-bolder">Add New Shipment</span>
                            </h1>
                            <!--end::Title-->
                            <!--begin::Illustration-->
                            <div class="py-10 text-center">
                                <img src="{{ url('assets/media/icons/agency.png') }}" class="w-200px"
                                    alt="" />
                            </div>
                            <!--end::Illustration-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
