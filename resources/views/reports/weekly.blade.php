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
                <div class="card-header text-center">
                    <a class="btn btn-outline-secondary float-left" href="{{ route('reports.weekly',['date' => $weekly->getPrevious()]) }}">
                    <i class="fas fa-caret-left" aria-hidden="true"></i>
                    前週</a>
                    
                    <span>{{ $weekly->getTitle() }}</span>
                    
                    <a class="btn btn-outline-secondary float-right" href="{{ route('reports.weekly',['date' => $weekly->getNext()]) }}">
                    次週
                    <i class="fas fa-caret-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="card-body text-center p-2 weekly">
                    {!! $weekly->render() !!}
                    <a class="small" href="{{ route('reports.calendar',['date' => $weekly->getDate()]) }}">カレンダーを表示</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
       @include('components.lists_weekly',['lists'=>$lists,'startDay'=>$startDay, 'lastDay'=>$lastDay,'url_date'=>$url_date])
    </div>
</div>

@endsection
