@foreach($reviews as $review)
@if(isset($review->id))
  <div class="p-report__reviews bg-light p-2 mb-3">

    {{-- Review-Header --}}
    <div class="p-report__review-header d-flex justify-content-between mx-2 mb-2 border-bottom">
      <div class="p-report__review-title">
        <i class="fas fa-medal" aria-hidden="true"></i>
        Review-{{ $loop->remaining + 1}}
      </div>
      <div class="p-report__review-details">
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
        @if($review->prep && $review->prep->task)
          <a href="{{ route('reviews.edit', ['project_id' => $review->prep->task->project_id,'task_id' => $review->prep->task_id, 'prep_id' => $review->prep_id,'review_id'=>$review->id ]) }}" class="mr-2"><small><i class="p-task__icon fas fa-pencil-alt" aria-hidden="true"></i></small></a>
        @endif
      </div>
    </div>

    {{-- タスク --}}
    @if(isset($review->prep->task->id))
    <div class="p-report__task-item d-flex bg-white p-2 mb-2 mx-2">
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
    <div class="p-report__review-text mx-2 px-2">
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

    <reports-prep>
      <template v-slot:prep>
          {{-- Prep --}}
          @if(isset($review->prep->id))
            <div class="p-report__prep-header d-flex justify-content-between mx-2 mb-2 border-bottom">
              @php
                $prep = $review->prep;
              @endphp
              <div class="p-report__prep-title">
                  <i class="far fa-clipboard mr-1" aria-hidden="true"></i>
                  Prep
              </div>
              <div class="p-report__prep-details">
                {{ $prep->unit_time }}分 × 
                @foreach(range(1,$prep->estimated_steps) as $num)
                <i class="fas fa-stopwatch" aria-hidden="true"></i>
                @endforeach
                = {{ ($prep->unit_time)*($prep->estimated_steps) ?? '' }}分
              </div>
            </div>
            <div class="p-report__prep-text mx-2 px-2 mb-4">
              <p class="mb-1">{!! nl2br(e($prep->prep_text)) !!}</p>
            </div>
          @endif
      </template>
    </reports-prep>

  </div>
@endif
@endforeach
