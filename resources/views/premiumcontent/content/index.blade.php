@extends('layouts.template')

@section('title','Article')
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
              <li class="breadcrumb-item"><a href="/premium-content">{{__('app.content')}}</a></li>
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
        <div class="col-md-12">
          @include('layouts.includes.alerts')
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12 mb-3">
                  <h3 class="pull-right">{{__('app.articles')}}</h3>
                </div>
                <div class="col-md-12">
                  <div class="table-responsive no-padding">
                    <table id="dataTable" class="table table-hover table-striped table-borderless">
                      <thead>
                      <tr>
                        <th class="">{{__('app.title')}}</th>
                        <th class="">{{__('app.created_at')}}</th>
                        <th class="">{{__('app.updated_at')}}</th>
                        <th class="">{{__('app.action')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($articles as $article)
                      <tr>
                        <td>{{str_limit($article->title,100,'(...)')}}</td>
                        <td>{{date('M d, Y',strtotime($article->created_at))}}</td>
                        <td>{{date('M d, Y',strtotime($article->updated_at))}}</td>
                        <td>
                          <div class="col-md-12">
                            <div class="row">
                              <div class="mx-1">
                                <a href="premium-content/{{$article->slug}}"><button class="btn btn-info" data-toggle="tooltip"  data-placement="bottom" title="{{__('app.view_article')}}"/><i class="fa fa-eye text-white"></i></button></a>
                              </div>
                            </div>
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
