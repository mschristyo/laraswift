@extends('layouts.template')
@section('title','Subscribed Users')
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
              <li class="breadcrumb-item active">{{__('app.subscribed_users')}}</li>
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
  		           @include('layouts.includes.alerts')
                  <div class="card">
                    <div class="card-body">
                        <div class="row">
                          <div class="col-md-12 mb-3">
                            <h3 class="pull-right pr-2">{{__('app.subscribed_users')}}</h3>
                          </div>
                          <div class="col-md-12">
                            @if($error_message)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{$error_message}}
                            </div>
                            @endif
                            <div class="table-responsive no-padding">
                              <table id="dataTable" class="table table-hover table-borderless table-striped">
                                <thead>
                                  <tr>
                                    <th>{{__('app.action')}}</th>
                                    <th>{{__('app.fullname')}}</th>
                                    <th>{{__('app.subscription_plan')}}</th>
                                    <th>{{__('app.subscription_amount')}}</th>
                                    <th>{{__('app.subscription_status')}}</th>
                                    <th>{{__('app.start_date')}}</th>
                                    <th>{{__('app.end_date')}}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($subscriptions as $subscription)
                                    @if(!is_null($subscription['subscription']))
                                      <tr>
                                        <td>
                                          <a href="/user-subscription-invoice/{{$subscription['subscription']->latest_invoice}}" class="" target="_blank"><button class="btn btn-sm btn-info mt-1" data-toggle="tooltip"  data-placement="bottom" title="Subscription Invoice PDF"/><i class="fa fa-file-pdf-o text-white"></i></button></a>
                                          <a type="button" data-toggle="modal" data-target="#cancelSub{{$subscription['subscription']->id}}"><button class="btn btn-sm btn-danger mt-1" data-toggle="tooltip"  data-placement="bottom" title="Cancel Subscription"/><i class="fa fa-remove text-white"></i></button></a>
                                          <div class="modal fade" id="cancelSub{{$subscription['subscription']->id}}" tabindex="-1" role="dialog" aria-labelledby="cancelSubLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-body text-center">
                                                  <h3 class="mb-4">{{__('app.please_confirm')}}</h3>
                                                  <p class="mb-5">{{__('app.subscription_confirm_cancel')}}</p>
                                                  <button type="button" class="btn btn-secondary col-md-5 pull-left" data-dismiss="modal">{{__('app.close')}}</button>
                                                  <a type="button" href="/user-subscription-cancel/{{$subscription['subscription']->id}}" class="btn btn-danger col-md-6 pull-right">{{__('app.cancel_subscription')}}</a>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </td>
                                        <td><a href="/user/">{{($subscription['fullname'])? $subscription['fullname'] : $subscription['customer']->email }}</a></td>
                                        <td>{{$subscription['subscription']->plan->nickname}}</td>
                                        <td>
                                          @if($subscription['subscription']->status == 'trialing')
                                            @money(0)
                                          @else
                                            @money($subscription['subscription']->plan->amount)
                                          @endif
                                        </td>
                                        <td>{{ucfirst($subscription['subscription']->status)}}</td>
                                        <td>{{date('M d, Y',$subscription['subscription']->current_period_start)}}</td>
                                        <td>{{date('M d, Y',$subscription['subscription']->current_period_end)}}</td>
                                    </tr>
                                    @endif
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
