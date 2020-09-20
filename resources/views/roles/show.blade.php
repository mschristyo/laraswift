@extends('layouts.template')

@section('title','Show Role')
@section('content')
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
        <!-- right column -->
          <div class="col-md-7 mx-auto">
              @include('layouts.includes.alerts')
              <form class="form-horizontal" method="POST" action="{{route('roles_permit',$role->id)}}">
                  @csrf
                <!-- Role Creation -->
                    <div class="card">
                          <div class="card-header">
                            {{__('app.all_permissions')}}
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                          <div class="form-group ">
                  						@foreach($allpermissions as $allpermission)
                              	<div class="icheck-primary">
                  							  <input  type="checkbox" name="permissions[]" value="{{$allpermission->name}}"  id="role{{$allpermission->id}}"
                                   @foreach($permissions as $permission)
                                    {{($permission->name ==$allpermission->name) ? 'checked' : '' }}
                                   @endforeach
                                   >
                  							  <label for="role{{$allpermission->id}}">
                  								          {{ucfirst($allpermission->name)}}
                  							  </label>
                  							</div>
                              @endforeach
                          </div>
                          <div class="col-sm-8 mx-auto"><button type="submit" class="btn btn-primary col-sm-12">{{__('app.update_permission')}}</button></div>
                      </div>
                        <!-- /.card-body -->
                    </div>
                <!-- Role Creation -->
              </form>
              <!-- form end -->
          </div>
        </div>
        <!--/.col (right) -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
