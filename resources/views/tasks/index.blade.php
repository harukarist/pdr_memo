
@extends('layouts.app')

@section('styles')
  @include('share.flatpickr.styles')
@endsection

@section('content')
  <div class="container-fluid">
    <div class="test" id="heatmap"></div>
    <div class="row pt-4">
      <div class="l-left__wrapper col-md-3 mb-5">
        <div class="p-menu__wrapper">
          <div class="p-menu__project">
            {{-- プロジェクト一覧 --}}
            <ul class="list-group list-group-flush mt-2 mb-4">
              {{-- <li class="list-group-item bg-light p-1">プロジェクト</li> --}}
              {{-- プロジェクトIDが一致する場合はactiveクラスを出力 --}}
              @if(count($projects))
                @foreach($projects as $project)
                <li class="p-menu__item list-group-item py-2 px-3 list-group-item-action {{ $current_project->id === $project->id ? 'list-group-item-info' : '' }}">
                  <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="text-decoration-none">
                    <span style="color:{{ $project->project_color }}">
                    @if($current_project->id === $project->id)
                    <i class="fas fa-folder-open" ></i>
                    {{-- <i class="far fa-folder-open" aria-hidden="true"></i> --}}
                    @else
                    <i class="fas fa-folder mr-1" aria-hidden="true"></i>
                    {{-- <i class="far fa-folder" aria-hidden="true"></i> --}}
                    @endif
                    </span>
                    {{ $project->project_name ?? ''}}
                    {{-- <span class="badge badge-pill badge-light float-right">{{ $project->category->category_name ?? '' }}</span> --}}
    
                    {{-- ステータスと期限日はモデル側で書き換え --}}
                    {{-- <span class="badge float-right {{ $project->category_class }}">{{ $project->category_name ?? ''}}</span> --}}
                  </a>

                  <a href="{{ route('projects.edit',['project_id'=>$project->id]) }}" class="small float-right">Edit</a>
                </li>
                @endforeach
              @endif
              <li class="list-group-item p-1">
                <a href="{{ route('projects.create') }}" class="btn btn-outline-secondary btn-block">
                  プロジェクトを追加
                </a>
              </li>
            </ul>
          </div>
          <div class="p-menu__status">

          </div>
        </div>
      </div>
      <div class="l-right__wrapper col-md-9">
        <div class="p-tasklist__wrapper">
          <div class="p-tasklist__title mb-2">
            {{-- <i class="fas fa-list-ul mr-1" aria-hidden="true"></i> --}}
            <i class="fas fa-folder-open" style="color:{{ $current_project->project_color }}" aria-hidden="true"></i>
            {{ $current_project->project_name ?? ''}} のタスクリスト
          </div>

          {{-- 目標 --}}
          @isset($current_project->project_target)
          <div class="p-tasklist__target bg-white p-2 mb-2">
            {{ $current_project->project_target ?? ''}} 
          </div>
          @endisset

          @if($counter)
          {{-- サマリー --}}
          <div class="p-counter__wrapper bg-white p-2 mx-0 mb-4">
            <div class="row">
              <div class="col">
                <small>
                  <i class="fas fa-medal" aria-hidden="true"></i>
                  達成度<br>
                  <span class="p-counter__number">
                    {{ $counter['reviewed_hours'] ?? 0 }}
                  </span>
                  h /
                  <span class="p-counter__number">
                    {{ $counter['reviewed_count'] ?? 0 }}
                  </span>
                  回
                </small>
              </div>
              <div class="col">
                <small>
                  <i class="far fa-check-circle" aria-hidden="true"></i>
                  完了<br>
                  <span class="p-counter__number">
                    {{ $counter['completed_count'] ?? 0 }}
                  </span>
                  件
                </small>
              </div>
              <div class="col">
                <small>
                  <i class="far fa-circle" aria-hidden="true"></i>
                  実行中<br>
                  <span class="p-counter__number">
                    {{ $counter['doing_count'] ?? 0 }}
                  </span>
                   件
                </small>
              </div>
              <div class="col">
                <small>
                  <i class="far fa-clipboard" aria-hidden="true"></i>
                  残り<br>
                  <span class="p-counter__number">
                    {{ $remained_hours ?? 0 }} 
                  </span>
                  h / 
                  <span class="p-counter__number">
                    {{ $remained_steps ?? 0 }}
                  </span>
                  回
                </small>
              </div>
              {{-- <div class="col">
                Prep済み<br>
                {{ $counter['prepped_count'] ?? 0 }} 件
              </div>
              <div class="col">
                Prep未設定<br>
                {{ $counter['waiting_count'] ?? 0 }} 件
              </div> --}}
            </div>
          </div>
          @endif
           {{-- タスク作成フォーム --}}
          <div class="p-tasklist__create mb-5">
            {{-- バリデーションエラー --}}
            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="m-0">
                  @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <form action="{{ route('tasks.create', ['project_id' => $current_project->id]) }}" method="POST">
              @csrf
              <div class="form-group mb-2">
                <label for="task_name"><i class="fas fa-plus mr-1" aria-hidden="true"></i>タスクを追加</label>
                <input type="text" class="form-control" name="task_name" id="task_name" value="{{ old('task_name') }}" autofocus/>
              </div>
              <div class="form-inline row">
                <div class="form-group form-inline col-auto">
                  <label for="priority">優先度</label>
                  <select name="priority" id="priority" class="form-control ml-2 @error('priority') is-invalid @enderror">
                    @foreach(\App\Task::PRIORITY as $key => $val)
                      <option
                          value="{{ $key }}"
                          {{ $key == old('priority') ? 'selected' : '' }}
                      >
                        {{ $val['priority_name'] }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group form-inline col-auto">
                  <label for="due_date">期限</label>
                  {{-- <input type="text" name="due_date" id="flatpickr" class="form-control ml-2" value="{{ old('due_date') }}"> --}}
                  <input type="date" name="due_date" id="due_date" class="form-control ml-2 @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">

                </div>
                <div class="form-group form-inline col-auto">
                  <button type="submit" class="btn btn-primary">追加</button>
                </div>
              </div>
            </form>
          </div>
          {{-- タスク一覧 --}}
          <div class="p-tasklist__list">
            <div class="btn-group" role="group" aria-label="task-status">
              <a href="{{ route('tasks.index',['project_id' => $current_project->id]) }}" class="btn {{ $has_done ? '' : 'btn-secondary' }}">
                <i class="far fa-circle mr-1" aria-hidden="true"></i>未完了タスク</a>
              <a href="{{ route('tasks.done',['project_id'=>$current_project->id])  }}" class="btn {{ $has_done ? 'btn-secondary' : '' }}">
                <i class="far fa-check-circle mr-1" aria-hidden="true"></i>完了済み</a>
            </div>
          
            {{-- vueコンポーネント --}}
            {{-- <task-item></task-item> --}}
            @if(count($tasks))
              @foreach($tasks as $task)
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
          </div>
        </div>
        <div class="my-4 d-flex justify-content-center">
          {{-- ページネーションリンク --}}
          @if(count($tasks))
          {{ $tasks->links() }}
          @endif
      </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @include('share.flatpickr.scripts')
@endsection
