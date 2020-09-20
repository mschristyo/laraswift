@extends('layouts.template')

@section('title','Permission')
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
              <li class="breadcrumb-item active">{{__('app.view')}}</li>
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
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12 mb-3">
                <a href="{{route('permission.create')}}" class="pull-right btn btn-primary">Create Permission</a>
              </div>

              <div class="col-md-12">
              <table id="dataTable" class="table table-hover table-borderless table-striped">
                <thead>
                <tr>
                  <th class="">{{__('app.id')}}</th>
                  <th class="">{{__('app.name')}}</th>
                  <th class="">{{__('app.action')}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($permissions as $permission)
                  <tr>
                    <td>{{$permission->id}}</td>
                    <td>{{$permission->name}}</td>
                    <td>
                      <div class="col-md-12">
                          <div class="row">
                            <div class="mx-1">
                                <a href="{{route('permission.edit',$permission->id)}}"><button class="btn btn-sm btn-info" data-toggle="tooltip"  data-placement="bottom" title="Edit {{__('app.permission')}}"/><i class="fa fa-edit text-white"></i></button></a>
                            </div>
                            @if($permission->removable)
                            <div class="mx-1">
                              <form action="{{route('permission.destroy',$permission->id)}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip"  data-placement="bottom" title="Delete {{__('app.permission')}}"/><i class='fa fa-trash text-white'></i></button>
                              </form>
                            </div>
                            @endif
                          </div>
                      </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
              </table>
                  </div>
              </div>
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
@section('script')
<script src="{{asset('js/datatable.js')}}" charset="utf-8"></script>
@endsection
