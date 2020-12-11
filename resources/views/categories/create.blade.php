@extends('layouts.app')

@section('content')
  <div class="container c-container">
    <div class="col-sm-8">

      @include('categories.form_create')

      @include('categories.category_list',['categories'=>$categories])
    </div>
  </div>
@endsection
