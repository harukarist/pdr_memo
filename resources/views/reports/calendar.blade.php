@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
                {{-- CalendarViewで作った関数を利用して、タイトルとカレンダー本体をわけて出力 --}}
                <div class="card-header text-center">
                    <a class="btn btn-outline-secondary float-left" href="{{ route('reports.calendar',['date' => $calendar->getPreviousMonth()]) }}">前の月</a>
                    
                    <span>{{ $calendar->getTitle() }}</span>
                    
                    <a class="btn btn-outline-secondary float-right" href="{{ route('reports.calendar',['date' => $calendar->getNextMonth()])}}">次の月</a>
                </div>
                <div class="card-body">
                    {!! $calendar->render() !!}
                </div>
            </div>
       </div>
   </div>
</div>
@include('reports.lists',['lists'=>$lists,'category'=>$category])

@endsection
