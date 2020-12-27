@extends('layouts.app')
@section('content')

<div class="container c-container">
    <h5 class="mb-4 text-center">合計時間・完了タスク数</h5>
    @if(isset($summaries['projects'])&&isset($summaries['categories']))
    <div class="row justify-content-center mb-2">
        @include('components.summary_project',['summaries'=>$summaries['projects']])
        @include('components.summary_category',['summaries'=>$summaries['categories']])
    </div>
    @endif
</div>

@endsection
