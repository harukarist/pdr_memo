@extends('layouts.app')

@section('content')
  <div class="container c-container">
    <div class="col-sm-8 mb-2">

      @include('categories.form_create')

      @include('categories.category_list',['categories'=>$categories])
    </div>
    <div class="col-sm-8">
      <button type="button" onclick="history.back()" class="btn btn-outline-secondary">
      {{-- <button type="button" onclick="history.back()" class="btn btn-outline-secondary"> --}}
        <i class="fas fa-caret-left mr-2" aria-hidden="true"></i>戻る</button>

    </div>
  </div>
@endsection
