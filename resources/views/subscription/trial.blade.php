@extends('layouts.template')

@section('title','Subscriptions')
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
              <li class="breadcrumb-item active">{{__('app.subscription')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          <div class="row">
      			<div class="col-md-7 mx-auto">
      				@include('layouts.includes.alerts')

      					<div class="card">
      						<div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h3 class="pull-right">{{__('app.subscription_details')}}</h3>
                        </div>
                        <div class="col-md-12">
                              <div class="table-responsive no-padding">
                    							<table class="table table-striped table-borderless pl-4 table-sm table-bordered">
                    								<tr>
                    									<td class="ml-5">{{__('app.name')}}</td>
                    									<td>{{auth()->user()->fullname}}</td>
                    								</tr>
                    								<tr>
                    									<td class="ml-5">{{__('app.plan_name')}}</td>
                    									<td>{{strtoupper($plan['plan_name'])}}</td>
                    								</tr>
                    								<tr>
                    									<td class="ml-5">{{__('app.plan_status')}}</td>
                    									<td>
                                          @if($plan['status']=='active')
                                            <span class="text-success">  {{'Active'}}</span>
                                          @elseif($plan['status']=='trialing')
                                            <span class="text-danger">  {{'Trial Version'}}</span>
                                          @endif
                                      </td>
                    								</tr>
                                    <tr>
                    									<td class="ml-5">{{__('app.plan_period')}}</td>
                    									<td>{{$plan['interval_count']}} {{ ucfirst($plan['interval'])}}</td>
                    								</tr>
                    								<tr>
                    									<td class="ml-5">{{__('app.plan_cost')}}</td>
                    									<td>@money($plan['amount']) {{strtoupper(config('app.currency')) }}</td>
                    								</tr>
                                    <tr>
                                      <td class="ml-5">{{__('app.plan_start_date')}}</td>
                                      <td>{{$plan['start_date']}}</td>
                                    </tr>
                                    <tr>
                                      <td class="ml-5">{{__('app.plan_expiring_date')}}</td>
                                      <td>{{$plan['end_date']}}</td>
                                    </tr>
                    							</table>
                            </div>
                        </div>
                        <div class="col-md-12 mx-auto mt-3">
                          <div class="row">
                            <div class="col-md-6">
                              <a href="/subscription-invoice/{{$plan['invoiceId']}}" class="btn btn-success col-md-12" target="blank">{{__('app.generate_invoice')}}</a>
                            </div>
                            <div class="col-md-6">
                              <a href="/subscription-cancel/{{$plan['subscriptionId']}}" class="btn btn-danger col-md-12" target="blank">{{__('app.cancel_subscription')}}</a>
                            </div>
                          </div>
                        </div>
                    </div>
      						</div>
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
