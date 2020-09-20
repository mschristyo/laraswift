@extends('layouts.template')
@section('title','Checkout Samples')
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
              <li class="breadcrumb-item"><a href="/checkout-sample">{{__('app.sample')}}</a></li>
              <li class="breadcrumb-item active">{{__('app.paid')}}</li>
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
                  <div class="card {{($response=='failed')? 'd-none':''}}">
                    <div class="card-body">
                        <div class="row">
                          <div class="col-md-12 mb-3">
                            <h3 class="pull-right pr-2">{{__('app.download_article')}}</h3>
                          </div>
                          <div class="col-md-12">
                            <div class="col-md-8 mx-auto">
                              <div class="card elevate shadow h-100">
                                  <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 my-3 text-center">
                                          <img src="{{asset('uploads/download.jpg')}}" alt="" class="img rounded img-fluid">
                                        </div>
                                        <div class="col-md-6 my-3 border-left py-2">
                                          <ul class="mb-5 list-style-square">
                                            <li>{{__('app.document_sample')}}</li>
                                            <li>{{__('app.sample_pages')}}</li>
                                            <li>{{__('app.sample_author')}}</li>
                                            <li>{{__('app.sample_publish_date')}}</li>
                                          </ul>
                                        </div>
                                        <div class="col-md-12 text-center">
                                          <a href="/checkout-sample/article"><button class="btn btn-success col-md-6">{{__('app.download_article')}}</button></a>
                                        </div>
                                    </div>
                                  </div>
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
    </section>
    <!-- /.content -->
  </div>
  <script>
    "use strict";
   var response = '{{$response}}';
   var message = '{{$message}}'
   var status = '{{$response}}'
   if(status=='success'){
     swal({
       icon:'success',
       text:message
     })
   }else{
     swal({
       icon:'error',
       text:message
     }).then(function(){
       window.location.replace("{{URL::to('/checkout-sample')}}")
     })
   }
  </script>
  <!-- /.content-wrapper -->
@endsection
