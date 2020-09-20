<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title','CRUD') - @setting('app_name')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/screenLoader.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/fontawesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck/skin/all.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/crud-style.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/datepicker/css/bootstrap-datepicker.standalone.css')}}">
    <script src="{{asset('plugins/sweetalert/js/sweetalert.min.js')}}"></script>
    @yield('css')

    <!-- FAVICON -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicon/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon/favicon-16x16.png')}}">
  <link rel="manifest" href="{{asset('favicon/site.webmanifest')}}">
  <link rel="mask-icon" href="{{asset('favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
  <!-- FAVICON -->
  @yield('style')
  <style>
    :root{
      --theme: {{setting("app_navbar")}}
    }
    .navbar {
      background-color: var(--theme);
    }
  </style>

</head>
<body class="hold-transition sidebar-mini">
  @include('sweet::alert')
  <!-- Preloader -->
  <div class="payment-loader">
    <div class="loader-pendulums"></div>
  </div>
  <!-- /Preloader -->
<div class="wrapper">
@if(!Auth::guest())
  @include('layouts.sidebar')

  <div class="content-wrapper">
    @yield('content')
  </div>

  <footer class="main-footer text-center">
    <strong>Copyright &copy; {{date('Y')}} <a href="{{env('APP_URL')}}">{{setting('app_name')}}</a>.</strong> All rights
    reserved.
  </footer>
</div>
@else
<meta http-equiv="refresh" content="0; URL='/login'"/>
@endif
<!-- ./wrapper -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/theme.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap4-toggle/js/bootstrap4-toggle.min.js') }}"></script>
<script src="{{asset('plugins/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
@yield('script')
@stack('scripts')
</body>
</html>
