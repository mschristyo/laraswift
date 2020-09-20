@extends('layouts.template')

@section('title','Edit Role')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ucfirst($role->name)}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('role.index')}}">{{__('app.role')}}</a></li>
              <li class="breadcrumb-item active">{{__('app.edit')}}</li>
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
            <form class="form-horizontal" method="POST" action="{{route('role.update',$role->id)}}">
                @csrf
                @method('PUT')
              <!-- Role Creation -->
                  <div class="card">
                    <div class="card-header with-border">
                      {{__('app.edit_role')}}
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="form-group">
                          <div  class="col-md-12 text-center">
                              <input type="text" value="{{$role->name}}"name="name" class="form-control" id="password" placeholder="{{__('app.name_of_role')}}">
                              @if ($errors->has('role'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('role') }}</strong>
                                </span>
                              @endif
                          </div>
                        </div>
                        <div class="col-sm-8 mx-auto"><button type="submit" class="btn btn-success col-sm-12">{{__('app.update_role')}}</button></div>
                    </div>
                      <!-- /.card-body -->
                  </div>
              <!-- Role Creation -->
            </form>
            <!-- form end -->
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
