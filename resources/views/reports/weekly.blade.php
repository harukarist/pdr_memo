@extends('layouts.app')
@section('content')
<div class="container c-container">
<h5 class="mb-4 text-center">これまでの記録</h5>
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
                <div class="card-header text-center">
                    <a class="btn btn-outline-secondary float-left" href="{{ route('reports.weekly',['date' => $weekly->getPreviousWeek()]) }}">前の週</a>
                    
                    <span>{{ $weekly->getTitle() }}</span>
                    
                    <a class="btn btn-outline-secondary float-right" href="{{ route('reports.weekly',['date' => $weekly->getNextWeek()]) }}">次の週</a>
                </div>
                <div class="card-body text-center p-2">
                    {!! $weekly->render() !!}
                    <a class="small" href="{{ route('reports.calendar',['date' => $weekly->getPreviousWeek()]) }}">カレンダーを表示</a>
                </div>
            </div>
       </div>
   </div>
</div>
{{-- PDR --}}
@include('reports.lists',['lists'=>$lists,'category'=>$category])

@endsection
