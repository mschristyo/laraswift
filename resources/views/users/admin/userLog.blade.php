@extends('layouts.template')

@section('title','Activity Logs')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3> {{($user->firstname)? $user->firstname:$user->email}} - Activity Logs</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="/user">{{__('app.users')}}</a></li>
              <li class="breadcrumb-item active">{{__('app.activity-log')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12">
              <div class="card">
                 <div class="card-body">
                   <div class="row">
                     <div class="col-md-12 mb-3">
                       <div class="table-responsive no-padding">
                         <table id="dataTable" class="table table-hover table-striped table-borderless">
                           <thead>
                             <tr>
                               <th class="">{{__('app.activity_log_description')}}</th>
                               <th class="">{{__('app.created_at')}}</th>
                             </tr>
                           </thead>
                           <tbody>
                            @foreach($activities as $activity)
                             <tr>
                               <td>{{$activity->description}}</td>
                               <td>{{date('Y-m-d h:i',strtotime($activity->created_at))}}</td>
                             </tr>
                            @endforeach
                          </tbody>
                          </table>
                        </div>
                     </div>
                   </div>
                  </div>
                  <!-- /.card-footer -->
                </div>
              </div>
          </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
