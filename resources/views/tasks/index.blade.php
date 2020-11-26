
@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-header">プロジェクト</div>
          <div class="card-body">
            <ul class="list-group mb-2">
              {{-- プロジェクト一覧 --}}
              {{-- プロジェクトIDが一致する場合はactiveクラスを出力 --}}
              @foreach($projects as $project)
              <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="list-group-item list-group-item-action {{ $current_project_id === $project->id ? 'active' : '' }}">
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
        <div class="card">
          <div class="card-header">これまでの合計</div>
          <div class="card-body">
            {{ $days_count }}日目（開始日：{{ $first_date->format('Y/m/d') ?? '' }}）<br>
            達成時間：{{ $reviewed_hours ?? 0 }}h<br>
            達成ステップ：{{ $reviewed_count ?? 0 }}回<br>
            完了タスク：{{ $completed_count ?? 0 }}件<br>
            <hr>
            今後の予定：{{ $remained_hours ?? 0 }}h / {{ $remained_steps ?? 0 }}回<br>
            進行中：{{ $doing_count ?? 0 }}件<br>
            Prep済み：{{ $waiting_count ?? 0 }}件<br>
            Prep未設定：{{ $nopreps_count ?? 0 }}件
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="card">
          <div class="card-header">タスク</div>
          <div class="card-body">
           {{-- タスク作成 --}}
          <div class="p-task-create">
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
            <form action="{{ route('tasks.add', ['project_id' => $current_project_id]) }}" method="POST">
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
            <table class="table">
              <tbody>
                @foreach($tasks as $task)
                {{-- @if($task->status < 3) --}}
                <tr>
                  <td>
                    {{-- チェックボックス --}}
                    <div class="p-list__checkbox">
                      @if($task->status == 3)
                      <i class="far fa-check-square icon-checkbox" aria-hidden="true"></i>
                      @else
                      <i class="far fa-square icon-checkbox" aria-hidden="true"></i></td>
                      @endif
                    </div>
                  <td>
                    <div class="p-guide__contents text-justify p-0">
                      <div class="p-list__taskname">
                        {{-- タスク名 --}}
                        @if($task->status == 3)<del>@endif
                        {{ $task->task_name }}
                        @if($task->status == 3)</del>@endif
                        <span class="badge {{ $task->status_class }}">{{ $task->status_name }}</span>
                      </div>
                      <div class="p-list__task-detail mb-2">
                        <small class="ml-1 text-muted">{{ $task->updated_at }}</small>
                        <a href="{{ route('tasks.edit', ['project_id' => $task->project_id,'task_id' => $task->id]) }}" class="small ml-2">Edit</a><br>
                      </div>
                    </div>
                    @forelse($task->preps as $prep)
                      <div class="p-list__pdr row m-1 py-1">
                        <div class="p-list__prep col-sm p-0">
                          <div class="p-list__prep-detail mr-2">
                            <small>
                              Prep-{{ $loop->iteration }}
                              <mark class="mr-2">
                              {{ $prep->unit_time }}分 × 
                              {{ $prep->estimated_steps }} =
                              {{ ($prep->unit_time)*($prep->estimated_steps) }}分
                              </mark>
                              <span class="badge badge-light">{{ $prep->category->category_name }}</span>
                            </small>
                          </div>
                          <div class="p-list__prep-text">
                            <small><p class="mb-1">{!! nl2br(e($prep->prep_text)) !!}</p></small>
                          </div>
                          <div class="d-flex justify-content-between pt-2">
                            <small class="text-muted pt-1">{{ $prep->updated_at }}</small>
                            <a href="{{ route('preps.edit', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $prep->id ]) }}" class="mr-2"><small>Edit</small></a>
                          </div>
                        </div>
                        {{-- Do --}}
                        <div class="p-list__do col-auto px-3 align-self-center">
                          <a href="{{ route('preps.do', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $prep->id]) }}" class="btn px-1 py-0 my-2 @if($task->status == 3) btn-light @else btn-primary @endif">Do!</a>
                        </div>
                        {{-- Review --}}
                        <div class="p-list__review col-sm p-0">
                          @forelse($prep->reviews as $review)
                          <div class="p-list__review-detail mb-2">
                            <small>
                              Review-{{ $review->step_counter }}:
                              <mark class="mr-2">
                              {{ $review->actual_time }}分
                              </mark>
                              <span class="badge badge-light">{{ $prep->category->category_name }}
                              </span>
                            </small>
                          </div>
                          <div class="p-list__review-text">
                            <small>
                              <p class="mb-1">
                              {!! nl2br(e($review->review_text)) !!}
                              </p>
                              @isset($review->good_text)
                              <p class="mb-1">
                                Good/Keep：{!! nl2br(e($review->good_text)) !!}
                              </p>
                              @endisset
                              @isset($review->problem_text)
                              <p class="mb-1">
                                Problem：{!! nl2br(e($review->problem_text)) !!}
                              </p>
                              @endisset
                              @isset($review->try_text)
                              <p class="mb-1">
                                Try：{!! nl2br(e($review->try_text)) !!}
                              </p>
                              @endisset
                            </small>
                          </div>
                          <div class="d-flex justify-content-between pt-2">
                            <small class="text-muted pt-1">{{ $review->updated_at}}</small>
                            <a href="{{ route('reviews.edit', ['project_id' => $task->project_id,'task_id' => $task->id, 'prep_id' => $review->prep_id,'review_id'=>$review->id ]) }}" class="mr-2"><small>Edit</small></a>
                          </div>
                          @empty
                          @endforelse
                        </div>
                      </div>
                    @empty
                    @endforelse
                    <a href="{{ route('preps.create', ['project_id' => $task->project_id,'task_id' => $task->id]) }}" class="btn btn-light px-1 py-0">Prepを追加</a>
                  </td>
                  <td>{{ $task->formatted_due_date }}</td>
                </tr>
                {{-- @endif --}}
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="my-4 row justify-content-center">
          {{-- ページネーションリンク --}}
          @if($tasks)
          {{ $tasks->links() }}
          @endif
      </div>
      </div>
    </div>
  </div>
@endsection
