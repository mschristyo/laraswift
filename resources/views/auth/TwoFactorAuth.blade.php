<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{_('Two Factor Authentication')}} - @setting('app_name')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- App Stylesheet -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<script src="{{asset('plugins/sweetalert/js/sweetalert.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('plugins/fontawesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/icheck/skin/all.css')}}">

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <div class=" d-block text-center mt-5">
      <img src="{{setting('app_dark_logo')? setting('app_dark_logo'):asset('uploads/appLogo/logo-dark.png')}}" class="img img-responsive" height="60px" width="220px" alt="App Logo">
    </div>
  </div>
  <!-- /.login-logo -->

  <div class="card">

    @include('layouts.includes.alerts')
  <div class="card-body text-center">
    <h5 class="text-center">{{ __('app.app_two_factor_auth') }}</h5>
    <p class="login-box-msg">{{ __('app.enter_verification_code') }}</p>

    <form method="POST" action="{{ route('verify-2fa') }}">
        @csrf
      <div class="form-group has-feedback">
        <input id="login" type="text" placeholder="{{ __('app.enter_verification_code') }}" class="form-control" name="one_time_password" autofocus>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('one_time_password'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('one_time_password')}}</strong>
            </span>
        @endif
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block">{{ __('app.verify_code') }}</button>
      </div>
        <!-- /.col -->
    </form>

    <p class="mt-3">
      <a href="{{route('logout')}}">{{ __('app.logout') }}</a>
    </p>
  </div>
  <!-- /.login-card-body -->
</div>

</div>
<!-- /.login-card -->

<!-- App Script -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/theme.min.js') }}"></script>
</body>
</html>
