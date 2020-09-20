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
            <h1>{{__('app.subscription')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('/subscription')}}">{{__('app.subscription')}}</a></li>
              <li class="breadcrumb-item active">{{__('app.plans')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          <div class="row">
          		@include('layouts.includes.alerts')
              @if(count($plans) < 1)
              <div class=" col-md-12 mx-auto">
                <div class="callout callout-danger text-center">
                  <h4 class=""><dfn>{{__('app.subscription_not_available')}}</dfn></h4>
                </div>
              </div>
              @endif
                  <div class="col-md-12">
                  <div class="row">
              			@foreach($plans->sortBy('id') as $plan)
                  			<div class="col-md-4 mx-0 my-1 mb-5">
                  				<div class="card border-top border-bottom-0 border-right-0 border-left-0 border-dark h-100">
                  					<!-- /.card-header -->
                  					<div class="card-body p-3">
                  						<div class="row">
                                  <div class=" col-md-10 mx-auto text-center h-100">
                                    <p class="px-3 py-2 bg-dark rounded-lg p-1 text-primary border-primary mx-auto d-inline-block lead font-weight-normal shadow">{{$plan->plan_name}}</p>
                                    <div class="text-left mb-3">
                                      <p class="mb-2">{!!$plan->plan_description!!}</p>
                                    </div>

                                    <hr />
                                    <p class="text-center mb-0 pb-0"><span class="font-weight-400 font-size-35">@money($plan->plan_amount)</span><span class="font-weight-400 font-size-25">/</span><span class="font-weight-400 font-size-20">{{ucfirst($plan->plan_interval)}}</span></p>
                                    <p class="text-center mt-0"><span>{{__('app.plan_interval')}}</span> : <span>{{$plan->plan_intervalCount}}</span></p>
                                    <p class="text-center mt-0"><span>{{__('app.trial_period')}}</span> : <span>{{($plan->trial_period_days)? $plan->trial_period_days:"nil" }}</span></p>
                                    <h2 class="mt-3"></h2>
                                  </div>
                  						</div>
                              <div class="col-md-12 mx-auto">
                                <form action="{{route('/subscription/stripe',['plan_id' =>$plan->plan_id])}}" method="GET" id="subscription-form">
                                  @csrf
                                  <div class="col-sm-12"><button type="submit" class="btn btn-primary col-sm-12">{{__('app.subscribe')}}</button></div>
                                </form>
                              </div>
                  					</div>
                  						<!-- /.card-body -->
                  						<!-- /.card-footer -->
                  				</div>
                  			<!-- /.card -->
                </div>
        			@endforeach

          </div>
          </div>
          </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
