
@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row pt-4">
      <div class="l-left__wrapper col-md-3">
        <div class="p-project__wrapper">
          <h3 class="p-project__title">プロジェクト</h3>
          <div class="p-project__list">
            <ul class="list-group mb-2">
              {{-- プロジェクト一覧 --}}
              {{-- プロジェクトIDが一致する場合はactiveクラスを出力 --}}
              @foreach($projects as $project)
              <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="list-group-item list-group-item-action {{ $current_project->id === $project->id ? 'active' : '' }}">
                {{ $project->project_name }}
                {{-- <span class="badge badge-pill badge-light float-right">{{ $project->category->category_name }}</span> --}}

                {{-- ステータスと期限日はモデル側で書き換え --}}
                <span class="badge float-right {{ $project->category_class }}">{{ $project->category_name }}</span>
              </a>
              @endforeach
            </ul>
            <a href="{{ route('projects.create') }}" class="btn btn-outline-secondary btn-block">
              プロジェクトを追加
            </a>
          </div>
        </div>
        <div class="p-summary">
          <div class="p-summary__title">これまでの合計</div>
          <div class="p-summary__body">
            {{ $days_count ?? 0 }}日目
            {{-- @isset($first_date)（開始日： {{ $first_date->format('Y/m/d') ?? 0 }}）@endisset<br> --}}
            達成時間：{{ $counter['reviewed_hours'] ?? 0 }}h<br>
            達成ステップ：{{ $counter['reviewed_count'] ?? 0 }}回<br>
            完了タスク：{{ $counter['completed_count'] ?? 0 }}件<br>
            <hr>
            今後の予定：{{ $remained_hours ?? 0 }}h / {{ $remained_steps ?? 0 }}回<br>
            進行中：{{ $counter['$doing_count'] ?? 0 }}件<br>
            Prep済み：{{ $waiting_count ?? 0 }}件<br>
            Prep未設定：{{ $nopreps_count ?? 0 }}件
          </div>
        </div>
      </div>
      <div class="l-right__wrapper col-md-9">
        <div class="p-tasklist__wrapper">
          <div class="p-tasklist__title mb-2">{{ $current_project->project_name }} のタスクリスト</div>
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
                Prep済み：{{ $counter['doing_count'] ?? 0 }}件

              </div>
              <div class="col">
                Prep未設定：{{ $counter['waiting_count'] ?? 0 }}件
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
                <label for="task_name">タスクを追加</label>
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
            @foreach($tasks as $task)
            {{-- @if($task->status < 4) --}}
            <div class="p-tasklist__item">
              {{-- Task --}}
              <div class="p-task__wrapper d-flex justify-content-between p-2 mt-3 bg-white">
                <div class="p-task__contents d-flex">
                  {{-- チェックボックス --}}
                  <div class="p-task__checkbox pr-2">
                    @if($task->status == 4)
                    <i class="far fa-check-square icon-checkbox" aria-hidden="true"></i>
                    @else
                    <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
                    @endif
                  </div>
                  <div class="p-task__taskname">
                    {{-- タスク名 --}}
                    @if($task->status == 4)<del>@endif
                    {{ $task->task_name }}
                    @if($task->status == 4)</del>@endif
                    {{ $task->formatted_due_date }}
                  </div>
                  <div class="p-task__detail">
                    {{-- タスク詳細 --}}
                    {{-- <small class="ml-3 text-muted">{{ $task->updated_at }}</small> --}}
                    <a href="{{ route('tasks.edit', ['project_id' => $task->project_id,'task_id' => $task->id]) }}" class="small ml-2">Edit</a><br>
                  </div>
                </div>
                <div class="p-task__btn">
                  <a href="{{ route('preps.create', ['project_id' => $task->project_id,'task_id' => $task->id]) }}" class="btn btn-light px-1 py-0">Prep!</a>
                </div>

              </div>

              <div class="bg-white px-2 pb-2">
              @forelse($task->preps as $prep)
                <div class="p-pdr__wrapper border-top border-bottom row m-0 bg-light p-2">
                  {{-- Prep --}}
                  <div class="p-pdr__prep-wrapper col-md p-0">
                    <div class="p-pdr__prep-detail d-flex justify-content-between mb-2">
                      <small class="pt-1">
                        <span>Prep-{{ $loop->iteration }}</span>
                        <mark class="mr-2">
                        {{ $prep->unit_time }}分 × 
                        {{ $prep->estimated_steps }} =
                        {{ ($prep->unit_time)*($prep->estimated_steps) }}分
                        </mark>
                        <span class="badge badge-light">{{ $prep->category->category_name }}</span>
                      </small>
                      <a href="{{ route('preps.edit', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $prep->id ]) }}" class="mr-2"><small>Edit</small></a>
                    </div>
                    <div class="p-pdr__prep-text">
                      <small><p class="mb-1">{!! nl2br(e($prep->prep_text)) !!}</p></small>
                    </div>
                    <div class="d-flex justify-content-end pt-2">
                      {{-- <small class="text-muted pt-1">{{ $prep->updated_at }}</small> --}}
                    </div>
                  </div>
                  {{-- Do --}}
                  <div class="p-pdr__do px-3 text-center col-md-auto align-self-center">
                    <a href="{{ route('preps.do', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $prep->id]) }}" class="btn px-1 py-0 my-2 @if($task->status == 3) btn-light @else btn-primary @endif">Do!</a>
                  </div>
                  {{-- Review --}}
                  <div class="p-pdr__review-reviews col-md p-0">
                    @forelse($prep->reviews as $review)
                    <div class="p-pdr__review-wrapper mb-2">
                      <div class="p-pdr__review-header d-flex justify-content-between mb-2 border-bottom">
                        <small>
                          <span>Review-{{ $review->step_counter }}</span>
                          <mark class="mr-2">
                          {{ $review->actual_time }}分
                          </mark>
                          <span class="badge badge-light">{{ $review->category->category_name }}
                          </span>
                        </small>
                        {{-- <small class="text-muted pt-1">{{ $review->updated_at}}</small> --}}
                        <a href="{{ route('reviews.edit', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $review->prep_id,'review_id'=>$review->id ]) }}" class="mr-2"><small>Edit</small></a>
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
                                  Good/Keep：<br>{!! nl2br(e($review->good_text)) !!}
                                </p>
                              @endisset
                              @isset($review->problem_text)
                              <p class="mb-1">
                                Problem：<br>{!! nl2br(e($review->problem_text)) !!}
                              </p>
                              @endisset
                              @isset($review->try_text)
                              <p class="mb-1">
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
            @endforeach
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
