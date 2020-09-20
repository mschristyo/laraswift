@extends('layouts.template')

@section('title','Profile')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
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
              <div class="row">
                <div id="avatar-holder" class="col-md-12">
                  <img id="avatar-img" width="40px" height="100px" class="img profile-user-img img-responsive img-circle" src="{{$user->avatar? $user->avatar :asset('uploads/avatar/avatar.png')}}" alt="User profile picture">
                  <h5 class="mt-3 mb-0"><b>{{$user->fullname}}</b></h5>
                  <p>{{$user->email}}</p>
                  <span class="mt-5 mb-0 d-block">
                    <p>
                      <b>Role:</b>
                      {{($role)? $role->name:'Role Not Set'}}
                    </p>
                  </span>
                  <span class="mt-0 d-block"><p><b>Joined:</b>
                        {{$user->created_at}}
                  </p></span>

                  <label class="btn btn-secondary btn-lg d-block mx-auto mt-5 col-sm-12 mb-0" for="avatarCrop">
                      {{__('app.update_avatar')}}
                      <input type="file" class="d-none"  id="avatarCrop">
                  </label>
                </div>
                <div id="avatar-updater" class="col-xs-12 d-none">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="image-preview"></div>
                    </div>
                    <div class="col-md-12">
                      <input type="text" name="avatar-url" class="d-none" value="{{route('update-avatar',Auth::user()->id)}}">
                      <button type="button"  id="rotate-image" class="btn btn-info col-sm-12 mb-1">{{__('app.rotate_image')}}</button>
                      <button type="button"  id="crop_image" class="btn btn-primary col-sm-12">{{__('app.crop_image')}}</button>
                      <button type="button" id="avatar-cancel-btn" name="button" class="btn btn-secondary col-sm-12 mt-1">{{__('app.cancel')}}</button>
                    </div>
                  </div>
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
                      @if(setting('2fa'))
                      <li class="nav-item shadow mb-3 mr-2">
                        <a class="nav-link" id="tfa-settings-tab" data-toggle="tab" href="#tfa-settings" role="tab" aria-controls="tfa-settings" aria-selected="false">{{__('app.two_factor_auth')}}</a>
                      </li>
                      @endif
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content mt-3 mx-0">
                        <div class="tab-pane active" id="account-details" role="tabpanel" aria-labelledby="account-details-tab">
                              <form class="form-horizontal" method="POST" action="{{route('profile.update',$user->id)}}">
                                  @csrf
                                  @method('put')
                                  <div class="row form-group">
                                        <div class="col-md-6 mt-1">
                                          <div><label class="label-block">{{__('app.role')}}</label></div>
                                          <input type="text" name="role" value="{{($role)? $role->name:'Role Not Set'}}" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                          <div><label class="label-block">{{__('app.status')}}</label></div>
                                          <select class="form-control" name="status" disabled>
                                            <option value="active" {{($user->status == 'active')? 'SELECTED':''}}>{{$user->status}}</option>
                                            <option value="active" {{($user->status == 'banned')? 'SELECTED':''}}>{{$user->status}}</option>
                                          </select>
                                        </div>
                                        <div class="col-md-6 mb-1 mt-2">
                                          <div><label class="label-block">{{__('app.fullname')}}</label></div>
                                          <input type="text" name="fullname" value="{{ $user->fullname }}" class="form-control" >
                                            @if ($errors->has('fullname'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('fullname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mt-1">
                                          <div><label class="label-block">{{__('app.phone')}}</label></div>
                                          <input type="text" name="phone" value="{{$user->phone}}" class="form-control" placeholder="Phone" >
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

                                    <div class="col-sm-6 mt-1">
                                      <label for="address" class="control-label">{{__('app.address')}}</label>
                                      <input type="text" name="address" class="form-control" value="{{$user->address}}" id="address" placeholder="Address">
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
                          <form class="form-horizontal" method="POST" action="{{route('update-login',$user->id)}}">
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
                        @if(setting('2fa'))
                        <div class="tab-pane" id="tfa-settings" role="tabpanel" aria-labelledby="tfa-settings-tab">
                          <!--Google Two Factor Authentication card-->
                          <div class="col-md-12">
                            @include('layouts.includes.alerts')
                            @if(empty(auth()->user()->google2fa))
                            <!--=============Generate QRCode for Google 2FA Authentication=============-->
                                  <div class="row p-0">
                                    <div class="col-md-12">
                                          <p>{{__('app.to_activate_2fa')}}</p>
                                    </div>
                                  <div class="col-md-12">
                                    <form class="" action="{{route('activate-2fa')}}" method="post">
                                      @csrf
                                      <button class="btn btn-primary col-md-6">{{__('app.activate_2fa')}}</button>
                                      <a class="btn btn-secondary col-md-5" data-toggle="collapse" href="#collapseExample"
                                      role="button" aria-expanded="false" aria-controls="collapseExample">{{__('app.setup_instruction')}}</a>
                                    </form>
                                  </div>
                                  <div class="col-md-12 mt-3 collapse" id="collapseExample">
                                    <hr>
                                    <h3 class="">{{__('app.2fa_instruction_1')}}</h3>
                                    <hr>
                                   <div class="mt-4">
                                          <h4>{{__('app.2fa_instruction_2')}}</h4>
                                          <p><label>{{__('app.step_1')}}:</label> {{__('app.download')}} <strong>{{__('app.google_auth')}}</strong> {{__('app.app_for_andriod_or_ios')}}</p>
                                          <p class="text-center">
                                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank"
                                            class="btn btn-success">{{__('app.download_for_android')}}<i class="fa fa-android fa-2x ml-2"></i></a>
                                            <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank"
                                            class="btn btn-dark ml-2">{{__('app.download_for_iPhones')}}<i class="fa fa-apple fa-2x ml-2"></i></a></p>
                                          <p><label>{{__('app.step_2')}}:</label> {{__('app.click_on_generate_secret')}}</p>
                                          <p><label>{{__('app.step_3')}}:</label> {{__('app.open_the')}} <strong>{{__('app.google_auth')}}</strong> {{__('app.and_click_on')}} <strong>{{__('app.begin')}}</strong> {{__('app.on_the_mobile_app')}}</p>
                                          <p><label>{{__('app.step_4')}}:</label> {{__('app.after_which_click_on')}} <strong>{{__('app.scan_a_QRcode')}}</strong></p>
                                          <p><label>{{__('app.step_5')}}:</label> {{__('app.then_scan_the_barcode_on')}}</p>
                                          <p><label>{{__('app.step_6')}}:</label> {{__('app.enter_the_verification_code')}}</p>
                                          <hr>
                                          <p><label>{{__('app.note')}}:</label> {{__('app.to_diasable_2fa_enter')}}</p>
                                   </div>
                                  </div>
                                </div>
                            <!--=============Generate QRCode for Google 2FA Authentication=============-->
                            @elseif(!auth()->user()->google2fa->google2fa_enable)
                            <!--=============Enable Google 2FA Authentication=============-->
                              <form class="form-horizontal" method="POST" action="{{route('enable-2fa')}}">
                                @csrf
                                    <div class="row">
                                        <div class="col-md-12"><p><strong>{{__('app.scan_the_qrcode_with')}} <dfn>{{__('app.google_auth')}}</dfn> {{__('app.and_enter_the_generated_code_below')}}</strong></p></div>
                                        <div class="col-md-12"><img src="{{$generated}}" /></div>
                                        <div class="col-md-12">
                                        <p>{{__('app.to_enable_2fa_auth_verify_qrcode')}}</p>
                                        </div>
                                        <div class="col-sm-12">
                                          <label for="address" class="control-label">{{__('app.verification_code')}}</label>
                                          <input type="text" name="code" class="form-control" id="code" placeholder="{{__('app.enter_verification_code')}}">
                                          @if ($errors->has('code'))
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('code') }}</strong>
                                            </span>
                                          @endif
                                        </div>
                                      </div>
                                    <div class="col-md-8 mx-auto m-2">
                                      <button type="submit" class="btn btn-primary col-sm-12">{{__('app.enable_2fa')}}</button>
                                    </div>
                              </form>
                            <!--=============Enable Google 2FA Authentication=============-->
                            @elseif(auth()->user()->google2fa->google2fa_enable)
                            <!--=============Disable Google 2FA Authentication=============-->
                              <form class="form-horizontal" method="POST" action="{{route('disable-2fa')}}">
                                @csrf
                                    <div class="row">
                                          <div class="col-md-12"><img src="{{$generated}}" /></div>
                                          <div class="col-md-12">
                                               <p>{{__('app.to_disable_2fa_auth_verify_qrcode')}}</p>
                                          </div>
                                          <div class="col-sm-12">
                                              <label for="address" class="control-label">{{__('app.verification_code')}}</label>
                                              <input type="text" name="code" class="form-control" id="code" placeholder="{{__('app.enter_verification_code')}}">
                                              @if ($errors->has('code'))
                                                <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $errors->first('code') }}</strong>
                                                </span>
                                              @endif
                                          </div>
                                          <div class="col-sm-12">
                                                <label for="address" class="control-label">{{__('app.current_password')}}</label>
                                                <input id="password" type="password" placeholder="{{ __('Current Password') }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                                @error('password')
                                                  <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $error('password')}}</strong>
                                                  </span>
                                                @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-8 mx-auto m-2">
                                        <button type="submit" class="btn btn-danger col-sm-12">{{__('app.disable_2fa')}}</button>
                                      </div>
                              </form>
                            <!--=============Disable Google 2FA Authentication=============-->
                            @endif
                            </div>
                        </div>
                        @endif
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
