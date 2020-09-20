@extends('errors::illustrated-layout')

@section('title', __('Method Not Allowed'))
@section('code')
  {{$code}}
@endsection
@section('message')
  {{$message}}
@endsection
