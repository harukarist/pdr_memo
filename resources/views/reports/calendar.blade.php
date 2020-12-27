@extends('layouts.app')
@section('content')
<div class="container c-container">
    <h5 class="mb-4 text-center">これまでの記録</h5>
    
    @if(isset($summaries))
        @include('components.summary_total',['summaries'=>$summaries,'targetDay'=>$targetDay])
    @endif

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
        @include('components.lists_daily',['lists'=>$lists,'url_date'=>$url_date])
    </div>
</div>

@endsection
