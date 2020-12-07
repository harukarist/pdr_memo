@if(count($projects))
  @foreach($projects as $project)
    @if(count($project->tasks))
      @foreach($project->tasks as $task)
      <div class="p-tasklist__item">
        {{-- Task --}}
        <div class="p-task__wrapper row justify-content-between p-2 mt-3 mx-0 bg-white">
          <div class="d-flex col-sm-9">
            <div class="p-task__checkbox pr-2">
              @if(!empty($task->status) && $task->status == 4)
              <i class="far fa-check-circle {{ $task->priority_class ?? '' }}" aria-hidden="true"></i>
              @else
              <i class="far fa-circle {{ $task->priority_class ?? '' }}" aria-hidden="true"></i>
              @endif
            </div>
            <div class="p-task__taskname">
              {{-- タスク名 --}}
              @if($task->status == 4)<del>@endif
              {{ $task->task_name ?? '' }}
              @if($task->status == 4)</del>@endif
              @if($task->status != 4 && $task->priority > 0)
              <div class="p-task__priority d-inline-block">
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
          <div class="p-task__action col-sm-3 text-right">
            <a href="{{ route('tasks.edit', ['project_id' => $task->project_id,'task_id' => $task->id]) }}">
              <i class="fas fa-pencil-alt small px-2" aria-hidden="true"></i>
            </a>
            <a href="{{ route('preps.create', ['project_id' => $task->project_id,'task_id' => $task->id]) }}" class="btn px-1 py-0 {{ $task->status > 1 ? 'btn-light' : 'btn-success' }}">
              <i class="fas fa-caret-right" aria-hidden="true"></i>
              <small>{{ $task->status > 1 ? 'Prep追加' : 'Prep !' }}</small>
            </a>
          </div>
        </div>
        
        @if(count($task->preps))
        <div class="bg-white px-2 pb-2">
          @foreach($task->preps as $prep)
          <div class="p-pdr__wrapper border-top border-bottom row m-0 bg-light p-2">
            {{-- Prep --}}
            <div class="p-pdr__prep-wrapper col-md p-1">
              <div class="p-pdr__prep-detail d-flex justify-content-between mb-2">
                <small class="pt-1">
                  <span>
                    <i class="far fa-clipboard" aria-hidden="true"></i>
                    Prep-{{ $loop->iteration }}
                  </span>
                  
                  <mark class="mr-2 text-primary">
                  {{ $prep->unit_time }}分 × 
                  @foreach(range(1,$prep->estimated_steps) as $num)
                  <i class="fas fa-stopwatch" aria-hidden="true"></i>
                  @endforeach
                  {{-- {{ $prep->estimated_steps ?? '' }} = --}}
                  = {{ ($prep->unit_time)*($prep->estimated_steps) ?? '' }}分
                  </mark>
                  <span class="badge badge-light">{{ $prep->category->category_name ?? '' }}</span>
                </small>
                <a href="{{ route('preps.edit', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $prep->id ]) }}" class="mr-2"><small><i class="fas fa-pencil-alt" aria-hidden="true"></i></small></a>
              </div>
              <div class="p-pdr__prep-text">
                <small><p class="mb-1">{!! nl2br(e($prep->prep_text)) !!}</p></small>
              </div>
              <div class="p-pdr__do d-flex justify-content-center py-2">
                {{-- Do --}}
                <a href="{{ route('preps.do', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $prep->id]) }}" class="btn px-1 py-0 my-2 {{ $task->status == 4 ? 'btn-secondary' : 'btn-success' }}">
                <i class="fas fa-caret-right mx-1" aria-hidden="true"></i>Do !</a>

              </div>
            </div>
            {{-- Review --}}
            @if(count($prep->reviews))
            <div class="p-pdr__review-reviews col-md p-1">
              @foreach($prep->reviews()->orderBy('started_at','asc')->get() as $review)
              <div class="p-pdr__review-wrapper mb-2">
                <div class="p-pdr__review-header d-flex justify-content-between mb-2 border-bottom">
                  <small>
                    <span>
                      <i class="fas fa-medal" aria-hidden="true"></i>
                      Review-{{ $loop->iteration }}
                    </span>
                    <mark class="mr-2 text-primary">
                    <i class="fas fa-stopwatch ml-1" aria-hidden="true"></i>
                      {{ $review->actual_time ?? 0 }}分
                    </mark>
                    <mark class="text-success mr-2">
                    <i class="fas fa-spa" aria-hidden="true"></i>
                    {{ $review->flow_level ?? '-' }}
                    </mark>

                    <span class="badge badge-light">{{ $review->category->category_name ?? '' }}
                    </span>
                  </small>
                  <a href="{{ route('reviews.edit', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $review->prep_id,'review_id'=>$review->id ]) }}" class="mr-2"><small><i class="fas fa-pencil-alt" aria-hidden="true"></i></small></a>
                </div>
                <div class="p-pdr__review-text">
                  <small>
                    <p class="mb-0">
                    {!! nl2br(e($review->review_text)) !!}
                    </p>
                    <small class="text-muted mb-1">
                      {{ $review->started_at ?? '' }}〜
                    </small>
                    @if($review->good_text||$review->problem_text||$review->try_text)
                      <div class="p-pdr__review-kpt border p-1 mt-1 mb-2">
                        @isset($review->good_text)
                          <p class="mb-1">
                            <i class="far fa-laugh-wink"></i>
                            <span class="font-weight-bold">Good/Keep</span><br>
                            {!! nl2br(e($review->good_text)) !!}
                          </p>
                        @endisset
                        @isset($review->problem_text)
                        <p class="mb-1">
                          <i class="fas fa-bolt"></i>
                          <span class="font-weight-bold">Problem</span><br>
                          {!! nl2br(e($review->problem_text)) !!}
                        </p>
                        @endisset
                        @isset($review->try_text)
                        <p class="mb-1">
                          <i class="far fa-lightbulb"></i>
                          <span class="font-weight-bold">Try</span><br>
                          {!! nl2br(e($review->try_text)) !!}
                        </p>
                        @endisset

                      </div>
                    @endif
                  </small>
                </div>
                <div class="p-pdr__review-footer d-flex pt-1">

                </div>
              </div>   
              @endforeach
            </div>
            @endif
          </div>
          @endforeach
        </div>
        @endif
      </div>
      @endforeach
    @endif
  @endforeach
@endif
