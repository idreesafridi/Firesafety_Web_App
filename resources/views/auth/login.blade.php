<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from demo.ninjateam.org/html/ninja-admin/light/page-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 24 Jun 2020 12:44:15 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('assets/styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/custom.css') }}">
    <!-- Waves Effect -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/waves/waves.min.css') }}">
</head>

<body>
    <div id="single-wrapper">
        <form method="POST" action="{{ route('login') }}" class="frm-single">
        @csrf
            <div class="inside">
                <div class="title"><strong>UFP</strong></div>
                <!-- /.title -->
                <div class="frm-title">Login</div>

                <!-- /.frm-title -->
                <div class="frm-input">
                    <input id="email" type="email" class="frm-inp form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <i class="fa fa-user frm-ico"></i>
                </div>

                <!-- /.frm-input -->
                <div class="frm-input">
                    <input id="password" type="password" class="frm-inp form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <i class="fa fa-lock frm-ico"></i>
                </div>
                <!-- /.frm-input -->


                <div class="clearfix margin-bottom-20">
                    <div class="float-left">
                        <div class="checkbox primary">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                        <!-- /.checkbox -->
                    </div>
                    <!-- /.float-left -->
                    <div class="float-right">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link a-link" href="{{ route('password.request') }}">
                                <i class="fa fa-unlock-alt"></i> {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                    <!-- /.float-right -->
                </div>
                <!-- /.clearfix -->
                <button type="submit" class="frm-submit">Login<i class="fa fa-arrow-circle-right"></i></button>
                <!-- /.footer -->
            </div>
            <!-- .inside -->
        </form>
        <!-- /.frm-single -->
    </div>

    <script src="{{ asset('assets/scripts/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/modernizr.min.js') }}"></script>
    <script src="../../../../cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/plugin/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/nprogress/nprogress.js') }}"></script>
    <script src="{{ asset('assets/plugin/waves/waves.min.js') }}"></script>

    <script src="{{ asset('assets/scripts/main.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/mycommon.js') }}"></script>
</body>

</html>