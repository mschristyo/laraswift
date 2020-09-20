<!DOCTYPE html>
<html>
<head>
      <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{_('Registration')}} - @setting('app_name')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if(setting('recaptcha'))
      {!! htmlScriptTagJsApi([
              'action' => 'registration',])
      !!}
  @endif
  {!! NoCaptcha::renderJs() !!}

  <!-- FAVICON -->
  <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
  <link rel="manifest" href="favicon/site.webmanifest">
  <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
  <!-- FAVICON -->

  <!-- STYLESHEET -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  <script src="{{asset('plugins/sweetalert/js/sweetalert.min.js')}}"></script>
  <link rel="stylesheet" href="{{asset('plugins/fontawesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/icheck/skin/all.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datepicker/css/bootstrap-datepicker.standalone.css')}}">

  <style>
     body{
      background: aliceblue!important;
    }

    .twitter-blue{
      color: #00acee;
    }
  </style>

</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
      <div class=" d-block text-center mt-5">
        <a href="./">
          <img src="{{setting('app_dark_logo')? setting('app_dark_logo'):asset('uploads/appLogo/logo-dark.png')}}" class="img img-responsive" height="60px" width="220px" alt="App Logo">
        </a>
      </div>
    </div>

    <div class="card shadow px-3">
      <div class="card-body register-card-body rounded">
        @include('layouts.includes.alerts')
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif
        <p class="login-box-msg">{{ __('app.create_new_account') }}</p>

        <!-- social-auth-links -->
        <div class="row mb-1">
          <div class="col-sm-12 my-3 text-center">
            <a href="login/facebook" class="mx-4">
              <i class="fa fa-facebook fa-2x text-primary"></i></a>

            <a href="login/twitter" class="mx-4">
              <i class="fa fa-twitter fa-2x twitter-blue"></i></a>

            <a href="login/google" class="mx-4">
              <i class="fa fa-google fa-2x text-danger"></i></a>
          </div>
        </div>
        <!-- /.social-auth-links -->


      <form method="POST" action="{{ route('register') }}">
          @csrf
        <div class="form-group has-feedback">
          <!-- Fullname -->
          <input id="fullname" type="text" placeholder="{{ __('app.fullname') }}" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname') }}" required autocomplete="fullname" autofocus>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
          @error('fullname')
              <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <!-- Fullname -->

        <!-- Username -->
        <div class="form-group has-feedback">
              <input id="username" type="text" placeholder="{{ __('app.username') }}" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username">
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
              @error('username')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
        </div>
        <!-- Username -->

        <!-- Email -->
        <div class="form-group has-feedback">
              <input id="email" type="email" placeholder="{{ __('app.email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
              <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
        </div>
        <!-- Email -->

        <!-- Password -->
        <div class="form-group has-feedback">
              <input id="password" type="password" placeholder="{{ __('app.password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
        </div>
        <!-- Password -->

        <!-- Password-Confirmation -->
        <div class="form-group has-feedback">
          <input id="password-confirm" type="password" placeholder="{{ __('app.confirm_password') }}" class="form-control" name="password_confirmation" required autocomplete="new-password">
          <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        <!-- Password-Confirmation -->
        @if(setting('captcha'))
          <div class="form-group has-feedback">
            {!! NoCaptcha::display() !!}
            @if ($errors->has('g-recaptcha-response'))
                <span class="help-block text-danger" role="alert">
                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                </span>
            @endif
          </div>
        @endif
        <!-- Submit Button -->
        <div class="row">
          <div class="col-md-12">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               {{ __('app.i_agree_to_the') }} <a href="#">{{ __('app.terms') }}</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
        <!-- Submit Button -->
      </form>

      <a href="{{route('login')}}" class="text-center">I already have an account</a>
    </div>
    <!-- /.form-box -->
    </div>
</div>
<!-- /.register-box -->


<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/theme.min.js') }}"></script>
<script src="{{asset('plugins/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('plugins/croppie/js/croppie.min.js')}}"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>
</body>
</html>
