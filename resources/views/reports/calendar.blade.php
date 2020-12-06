@extends('layouts.app')
@section('content')
<div class="container c-container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
                {{-- CalendarViewで作った関数を利用して、タイトルとカレンダー本体をわけて出力 --}}
                <div class="card-header text-center">
                    <a class="btn btn-outline-secondary float-left" href="{{ route('reports.calendar',['date' => $calendar->getPrevious()]) }}">前の月</a>
                    
                    <span>{{ $calendar->getTitle() }}</span>
                    
                    <a class="btn btn-outline-secondary float-right" href="{{ route('reports.calendar',['date' => $calendar->getNext()])}}">次の月</a>
                </div>
                <div class="card-body text-center p-2">
                    {!! $calendar->render() !!}
                    <a class="small" href="{{ route('reports.weekly',['date' => $calendar->getDate()]) }}">週表示に戻る</a>
                </div>
            </div>
       </div>
   </div>
</div>
@include('reports.lists',['lists'=>$lists,'category'=>$category])

@endsection
