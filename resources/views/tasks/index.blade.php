
@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row pt-4">
      <div class="l-left__wrapper col-md-3 mb-5">
        <div class="p-menu__wrapper">
          <div class="p-menu__project">
            {{-- プロジェクト一覧 --}}
            <ul class="list-group list-group-flush mt-2 mb-4">
              {{-- <li class="list-group-item bg-light p-1">プロジェクト</li> --}}
              {{-- プロジェクトIDが一致する場合はactiveクラスを出力 --}}
              @foreach($projects as $project)
              <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="list-group-item py-2 px-3 list-group-item-action {{ $current_project->id === $project->id ? 'active' : '' }}">
                @if($current_project->id === $project->id)
                <i class="fas fa-folder-open"></i>
                {{-- <i class="far fa-folder-open"></i> --}}
                @else
                <i class="fas fa-folder mr-1"></i>
                {{-- <i class="far fa-folder"></i> --}}
                @endif
                {{ $project->project_name ?? ''}}
                {{-- <span class="badge badge-pill badge-light float-right">{{ $project->category->category_name ?? '' }}</span> --}}

                {{-- ステータスと期限日はモデル側で書き換え --}}
                {{-- <span class="badge float-right {{ $project->category_class }}">{{ $project->category_name ?? ''}}</span> --}}
              </a>
              @endforeach
              <li class="list-group-item p-1">
                <a href="{{ route('projects.create') }}" class="btn btn-outline-secondary btn-block">
                  プロジェクトを編集
                </a>
              </li>
            </ul>

          </div>
          <div class="p-menu__status">
            <ul class="list-group list-group-flush">
              <a href="{ route('records.index') }}" class="list-group-item py-2 px-3 list-group-item-action">
                <i class="far fa-circle mr-1" aria-hidden="true"></i>未完了タスク</a>
              <a href="{ route('records.index') }}" class="list-group-item py-2 px-3 list-group-item-action">
                <i class="far fa-check-circle mr-1" aria-hidden="true"></i>完了済み</a>
            </ul>
          </div>
        </div>
      </div>
      <div class="l-right__wrapper col-md-9">
        <div class="p-tasklist__wrapper">
          <div class="p-tasklist__title mb-2"><i class="fas fa-list-ul mr-1"></i>
            {{ $current_project->project_name ?? ''}} のタスクリスト</div>
          <div class="counter__wrapper bg-white p-2 mx-0 mb-4 ">
            <div class="row">
              <div class="col">
                達成時間：{{ $counter['reviewed_hours'] ?? 0 }}h
              </div>
              <div class="col">
                達成ステップ：{{ $counter['reviewed_count'] ?? 0 }}回
              </div>
              <div class="col">
                完了タスク：{{ $counter['completed_count'] ?? 0 }}件
              </div>
            </div>
            <div class="row">
              <div class="col">
                実行中：{{ $counter['doing_count'] ?? 0 }}件
              </div>
              <div class="col">
                Prep済み：{{ $counter['prepped_count'] ?? 0 }}件
              </div>
              <div class="col">
                Prep未設定：{{ $counter['waiting_count'] ?? 0 }}件
              </div>
            </div>
            <div class="row">
              <div class="col">
                今後の予定：{{ $remained_hours ?? 0 }}h / {{ $remained_steps ?? 0 }}回
              </div>
            </div>

          </div>
           {{-- タスク作成 --}}
          <div class="p-tasklist__create mb-3">
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
            <form action="{{ route('tasks.add', ['project_id' => $current_project->id]) }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="task_name"><i class="fas fa-plus mr-1"></i>タスクを追加</label>
                <input type="text" class="form-control" name="task_name" id="task_name" value="{{ old('task_name') }}" />
              </div>
              {{-- <div class="form-group">
                <label for="due_date">期限</label>
                <input type="text" class="form-control" name="due_date" id="due_date" value="{{ old('due_date') }}" />
              </div> --}}
              <div class="text-right">
                <button type="submit" class="btn btn-primary">送信</button>
              </div>
            </form>
          </div>
          {{-- タスク一覧 --}}
          <div class="p-tasklist__list">
            @forelse($tasks as $task)
            {{-- @if($task->status < 4) --}}
            <div class="p-tasklist__item">
              {{-- Task --}}
              <div class="p-task__wrapper d-flex flex-column flex-sm-row justify-content-between p-2 mt-3 bg-white">
                <div class="p-task__contents d-flex">
                  {{-- チェックボックス --}}
                  <div class="p-task__checkbox pr-2">
                    @if(!empty($task->status) && $task->status == 4)
                    {{-- <i class="far fa-check-square {{ $task->priority_class ?? '' }}" aria-hidden="true"></i> --}}
                    {{-- <i class="fas fa-check-circle {{ $task->priority_class ?? '' }}" aria-hidden="true"></i> --}}
                    <i class="far fa-check-circle {{ $task->priority_class ?? '' }}" aria-hidden="true"></i>
                    @else
                    {{-- <i class="far fa-square {{ $task->priority_class ?? '' }}" aria-hidden="true"></i> --}}
                    {{-- <i class="fas fa-circle {{ $task->priority_class ?? '' }}" aria-hidden="true"></i> --}}
                    <i class="far fa-circle {{ $task->priority_class ?? '' }}" aria-hidden="true"></i>
                    @endif
                  </div>
                  <div class="p-task__taskname">
                    {{-- タスク名 --}}
                    @if($task->status == 4)<del>@endif
                    {{ $task->task_name ?? '' }}
                    @if($task->status == 4)</del>@endif
                    @if($task->status != 4 && $task->priority > 0)
                    <small class="ml-2 {{ $task->priority_class ?? '' }}">
                      @forelse(range(1,$task->priority) as $num)
                      <i class="fas fa-star" aria-hidden="true"></i>
                      @empty
                      @endforelse
                    </small>
                    @endif
                    {{ $task->formatted_due_date ?? '' }}
                  </div>
                  <div class="p-task__detail">
                    {{-- タスク詳細 --}}
                    {{-- <small class="ml-3 text-muted">{{ $task->updated_at ?? '' }}</small> --}}
                  </div>
                </div>
                <div class="p-task__btn text-right">
                  <a href="{{ route('tasks.edit', ['project_id' => $task->project_id,'task_id' => $task->id]) }}">
                    <i class="fas fa-pencil-alt small px-2" aria-hidden="true"></i>
                  </a>
                  <a href="{{ route('preps.create', ['project_id' => $task->project_id,'task_id' => $task->id]) }}" class="btn px-1 py-0 {{ $task->status > 1 ? 'btn-secondary' : 'btn-success' }}">
                    <i class="fas fa-caret-right mx-1"></i>Prep !</a>
                </div>
              </div>
              

              <div class="bg-white px-2 pb-2">
              @forelse($task->preps as $prep)
                <div class="p-pdr__wrapper border-top border-bottom row m-0 bg-light p-2">
                  {{-- Prep --}}
                  <div class="p-pdr__prep-wrapper col-md p-0">
                    <div class="p-pdr__prep-detail d-flex justify-content-between mb-2">
                      <small class="pt-1">
                        {{-- <span>Prep-{{ $loop->iteration }}</span> --}}
                        <span>
                          <i class="far fa-clipboard"></i>
                          {{-- <i class="far fa-caret-square-right mr-2"></i>
                          <i class="fas fa-arrow-alt-circle-right"></i>
                          <i class="fas fa-caret-square-right"></i>
                          <i class="fas fa-chevron-circle-right"></i> --}}
                          Prep</span>
                        
                        <mark class="mr-2">
                        {{ $prep->unit_time }}分 × 
                        @foreach(range(1,$prep->estimated_steps) as $num)
                        <i class="fas fa-stopwatch text-danger" aria-hidden="true"></i>
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
                    <div class="d-flex justify-content-end pt-2">
                      {{-- <small class="text-muted pt-1">{{ $prep->updated_at ?? '' }}</small> --}}
                    </div>
                  </div>
                  {{-- Do --}}
                  <div class="p-pdr__do px-3 text-center col-md-auto align-self-center">
                    <a href="{{ route('preps.do', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $prep->id]) }}" class="btn px-1 py-0 my-2 {{ $task->status == 4 ? 'btn-secondary' : 'btn-success' }}">
                      <i class="fas fa-caret-right mx-1"></i>Do !</a>
                  </div>
                  {{-- Review --}}
                  <div class="p-pdr__review-reviews col-md p-0">
                    @forelse($prep->reviews as $review)
                    <div class="p-pdr__review-wrapper mb-2">
                      <div class="p-pdr__review-header d-flex justify-content-between mb-2 border-bottom">
                        <small>
                          <span>
                            <i class="fas fa-medal"></i>
                            Review-{{ $review->step_counter ?? '' }}</span>
                          <mark class="mr-2">
                          {{ $review->actual_time ?? '' }}分
                          </mark>
                          @foreach(range(1,$review->step_counter) as $num)
                          <i class="fas fa-stopwatch text-danger" aria-hidden="true"></i>
                          @endforeach
                          @if(($prep->estimated_steps)-($review->step_counter)>=1)
                          @foreach(range(1,($prep->estimated_steps)-($review->step_counter)) as $num)
                          <i class="fas fa-stopwatch text-dark" aria-hidden="true"></i>
                          @endforeach
                          @endif
                          <span class="badge badge-light">{{ $review->category->category_name ?? '' }}
                          </span>
                        </small>
                        {{-- <small class="text-muted pt-1">{{ $review->updated_at}}</small> --}}
                        <a href="{{ route('reviews.edit', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $review->prep_id,'review_id'=>$review->id ]) }}" class="mr-2"><small><i class="fas fa-pencil-alt" aria-hidden="true"></i></small></a>
                      </div>
                      <div class="p-pdr__review-text">
                        <small>
                          <p class="mb-1">
                          {!! nl2br(e($review->review_text)) !!}
                          </p>
                          @if($review->good_text||$review->problem_text||$review->try_text)
                            <div class="p-pdr__review-kpt border p-1">
                              @isset($review->good_text)
                                <p class="mb-1">
                                  <i class="far fa-laugh-wink"></i>
                                  Good/Keep：<br>{!! nl2br(e($review->good_text)) !!}
                                </p>
                              @endisset
                              @isset($review->problem_text)
                              <p class="mb-1">
                                <i class="fas fa-bolt"></i>
                                Problem：<br>{!! nl2br(e($review->problem_text)) !!}
                              </p>
                              @endisset
                              @isset($review->try_text)
                              <p class="mb-1">
                                <i class="far fa-lightbulb"></i>
                                Try：<br>{!! nl2br(e($review->try_text)) !!}
                              </p>
                              @endisset

                            </div>
                          @endif
                        </small>
                      </div>
                      <div class="p-pdr__review-footer d-flex justify-content-end pt-2">
                        <small class="text-muted pt-1">{{ $review->updated_at}}</small>
                      </div>
                    </div>   
                    @empty
                    @endforelse
                  </div>
                </div>
                @empty
                @endforelse
              </div>
            </div>
            {{-- @endif --}}
            @empty
            @endforelse
          </div>
        </div>
        <div class="my-4 d-flex justify-content-center">
          {{-- ページネーションリンク --}}
          @if($tasks)
          {{ $tasks->links() }}
          @endif
      </div>
      </div>
    </div>
  </div>
@endsection
