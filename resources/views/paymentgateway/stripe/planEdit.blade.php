@extends('layouts.template')

@section('title','Edit Stripe Plan')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('app.subscription_plans')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="/subscription/plan">{{__('app.subscription_plans')}}</a></li>
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
            <div class="col-md-12">
                @include('layouts.includes.alerts')
                <div id="float-none" class="col-md-7 mx-auto">
                    <form class="form-horizontal" method="POST" action="{{url('/stripe/plan/edit',['id'=>$plan->id,'plan_'=>$plan->plan_id,])}}">
                        @csrf
                      <!-- Role Creation -->
                          <div class="card">
                              <div class="card-header">
                                <h3 class="card-title">{{__('app.edit_subscription_plans')}}</h3>
                              </div>
                              <!-- /.card-header -->

                              <div class="card-body">

                              <div class="form-group ">
                                    <div  class="col-sm-12">
                                        <label for="plan_name" class="control-label">{{__('app.plan_name')}}</label>
                                        <input type="text" name="plan_name" value="{{$plan->plan_name}}" class="form-control" id="password" placeholder="{{__('app.plan_name')}}">
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
                                        <textarea id="plan-description" name="plan_description" class="form-control" placeholder="{{__('app.plan_description')}}">{!!$plan->plan_description!!}</textarea>
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
                                        <input type="text" name="plan_amount" value="@money($plan->plan_amount)" class="form-control" id="plan_amount" placeholder="{{__('app.amount')}}" DISABLED>
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
                                        <select class="form-control" name="plan_interval" DISABLED>
                                              <option  value="{{__('app.year')}}" {{($plan->plan_interval == "year") ? 'SELECTED':''}}>{{__('app.yearly')}}</option>
                                              <option value="{{__('app.month')}}" {{($plan->plan_interval == "month") ? 'SELECTED':''}}>{{__('app.monthly')}}</option>
                                              <option value="{{__('app.week')}}"  {{($plan->plan_interval == "week") ? 'SELECTED':''}}>{{__('app.weekly')}}</option>
                                              <option value="{{__('app.day')}}"   {{($plan->plan_interval == "daily") ? 'SELECTED':''}}>{{__('app.daily')}}</option>
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
                                        <input type="number" name="plan_intervalCount" value="{{$plan->plan_intervalCount}}" class="form-control" id="plan_intervalCount" placeholder="{{__('app.plan_interval_count')}}" DISABLED>
                                        @if ($errors->has('plan_intervalCount'))
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('plan_interval') }}</strong>
                                          </span>
                                        @endif
                                    </div>
                              </div>

                            </div>
                              <!-- /.card-body -->
                              <div class="card-footer">
                                <div class="col-sm-8 mx-auto">
                                  <button type="submit" class="btn btn-info col-sm-12">{{__('app.update_plan')}}</button>
                                </div>
                              </div>
                                <!-- form end -->
                            </div>
                            <!-- /.card-body -->
                      </form>
                      <!-- Role Creation -->
                </div>
              </div>
              <!-- /.card -->
            </div>
            <!--/.col (right) -->
          <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
