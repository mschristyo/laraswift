@extends('layouts.template')

@section('title','Article')
@section('style')
<link rel="stylesheet" type="text/css"  href="{{asset('assets/css/articleStyle.css')}}"></link>
@endsection
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('app.article')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="/article">{{__('app.article')}}</a></li>
              <li class="breadcrumb-item active">{{__('app.show')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          @include('layouts.includes.alerts')
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{strtoupper($article->title)}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <article class="">
                  {!!$article->content!!}
              </article>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
