@extends('layouts.app')
@section('content')
<div class="container c-container">
    <div class="row justify-content-center mb-2">
        <div class="col-md-10">
            <div class="card">
                {{-- CalendarViewで作った関数を利用して、タイトルとカレンダー本体をわけて出力 --}}
                <div class="card-header text-center">
                    <a class="btn btn-outline-secondary float-left" href="{{ route('reports.calendar',['date' => $calendar->getPrevious()]) }}">
                        <i class="fas fa-caret-left" aria-hidden="true"></i>
                        前月</a>
                    
                    <span>{{ $calendar->getTitle() }}</span>
                    
                    <a class="btn btn-outline-secondary float-right" href="{{ route('reports.calendar',['date' => $calendar->getNext()])}}">次月
                        <i class="fas fa-caret-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="card-body text-center p-2">
                    {!! $calendar->render() !!}
                    <a class="small" href="{{ route('reports.weekly',['date' => $calendar->getDate()]) }}">週表示に戻る</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        @include('reports.lists_daily',['lists'=>$lists])
    </div>
</div>

@endsection
