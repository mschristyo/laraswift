@extends('layouts.template')

@section('title','Users')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{__('app.profile')}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('user.index')}}">{{__('app.users.users')}}</a></li>
            <li class="breadcrumb-item active">{{__('app.profile')}}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
         @include('layouts.includes.alerts')
        <!-- Profile Image -->
        <div class="card">
          <div class="card-body text-center">
            <img class="img profile-user-img img-responsive img-circle" src="{{$user->avatar}}" alt="User profile picture">
            <h5 class="mt-3 mb-0"><b>{{$user->fullname}}</b></h5>
            <p>{{$user->email}}</p>

            <span class="mt-5 mb-0 d-block">
              <p>
                <b>{{__('app.role')}}:</b>
                @if(empty($role->name))
                    {{'Role not assigned to user yet'}}
                @else
                    {{ucfirst($role->name)}}
                @endif
              </p>
            </span>
            <span class="mt-0 d-block"><p><b>{{__('app.joined')}}:</b>
                  {{$user->created_at}}
            </p></span>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

      </div>
      <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#activity-log" role="tab" aria-controls="activity-log" aria-selected="true">{{__('app.activity_log')}}</a>
                  </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#account-detail" role="tab" aria-controls="account-detail" aria-selected="false">{{__('app.account_details')}}</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#contact-detail" role="tab" aria-controls="contact-detail" aria-selected="false">{{__('app.contact_details')}}</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="messages-tab" data-toggle="tab" href="#role-detail" role="tab" aria-controls="role-detail" aria-selected="false">{{__('app.role_details')}}</a>
                    </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content mt-3">
                    <div class="tab-pane active" id="activity-log" role="tabpanel" aria-labelledby="activity-log-tab">
                      <div class="table-responsive no-padding">
                        <table id="datatable" class="table table-hover table-striped" >
                            <tr>
                              <th>{{__('app.name')}}</th>
                              <th>{{__('app.activity_log_description')}}</th>
                              <th>{{__('app.created_at')}}</th>
                            </tr>
                            @foreach($activities as $activity)
                                  <tr>
                                    <td>{{$activity->properties['name']}}</td>
                                    <td>{{$activity->description}}</td>
                                    <td>@datetime($activity->created_at)</td>
                                  </tr>
                            @endforeach
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane" id="account-detail" role="tabpanel" aria-labelledby="account-detail-tab">
                      <div class="table-responsive no-padding">
                        <table id="datatable" class="table table-hover table-striped" >
                            <tr>
                              <th>{{__('app.name')}}</th>
                              <th>{{__('app.activity_log_description')}}</th>
                              <th>{{__('app.created_at')}}</th>
                            </tr>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane" id="contact-detail" role="tabpanel" aria-labelledby="contact-detail-tab">
                      <form class="form-horizontal" method="POST" action="{{route('update-contact',$user->id)}}">
                          @csrf
                          <div class="card-body">
                              <div class="row">
                                    <div class="col-md-12">
                                      <div><label class="label-block">{{__('app.phone')}}</label></div>
                                      <input type="tel" id="phone" name="phone" value="{{ $user->phone }}" class="form-control" >
                                      @if ($errors->has('phone'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                    </div>

                                    <div class="col-md-12">
                                      <label for="mobile">{{__('app.country')}}</label>
                                      <select id="country" name="country" class="form-control form-control-inline-block col-md-6">
                                        @if ($errors->has('country'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                      </select>
                                    </div>

                                <div class="col-sm-12">
                                  <label for="address" class="control-label">{{__('app.address')}}</label>
                                  <input type="text" name="address" class="form-control" value="{{$user->address}}" id="address" placeholder="{{__('app.address')}}">
                                  @if ($errors->has('address'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('address') }}</strong>
                                      </span>
                                  @endif
                                </div>
                              </div>
                          </div>
                            <div class="col-md-8 mx-auto">
                              <button type="submit" class="btn btn-primary col-sm-12">{{__('app.submit')}}</button>
                            </div>
                    </form>
                    </div>
                  </div>
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
