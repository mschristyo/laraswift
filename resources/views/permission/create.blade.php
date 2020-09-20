@extends('layouts.template')

@section('title','Create Permission')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('app.permissions')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('permission.index')}}">{{__('app.permission')}}</a></li>
              <li class="breadcrumb-item active">{{__('app.create')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
        <!-- right column -->
        <div class="col-md-7 mx-auto">
            @include('layouts.includes.alerts')
            <form class="form-horizontal" method="POST" action="{{route('permission.store')}}">
                  @csrf
                  <!-- Permission Creation -->
                  <div class="card">
                    <div class="card-header with-border">
                      {{__('app.create_permission')}}
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group ">
                          <div class="row">

                            <div class="col-sm-12">
                              <div class="form-group">
                                <input type="text" name="name" class="form-control" id="password" placeholder="{{__('app.name_of_permission')}}">
                                @if ($errors->has('permission'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $errors->first('permission') }}</strong>
                                  </span>
                                @endif
                              </div>
                              <div class="col-sm-8 mx-auto" ><button type="submit" class="btn btn-success text-white btn-outline-secondary col-sm-12">{{__('app.create_permission')}}</button></div>
                            </div>
                              </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- Permission Creation -->
            </form>
            <!-- form end -->
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
