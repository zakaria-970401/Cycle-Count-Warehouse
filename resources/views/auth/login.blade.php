<!DOCTYPE html>

<html lang="en">
	<head>
		<title>FORM LOGIN</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="shortcut icon" href="/metronic8/demo4/assets/media/logos/favicon.ico" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<link href="{{url('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{url('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
	</head>
	<body data-kt-name="metronic" id="kt_body" class="bg-dark bgi-size-cover bgi-position-center bgi-no-repeat">
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-lg-row-fluid">
					<div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
						<img class="theme-light-show  mx-auto mw-100 w-150px w-lg-800px mb-2 mb-lg-5" src="{{asset('assets/media/icons/login.jpg')}}" style="border-radius: 15px;" />
						<h1 class="text-white fs-2qx fw-bold text-center mb-3">Fast, Efficient and Productive</h1>
						<div class="text-white fs-base text-center fw-semibold">Future Synthetics Pte Ltd adalah salah satu produsen sling tali kawat sintetis dan baja terbesar di dunia dengan fasilitas rigging di Singapura (HQ), Malaysia, Uni Emirat Arab dan Indonesia. Misi kami adalah membangun sling sintetis dan baja terbesar yang dibutuhkan industri angkat berat dunia; dengan standar kualitas tertinggi; serta menjadi penyedia solusi tepercaya di industri mana pun yang membutuhkan layanan dan produk kami terkait dengan kebutuhan rigging, lifting, dan mooring mereka.
						{{-- <br />and provides some background information about 
						and their 
						<br />work following this is a transcript of the interview.</div> --}}
					</div>
				</div>
				<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
					<div class="bg-gray-100 d-flex flex-center rounded-4 w-md-600px p-10">
						<div class="w-md-400px">
							<form class="form w-100" id="login" action="{{ route('login') }}">
                                @csrf
                                @guest
								<div class="text-center mb-11">
									<h1 class="text-dark fw-bolder mb-3">FORM LOGIN</h1>
								</div>
								<div class="row g-3 mb-9">
                                    <div class="fv-row mb-8">
                                        <input type="text" required placeholder="Username" name="username" autocomplete="off" class="form-control bg-transparent" autofocus />
                                    </div>
                                    <div class="fv-row mb-3">
                                        <input type="password" required placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" id="passInput" />
                                        <input type="checkbox" class="ml-4 mr-4 mt-4" id="showPass"> Show Password
                                    </div>
								</div>
								<div class="d-grid mb-10">
									<button type="submit"class="btn btn-primary btnSave">
                                        <span class="indicator-label">Sign In</span>
									</button>
                                    @endguest
                                    @auth
                                    <script>
                                        window.location.href = "{{url('/home')}}";
                                    </script>
                                    @endauth
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="{{url('assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{url('assets/js/scripts.bundle.js')}}"></script>
        <script type="text/javascript">

        $('#showPass').on('click', function(){
            var passInput=$("#passInput");
            if(passInput.attr('type')==='password')
                {
                passInput.attr('type','text');
            }else{
                passInput.attr('type','password');
            }
        });

          $("#login").submit( function (e) {
            e.preventDefault();
            $('.btnSave').attr('disabled', 'true');
            $('.btnSave').addClass('spinner spinner-left pl-15')
            $.ajax({
                url: "{{ route('login') }}",
                type: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success == 1)
                    {
                        window.location.href = "{{url('/home')}}";
                    }
                },
                error: function(error) {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Username Atau Password Salah',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    $('.btnSave').removeAttr('disabled');
                    $('.btnSave').removeClass('spinner spinner-left pl-15');
                }
            });
        });
        </script>
	</body>
</html>

{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
