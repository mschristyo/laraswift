@extends('layouts.template')

@section('title','Edit Users')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3>{{($user->fullname)? $user->fullname:$user->username}}</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
            <li class="breadcrumb-item"><a href="/user">{{__('app.users')}}</a></li>
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
      <div class="col-md-4">
         @include('layouts.includes.alerts')
        <!-- Profile Image -->
        <div class="card">
          <div class="card-body text-center">
          <div class="row">
            <div class="col-md-12">
                <img class="img profile-user-img img-responsive img-circle" width="40px" height="100px" src="{{($user->avatar)? $user->avatar: asset('uploads/avatar/avatar.png')}}" alt="User profile picture">
              <h5 class="mt-3 mb-0"><b>{{$user->fullname}}</b></h5>
              <p>{{$user->email}}</p>

            </div>
            <div class="col-md-12">
                <span class="mt-2 mb-0 d-block">
                  <p><b>Role:</b>
                        {{($user_role)? ucfirst($user_role->name): 'No role assigned'}}
                  </p>
                </span>
              <span class="mt-0 d-block">
                <p><b>Last Login:</b>
                    {{empty($user->last_login_at)? 'Yet to login': ($user->last_login_at)->diffForHumans() }}
                </p>
              </span>
              <span class="mt-0 d-block">
                <p><b>Status:</b>
                    @if($user->status == 'active')
                      <span class="badge badge-success badge-md"> {{'Active'}}</span>
                    @elseif($user->status == 'banned')
                      <span class="badge badge-danger badge-md"> {{'Banned'}}</span>
                    @endif
                </p>
              </span>
            </div>
          </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item shadow mb-3 mr-2">
                      <a class="nav-link active" id="account-details-tab" data-toggle="tab" href="#account-details" role="tab" aria-controls="account-details" aria-selected="false">{{__('app.account_details')}}</a>
                    </li>
                    <li class="nav-item shadow mb-3 mr-2">
                      <a class="nav-link" id="login-details-tab" data-toggle="tab" href="#login-details" role="tab" aria-controls="login-details" aria-selected="false">{{__('app.login_details')}}</a>
                    </li>
                    <li class="nav-item shadow mb-3 mr-2">
                      <a class="nav-link" id="activiylog-details-tab" data-toggle="tab" href="#activiylog-details" role="tab" aria-controls="activiylog-details" aria-selected="false">{{__('app.activity_logs')}}</a>
                    </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content mt-3">
                    <div class="tab-pane active" id="account-details" role="tabpanel" aria-labelledby="account-details-tab">
                          <form class="form-horizontal" method="POST" action="{{route('user.update',$user->id)}}">
                              @csrf
                              @method('put')
                                  <div class="row form-group">
                                        <div class="col-md-6 mt-1">
                                            <label for="mobile">{{__('app.role')}}</label>
                                            <select name="role" class="form-control form-control-inline-block">
                                              @foreach($roles as $role)
                                                <option value="{{$role->name}}" {{((($user_role)? $user_role->name:'') == $role->name)? 'selected':''}}>{{$role->name}}</option>
                                              @endforeach
                                              @if ($errors->has('role'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('role') }}</strong>
                                                </span>
                                              @endif
                                          </select>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label for="mobile">{{__('app.status')}}</label>
                                            <select name="status" class="form-control form-control-inline-block">
                                                <option value="active" {{($user->status == 'active')? 'selected':''}}>{{'Active'}}</option>
                                                <option value="banned" {{($user->status == 'banned')? 'selected':''}}>{{'Banned'}}</option>
                                              @if ($errors->has('status'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                              @endif
                                          </select>
                                        </div>
                                        <div class="col-md-6 mb-1 mt-1">
                                          <div><label class="label-block">{{__('app.fullname')}}</label></div>
                                          <input type="text" name="fullname" value="{{ $user->fullname }}" class="form-control" placeholder="{{__('app.fullname')}}">
                                            @if ($errors->has('fullname'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('fullname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div id="birthday" class="col-sm-6 mb-1 mt-1">
                                           <label for="name">{{__('app.birthday')}}</label>
                                            <input type="text" name="birthday" value="{{$user->birthday}}" placeholder="{{__('app.birthday')}}" class="form-control">
                                            @error('birthday')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mt-1">
                                          <div><label class="label-block">{{__('app.phone')}}</label></div>
                                          <input type="text" name="phone" value="{{$user->phone}}" class="form-control" placeholder="{{__('app.phone')}}" >
                                          @if ($errors->has('phone'))
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $errors->first('phone') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                        <div class="col-md-6 mt-1">
                                          <label for="mobile">{{__('app.country')}}</label>
                                          <select name="country" class="form-control form-control-inline-block">
                                            @foreach($countries as $key => $country)
                                              <option value="{{$country}}" {{($user->country == $country)? 'selected':''}}>{{$country}}</option>
                                            @endforeach
                                            @if ($errors->has('country'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('country') }}</strong>
                                            </span>
                                        @endif
                                          </select>
                                        </div>

                                    <div class="col-sm-12 mt-1">
                                      <label for="address" class="control-label">{{__('app.address')}}</label>
                                      <input type="text" name="address" class="form-control" value="{{$user->address}}" id="address" placeholder="{{__('app.address')}}">
                                      @if ($errors->has('address'))
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('address') }}</strong>
                                          </span>
                                      @endif
                                    </div>
                                  </div>

                                <div class="col-md-8 mx-auto">
                                  <button type="submit" class="btn btn-primary col-sm-12">{{__('app.update_account')}}</button>
                                </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="login-details" role="tabpanel" aria-labelledby="login-details-tab">
                        <form class="form-horizontal" method="POST" action="{{route('user-login',$user->id)}}">
                          @csrf
                              <div class="row form-group">
                                    <div class="col-md-6">
                                      <div><label class="label-block">{{__('app.email')}}</label></div>
                                      <input type="text" name="email" value="{{$user->email}}" class="form-control" >
                                      @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                      @endif
                                    </div>
                                    <div class="col-md-6">
                                      <div><label class="label-block">{{__('app.username')}}</label></div>
                                      <input type="text" name="username" value="{{$user->username}}" class="form-control" autocomplete="off">
                                      @if ($errors->has('username'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                      @endif
                                    </div>
                                    <div class="col-md-6 my-1">
                                      <div><label class="label-block">{{__('app.password')}}</label></div>
                                      <input type="password" name="password" value="" placeholder="{{__('app.leave_blank')}}" class="form-control" autocomplete="off">
                                      @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                      @endif
                                    </div>
                                    <div class="col-md-6 my-1">
                                      <div><label class="label-block">{{__('app.confirm_password')}}</label></div>
                                      <input type="password" name="password_confirmation" value="" placeholder="{{__('app.leave_blank')}}" class="form-control" autocomplete="off">
                                      @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                      @endif
                                    </div>
                              </div>
                            <div class="col-md-8 mx-auto">
                              <button type="submit" class="btn btn-primary col-sm-12">{{__('app.update_login')}}</button>
                            </div>
                      </form>
                    </div>
                    <div class="tab-pane" id="activiylog-details" role="tabpanel" aria-labelledby="activiylog-details-tab">
                      <div class="row">
                        <div class="col-md-12">
                          <a href="/user/{{$user->id}}/activity-log/" class="btn btn-outline-secondary pull-right">{{__('app.view_all')}}</a>
                        </div>
                        <div class="col-md-12">
                          <div class="table-responsive no-padding">
                        <table class="table table-hover table-striped table-borderless">
                          <thead>
                            <tr>
                              <th class="">{{__('app.activity_log_description')}}</th>
                              <th class="">{{__('app.created_at')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($activities) > 0)
                              @foreach($activities as $activity)
                               <tr>
                                 <td>{{$activity->description}}</td>
                                 <td>{{date('Y-m-d h:i',strtotime($activity->created_at))}}</td>
                               </tr>
                              @endforeach
                            @else
                            <tr>
                              <td colspan="2"> <i><h5 class="text-muted text-center">{{__('app.no_record')}}</h5></i></td>
                            </tr>
                            @endif
                         </tbody>
                         </table>
                       </div>
                        </div>
                      </div>
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
