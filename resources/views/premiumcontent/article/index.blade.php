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
            <h1>{{__('app.article')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{__('app.home')}}</a></li>
              <li class="breadcrumb-item"><a href="/article">{{__('app.article')}}</a></li>
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
                  <a href="article/create" class="pull-right btn btn-primary">{{__('app.create_article')}}</a>
                </div>
                <div class="col-md-12">
                  <div class="table-responsive no-padding ">
                <table id="dataTable" class="table table-borderless table-striped table-hover">
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
                    <td>{{Str::limit($article->title,80,'(...)')}}</td>
                    <td>{{date('M d, Y',strtotime($article->created_at))}}</td>
                    <td>{{date('M d, Y',strtotime($article->updated_at))}}</td>
                    <td>
                      <div class="col-md-12">
                        <div class="row">
                          <div class="mx-1">
                            <a href="/article/{{$article->slug}}"><button class="btn btn-info" data-toggle="tooltip"  data-placement="bottom" title="{{__('app.view_article')}}"/><i class="fa fa-eye text-white"></i></button></a>
                          </div>
                          <div class="mx-1">
                            <a href="/article/{{$article->slug}}/edit"><button class="btn btn-info" data-toggle="tooltip"  data-placement="bottom" title="{{__('app.edit_article')}}"/><i class="fa fa-edit text-white"></i></button></a>
                          </div>
                          <div class="mx-1">
                            <form action="/article/{{$article->slug}}" method="POST">
                              @method('DELETE')
                              @csrf
                              <button class="btn btn-danger" data-toggle="tooltip"  data-placement="bottom" title="{{__('app.delete_article')}}"/><i class='fa fa-trash text-white'></i></button>
                            </form>
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
