<!DOCTYPE html>
<html lang="en">

<head>
    <base href="">
    <title>@yield('judul')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ url('assets/plugins/custom/leaflet/leaflet.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css" />
    {{-- <link href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap5.min.css" rel="stylesheet"> --}}
    {{-- <link href="//cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap5.min.css" rel="stylesheet"> --}}

    <script src="{{ url('assets/js/jquery.js') }}"></script>


</head>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-disabled">
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div id="kt_aside" class="aside bg-dark" data-kt-drawer="true" data-kt-drawer-name="aside"
                data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
                <div class="aside-logo d-none d-lg-flex flex-column align-items-center flex-column-auto py-8"
                    id="kt_aside_logo">
                    <a href="{{ url('/') }}">
                        <img alt="Logo" src="{{ asset('assets/media/icons/logo.png') }}" class="h-85px" />
                    </a>
                </div>
                <div class="aside-nav d-flex flex-column align-lg-center flex-column-fluid w-100 pt-5 pt-lg-0"
                    id="kt_aside_nav">
                    <div id="kt_aside_menu"
                        class="menu menu-column menu-title-gray-600 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-bold fs-6"
                        data-kt-menu="true">
                        @include('layouts.sidebar')
                    </div>
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" style="" class="header bg-white align-items-stretch">
                    <!--begin::Container-->
                    <div class="container-fluid d-flex align-items-stretch justify-content-between">
                        <!--begin::Aside mobile toggle-->
                        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
                            <div class="btn btn-icon btn-active-color-primary w-40px h-40px" id="kt_aside_toggle">
                                <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                            fill="black" />
                                        <path opacity="0.3"
                                            d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                        </div>
                        <!--end::Aside mobile toggle-->
                        <!--begin::Mobile logo-->
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                            <h3>
                                {{-- <a href="#" class="d-lg-none">
                                    <span class="badge badge-danger time"></span>
                                </a> --}}
                            </h3>
                        </div>
                        <div class="d-flex align-items-center" id="kt_header_wrapper">
                            <div
                                class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-20 pb-5 pb-lg-0">
                                <h1 class="text-dark fw-bold my-1 fs-3 lh-1">@yield('judul_konten')</h1>
                            </div>
                        </div>
                        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                            <!--begin::Navbar-->
                            <div class="d-flex align-items-stretch" id="kt_header_nav">

                            </div>
                            <!--end::Navbar-->
                            <!--begin::Toolbar wrapper-->
                            <div class="d-flex align-items-stretch justify-self-end flex-shrink-0">
                                <!--begin::Search-->
                                <div class="d-flex align-items-stretch ms-1 ms-lg-3">
                                    <!--begin::Search-->

                                    <!--end::Search-->
                                </div>
                                <!--end::Search-->
                                <!--begin::Activities-->
                                <div class="d-flex align-items-center ms-1 ms-lg-3">
                                    <!--begin::drawer toggle-->
                                    <!--end::drawer toggle-->
                                </div>
                                <!--end::Activities-->
                                <!--begin::Notifications-->
                                <div class="d-flex align-items-center ms-1 ms-lg-3">

                                </div>
                                <!--end::Notifications-->
                                <!--begin::Chat-->
                                <div class="d-flex align-items-center ms-1 ms-lg-3">
                                    {{-- <h1 class="text-dark fw-bolder my-1 fs-3 lh-1 date mr-4 ml-4"></h1> --}}
                                    {{-- <h1 class="ml-4 mr-4"><b class="">|</b></h1> --}}
                                </div>
                                <!--end::Chat-->
                                <!--begin::Quick links-->
                                <div class="d-flex align-items-center ms-1 ms-lg-1">
                                    {{-- <h1 class="text-dark fw-bolder my-1 fs-3 lh-1 date mr-4 ml-4 time"></h1> --}}
                                </div>
                                <!--end::Quick links-->
                                <!--begin::Theme mode-->
                                <div class="d-flex align-items-center ms-1 ms-lg-3">
                                    <span class="badge badge-primary">{{ Auth::user()->name }}</span>
                                </div>
                                <!--end::Theme mode-->
                                <!--begin::User-->
                                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                                    <!--begin::Menu wrapper-->
                                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
                                        data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                                        data-kt-menu-placement="bottom-end">
                                        <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="user" />
                                    </div>
                                    <!--begin::User account menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <!--begin::Avatar-->
                                                <div class="symbol symbol-50px me-5">
                                                    <img alt="Logo"
                                                        src="{{ asset('assets/media/avatars/blank.png') }}" />
                                                </div>
                                                <!--end::Avatar-->
                                                <!--begin::Username-->
                                                <div class="d-flex flex-column">
                                                    <div class="fw-bolder d-flex align-items-center fs-5">
                                                        {{ Auth::user()->name }}
                                                        {{-- <span
                                                            class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Pro</span> --}}
                                                    </div>
                                                    <a href="#"
                                                        class="fw-bold text-muted text-hover-primary fs-7">{{ Auth::user()->username }}</a>
                                                </div>
                                                <!--end::Username-->
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu separator-->
                                        <div class="separator my-2"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-5">
                                            <a href="#ganti-pw" data-bs-toggle="modal" class="menu-link px-5">Ganti Password</a>
                                        </div>
                                        <div class="menu-item px-5">
                                            <hr>
                                            <a href="#" onclick="postLogout()" class="menu-link px-5">Sign
                                                Out</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::User account menu-->
                                    <!--end::Menu wrapper-->
                                </div>
                                <!--end::User -->
                                <!--begin::Heaeder menu toggle-->
                                <div class="d-flex align-items-center d-lg-none ms-3 me-n1" title="Show header menu">
                                    <div class="btn btn-icon btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                                        id="kt_header_menu_mobile_toggle">
                                        <!--begin::Svg Icon | path: icons/duotune/text/txt001.svg-->
                                        <span class="svg-icon svg-icon-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z"
                                                    fill="black" />
                                                <path opacity="0.3"
                                                    d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </div>
                                </div>
                                <!--end::Heaeder menu toggle-->
                            </div>
                            <!--end::Toolbar wrapper-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->

                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Container-->
                    <div class="container-xxl" id="kt_content_container">
                        @yield('content')
                    </div>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>

        <!-- Modal -->
        <div class="modal fade" id="ganti-pw" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Ganti Password</h5>
                    </div>
                    <form action="{{url('change-password')}}" method="post" id="change-password">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                  <input type="password" name="password" id="" class="form-control Password" placeholder="Masukan Password Baru" aria-describedby="helpId" required>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-4">
                                <div class="form-group">
                                  <input type="password" name="password_konfirm" id="" class="form-control Password" placeholder="Konfirmasi Password" aria-describedby="helpId" required>
                                </div>
                                <input type="checkbox" class="ml-4 mr-4 mt-4" id="showPass"> Show Password
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"> Update</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <script>
            var hostUrl = "{{ url('/') }}";
        </script>
        <script src="{{ url('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ url('assets/js/scripts.bundle.js') }}"></script>
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        {{-- <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap5.min.js"></script> --}}
        <script src="//cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
        <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap4.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
        <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
        {{-- <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script> --}}


        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#showPass').on('click', function(){
            var passInput=$(".Password");
            if(passInput.attr('type')==='password')
                {
                passInput.attr('type','text');
            }else{
                passInput.attr('type','password');
            }
        });

            $('#change-password').on('submit', function(e){
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var data = form.serialize();
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        if(response.status == 'gagal'){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Password tidak sama!',
                            });
                        }else if(response.status == 'kurang'){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Password minimal 6 karakter!',
                            });
                            // toastr.error(data.message);
                        }else{
                            toastr.success('Password berhasil diubah');
                            location.reload();
                        }
                    }
                });
            });

            function kasihNol($data) {
                if ($data < 10) {
                    return '0' + $data;
                } else {
                    return $data;
                }
            }

            function postLogout() {
                $.ajax({
                    url: "{{ route('logout') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        window.location.href = "{{ url('/') }}";
                    }
                });
            }



            function formatTanggalIndonesia(tanggal) {
                const today = new Date(tanggal);
                return kasihNol(today.getDate()) + '/' + kasihNol((today.getMonth() + 1)) + '/' + kasihNol(today.getFullYear());
            }

            function numberFormat(number) {
                return (Math.round(number) || "")
                    .toString()
                    .replace(/^0|\./g, "")
                    .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
            }

            function formatTanggalIndonesia2(tanggal) {
                var formated;
                const today = new Date(tanggal);
                const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ];
                formated = kasihNol(today.getDate()) + ' ' + bulan[today.getMonth()] + ' ' + kasihNol(today.getFullYear());

                if (tanggal == null || tanggal == '') {
                    formated = '';
                }

                return formated;
            }

            function formatTanggalWaktuIndonesia2(tanggal) {
                var formated;
                const today = new Date(tanggal);
                const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ];
                formated = kasihNol(today.getDate()) + ' ' + bulan[today.getMonth()] + ' ' + kasihNol(today.getFullYear()) +
                    ' ' + kasihNol(today.getHours()) + ':' + kasihNol(today.getMinutes()) + ':' + kasihNol(today.getSeconds());

                if (tanggal == null || tanggal == '') {
                    formated = '';
                }

                return formated;
            }

            function get_time() {
                const today = new Date();
                const time = kasihNol(today.getHours()) + ":" + kasihNol(today.getMinutes()) + ":" + kasihNol(today
                    .getSeconds());
                const date = kasihNol(today.getDate()) + '/' + kasihNol((today.getMonth() + 1)) + '/' + kasihNol(today
                    .getFullYear());
                const tanggal = formatTanggalIndonesia2(today);
                $('.date').text(tanggal);
                $('.time').text(time + ' WIB');
            }

            get_time();

            setInterval(function() {
                get_time();
            }, 1000);
        </script>
</body>
<!--end::Body-->

</html>
