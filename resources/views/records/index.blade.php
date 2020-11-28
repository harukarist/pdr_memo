@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h5 class="mb-4">これまでの記録</h5>

  <section class="mb-5">
    @forelse($lists as $list)
    <div class="p-record__date border-bottom d-flex justify-content-between">
      <h5>{{ $list['target_date'] }}</h5>
      <div class="p-record__time">
        @forelse($list['total_time'] as $key=>$val)
        <mark class="ml-3">{{ $category[$key]['category_name'] }} {{ round($val) }} h</mark>
        @empty
        @endforelse
      </div>
    </div>
    <div class="p-record__contents">
      @forelse($list['tasks'] as $task)
      <p>■{{ $task['task_name'] }}</p>
        @forelse($task['reviews'] as $review)
        <p>
          {{ $review['review_text'] }}<br>
          <mark>{{ $review['category']['category_name'] }}</mark>
          <mark>{{ $review['actual_time'] }}分</mark>
        </p>  
        @empty
        @endforelse
      @empty
      @endforelse
    </div>
    @empty
    @endforelse
  </section>


  {{-- <section class="mb-5">
    @forelse($records as $record)
        <div class="d-flex justify-content-between w-50">
        <div class="">
          {{ $record->day }}
        </div>
        <div class="">
          {{ $record->hour ?? 0 }} h
        </div>
      </div>
        @empty
          記録はまだありません
    @endforelse
  </section> --}}
</div>
@endsection
