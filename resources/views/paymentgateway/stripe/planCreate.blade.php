@extends('layouts.template')

@section('title','Create Stripe Plan')
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
              <li class="breadcrumb-item"><a href="/subscription/plan">{{__('app.subscription_plans')}}</a></li>
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
        <div class="col-md-12">
            @include('layouts.includes.alerts')
            <div class="col-md-7 mx-auto">
                  <!-- Role Creation -->
                      <div class="card">
                        <form class="form-horizontal" method="POST" action="/subscription/plan/create">
                          @csrf
                        <!-- form start -->
                        <div class="card-body">
                              <div class="row">
                                <div class="col-md-12 mb-3">
                                  <h3 class="pull-right">{{__('app.create_plan')}}</h3>
                                </div>
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <div  class="col-sm-12">
                                          <label for="plan_name" class="control-label">{{__('app.plan_name')}}</label>
                                          <input type="text" name="plan_name" class="form-control" id="password" placeholder="{{__('app.plan_name')}}">
                                          @if ($errors->has('plan_name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('plan_name') }}</strong>
                                            </span>
                                          @endif
                                        </div>
                                  </div>
                                  <div class="form-group ">
                                      <div  class="col-sm-12">
                                          <label for="plan_description" class="control-label">{{__('app.plan_description')}}</label>
                                          <textarea id="plan-description" name="plan_description" class="form-control textarea-resize-none" placeholder="{{__('app.plan_description')}}"></textarea>
                                          @if ($errors->has('plan_description'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('plan_description') }}</strong>
                                            </span>
                                          @endif
                                        </div>
                                  </div>
                                  <div class="form-group ">
                                        <div  class="col-sm-12">
                                            <label for="plan_amount" class="control-label">{{__('app.amount')}}</label>
                                            <input type="text" name="plan_amount" class="form-control" id="plan_amount" placeholder="{{__('app.amount')}}">
                                            @if ($errors->has('plan_amount'))
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $errors->first('plan_amount') }}</strong>
                                              </span>
                                            @endif
                                          </div>
                                    </div>
                                  <div class="form-group ">
                                        <div  class="col-sm-12">
                                            <label for="plan_interval" class="control-label">{{__('app.plan_interval')}}</label>
                                            <select class="form-control" name="plan_interval">
                                              <option value="{{__('app.year')}}">{{__('app.yearly')}}</option>
                                              <option value="{{__('app.month')}}" selected>{{__('app.monthly')}}</option>
                                              <option value="{{__('app.week')}}">{{__('app.weekly')}}</option>
                                              <option value="{{__('app.day')}}">{{__('app.daily')}}</option>
                                            </select>
                                            @if ($errors->has('plan_interval'))
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $errors->first('plan_interval') }}</strong>
                                              </span>
                                            @endif
                                        </div>
                                  </div>
                                  <div class="form-group ">
                                      <div  class="col-sm-12">
                                          <label for="plan_intervalCount" class="control-label">{{__('app.plan_interval_count')}}</label>
                                          <input type="number" name="plan_intervalCount" class="form-control" id="plan_intervalCount" placeholder="{{__('app.plan_interval_count')}}">
                                          @if ($errors->has('plan_intervalCount'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('plan_interval') }}</strong>
                                            </span>
                                              @endif
                                          </div>
                                  </div>
                                  <div class="form-group ">
                                      <div  class="col-sm-12">
                                          <label for="plan_intervalCount" class="control-label mr-2">{{__('app.add_trial_period')}}</label>
                                          <input type="checkbox" name="trial_check" id="trial_check">
                                          <input type="text" name="plan_trial_period" class="form-control" id="plan_trial_period" placeholder="{{__('app.trial_period')}}">
                                          @if ($errors->has('plan_trial_period'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('plan_trial_period') }}</strong>
                                            </span>
                                              @endif
                                          </div>
                                  </div>
                                </div>
                              </div>
                         </div>
                          <!-- /.card-body -->
                          <script>
                            (function(){
                              "use strict";

                              window.onload = function(){
                                var trial_check = document.getElementById('trial_check');
                                var trial_check_input = document.getElementById('plan_trial_period');
                                trial_check_input.style.display = 'none';
                                trial_check.addEventListener('change',function(){
                                  if(this.checked){
                                    trial_check_input.style.display = '';
                                  }else{
                                    trial_check_input.style.display = 'none';
                                  }
                                })
                              }
                            })()
                          </script>
                        <div class="card-footer">
                            <div class="col-md-8 mx-auto">
                               <button type="submit" class="btn btn-info col-sm-12">{{__('app.create_plan')}}</button>
                            </div>
                          </div>
                          <!-- Role Creation -->
                      </form>
                <!-- form end -->
                </div>
                <!-- /.card-body -->
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!--/.col (right) -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
