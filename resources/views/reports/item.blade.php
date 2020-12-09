@foreach($reviews as $review)
@if(isset($review->id))

@if(isset($review->id))
<div class="p-report__reviews bg-light px-4 py-2 mb-3">
    {{-- Review-Header --}}
    <div class="p-report__review-header d-flex mb-2 border-bottom">
        <span>
          <i class="fas fa-medal" aria-hidden="true"></i>
          Review-{{ $loop->remaining + 1}}
        </span>
        <mark class="mr-2 text-primary">
        <i class="fas fa-stopwatch ml-1" aria-hidden="true"></i>
          {{ $review->actual_time ?? 0 }}分
        </mark>
        <mark class="text-success mr-2">
        <i class="fas fa-spa" aria-hidden="true"></i>
        {{ $review->flow_level ?? '-' }}
        </mark>

        <span class="badge badge-light">{{ $review->prep->task->project->project_name ?? '' }}
        </span>
        <span class="badge badge-light">{{ $review->category->category_name ?? '' }}
        </span>

    </div>
    {{-- Task --}}
    @if(isset($review->prep->task->id))
    <div class="p-report__task-item d-flex bg-white p-2 mb-2">
      @php
        $task = $review->prep->task;
      @endphp
      <div class="p-report__task-checkbox pr-2">
        @if(!empty($task->status) && $task->status == 4)
        <i class="far fa-check-circle {{ $task->priority_class ?? '' }}" aria-hidden="true"></i>
        @else
        <i class="far fa-circle {{ $task->priority_class ?? '' }}" aria-hidden="true"></i>
        @endif
      </div>
      {{-- タスク名 --}}
      <div class="p-report__task-taskname">
        {{ $task->task_name ?? '' }}
        @if($task->status != 4 && $task->priority > 0)
        <div class="p-report__task-priority d-inline-block">
          <small class="ml-2 {{ $task->priority_class ?? '' }}">
            @forelse(range(1,$task->priority) as $num)
            <i class="fas fa-star" aria-hidden="true"></i>
            @empty
            @endforelse
          </small>
        </div>
        @endif
        @isset($task->formatted_due_date)
        <span class="badge badge-light">
          <i class="fas fa-calendar-day" aria-hidden="true"></i>
        {{ $task->formatted_due_date ?? '' }}
        </span>
        @endisset
      </div>
    </div>
    @endif
    {{-- Review-Text --}}
    <div class="p-report__review-text px-2">
        <p class="mb-0">
        {!! nl2br(e($review->review_text)) !!}
        </p>
        <small class="text-muted mb-1">
          {{ $review->started_at ?? '' }}〜
        </small>
        @if($review->good_text||$review->problem_text||$review->try_text)
          <div class="p-report__review-kpt border p-2 mt-1 mb-2">
            @isset($review->good_text)
              <p class="mb-1">
                <i class="far fa-laugh-wink"></i>
                <span class="small font-weight-bold">Good/Keep</span><br>
                {!! nl2br(e($review->good_text)) !!}
              </p>
            @endisset
            @isset($review->problem_text)
            <p class="mb-1">
              <i class="fas fa-bolt"></i>
              <span class="small font-weight-bold">Problem</span><br>
              {!! nl2br(e($review->problem_text)) !!}
            </p>
            @endisset
            @isset($review->try_text)
            <p class="mb-1">
              <i class="far fa-lightbulb"></i>
              <span class="small font-weight-bold">Try</span><br>
              {!! nl2br(e($review->try_text)) !!}
            </p>
            @endisset
          </div>
        @endif
    </div>

    {{-- Prep --}}
    {{-- @if(isset($review->prep->id))
      @php
        $prep = $review->prep;
      @endphp
      <div class="p-report__prep-wrapper col-md p-1">
        <div class="p-report__prep-detail d-flex justify-content-between mb-2">
            <span>
              <i class="far fa-clipboard" aria-hidden="true"></i>
              Prep-{{ $loop->iteration }}
            </span>
            
            <mark class="mr-2 text-primary">
            {{ $prep->unit_time }}分 × 
            @foreach(range(1,$prep->estimated_steps) as $num)
            <i class="fas fa-stopwatch" aria-hidden="true"></i>
            @endforeach
            = {{ ($prep->unit_time)*($prep->estimated_steps) ?? '' }}分
            </mark>
            <span class="badge badge-light">{{ $prep->category->category_name ?? '' }}</span>
        </div>
        <div class="p-report__prep-text">
            <p class="mb-1">{!! nl2br(e($prep->prep_text)) !!}</p>
        </div>
      </div>
    @endif --}}
  </div>
@endif

@endif
@endforeach
