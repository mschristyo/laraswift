@extends('layouts.template')
@section('title','Incomes')
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
              <li class="breadcrumb-item active">{{__('app.income_record')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
       <div class="container-fluid">
          <div class="row">
             <div class="col-md-8">
  		           @include('layouts.includes.alerts')
                  <div class="card">
                    <div class="card-body">
                        <div class="row">
                          <div class="col-md-12 mb-3">
                            <h3 class="pull-right pr-2">{{__('app.income_history')}}</h3>
                          </div>
                          <div class="col-md-12">
                            <div class="table-responsive no-padding">
                              <table id="dataTable" class="table table-hover table-borderless table-striped">
                                <thead>
                                  <tr>
                                    <th>{{__('app.date_of_payment')}}</th>
                                    <th>{{__('app.payer')}}</th>
                                    <th>{{__('app.amount')}}</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($incomes as $income)
                                      @if($income)
                                        <tr>
                                          <td>{{date('M d, y h:i:s',strtotime($income['date']))}}</td>
                                            <td>{{$income['payer']}}</td>
                                            <td>@money($income['amount'])</td>
                                        </tr>
                                      @endif
                                  @endforeach
                                  <tr>
                                    <td><h5>Sub-total</h5></td>
                                    <td></td>
                                    <td><h5>@if($total) @money($total) @else {{'N/A'}} @endif</h5></td>
                                  </tr>
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
             <div class="col-md-4">
               <div class="card">
                 <div class="card-body">
                    <h4>{{__('app.overral_income')}}</h4> <h3>@if($overrall_income) @money($overrall_income) @else {{'N/A'}} @endif</h3>
                 </div>
               </div>
             </div>
          </div>
       </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
