@extends('layouts.template')

@section('title','Article Create')
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
              <div class="col-md-12">
                  @include('layouts.includes.alerts')
                    <div class="card">
                      <div class="card-body">
                          <form class="" action="/article/{{$article->slug}}" method="post">
                                  @method("PUT")
                                  @csrf
                                  <div class="form-group">
                                    <select class="form-control" name="category">
                                      <option>{{'--Select Category--'}}</option>
                                      @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{($category->id == $article->article_category_id)? 'selected':''}}>{{Str::title($category->name)}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <input type="text" name="title" value="{{$article->title}}" class="form-control" id="title" placeholder="{{__('app.article_title')}}">
                                  </div>
                                  <div class="form-group">
                                    <textarea id="summernote" name="content">{{$article->content}}</textarea>
                                  </div>
                                  <div class="form-group">
                                    <input type="submit" class="btn btn-primary col-md-12" value="{{__('app.update_article')}}">
                                  </div>
                            </form>
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
