@extends('layouts.template')

@section('title','Subscription Plans')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3>{{__('app.subscription_plans')}}</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="/subscription/plan">{{__('app.subscription_plans')}}</a></li>
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
          <div class="col-md-12 mx-auto">
            @include('layouts.includes.alerts')
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                      <a href="/subscription/plan/create" class="pull-right btn btn-primary">{{__('app.create_plan')}}</a>
                    </div>
                    <div class="col-md-12">
                    @if($error_message)
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          {{$error_message}}
                      </div>
                    @endif
                      <div class="table-responsive no-padding">
                        <table id="dataTable" class="table table-hover table-striped table-borderless">
                          <thead>
                            <tr>
                              <th>{{__('app.plan_name')}}</th>
                    				  <th>{{__('app.plan_frequency')}}</th>
                              <th>{{__('app.plan_interval')}}</th>
                              <th>{{__('app.amount')}}</th>
                              <th>{{__('app.trial_period')}}</th>
                              <th>{{__('app.action')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($plans as $plan)
                            <tr>
                              <td>{{$plan->plan_name}}</td>
                    					<td>{{$plan->plan_interval}}</td>
                    					<td>{{$plan->plan_intervalCount}}</td>
                    					<td>@money($plan->plan_amount)</td>
                              <!-- str_plural for days or day indication -->
                    					<td>{{($plan->trial_period_days < 1 )?  0 : $plan->trial_period_days." ".str_plural('day',$plan->trial_period_days) }}</td>
                              <td>

                                      <div class="d-inline-block">
                                        <a href="{{url('/stripe/plan/edit',['plan_id'=>$plan->plan_id])}}"><button class="btn btn-sm btn-info" data-toggle="tooltip"  data-placement="bottom" title="{{__('app.edit_plan')}}"/><i class="fa fa-edit text-white"></i></button></a>
                                      </div>
                                      <div class="d-inline-block">
                                        <form action="{{url('/stripe/plan/delete',['plan_id'=>$plan->plan_id])}}" method="POST">
                                          @csrf
                                          <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"  data-target="#deletePlan{{$plan->plan_id}}" title="{{__('app.delete_plan')}}"/><i class='fa fa-trash text-white'></i></button>
                                          <div class="modal fade" id="deletePlan{{$plan->plan_id}}" tabindex="-1" role="dialog" aria-labelledby="deletePlanLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-body text-center">
                                                  <h3 class="mb-4">{{__('app.please_confirm')}}</h3>
                                                  <p class="mb-5">{{__('app.delete_plan_confirm')}}</p>
                                                  <button type="button" class="btn btn-secondary col-md-5 pull-left" data-dismiss="modal">{{__('app.close')}}</button>
                                                  <button type="submit" class="btn btn-danger col-md-6 pull-right">{{__('app.delete_plan')}}</button>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </form>
                                      </div>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
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
