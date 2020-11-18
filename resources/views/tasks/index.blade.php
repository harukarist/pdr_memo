@extends('layout')

@section('content')
  <div class="container p-3">
    <div class="row">
      {{-- プロジェクト一覧 --}}
      <div class="col col-md-4">
        <div class="card">
          <div class="card-header">Project</div>
          <div class="card-body">
            <div class="list-group mb-2">
              {{-- プロジェクトIDが一致する場合はactiveクラスを出力 --}}
              @foreach($projects as $project)
              <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="list-group-item  {{ $current_project_id === $project->id ? 'active' : '' }}">
                {{ $project->project_name }}
              </a>
              @endforeach
            </div>
            <a href="{{ route('projects.create') }}" class="btn btn-outline-secondary btn-block">
              プロジェクトを追加する
            </a>
          </div>
        </div>
      </div>
      {{-- タスク一覧 --}}
      <div class="col col-md-8">
        <div class="card">
          <div class="card-header">Task</div>
          <div class="card-body">
            <table class="table">
              <thead>
              <tr>
                <th>タイトル</th>
                <th>状態</th>
                <th>期限</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
                @foreach($tasks as $task)
                <tr>
                  <td>{{ $task->task_name }}</td>
                  <td>
                    {{-- ステータスと期限日はモデル側で書き換え --}}
                      <span class="badge {{ $task->status_class }}">{{ $task->status_name }}</span>
                    </td>
                    <td>{{ $task->formatted_due_date }}</td>
                    <td><a href="#">編集</a></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <a href="{{ route('tasks.create', ['project_id' => $current_project_id]) }}" class="btn btn-outline-secondary btn-block">
              タスクを追加する
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
