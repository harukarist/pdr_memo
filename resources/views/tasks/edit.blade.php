@extends('layouts.app')

@section('styles')
  @include('share.flatpickr.styles')
@endsection

@section('content')
  <div class="container">
    <div class="row">
      <div class="col col-md-offset-3 col-md-6">
        <nav class="panel panel-default">
          <div class="panel-heading">タスクを編集する</div>
          <div class="panel-body">
            @if($errors->any())
              <div class="alert alert-danger">
                @foreach($errors->all() as $message)
                  <p>{{ $message }}</p>
                @endforeach
              </div>
            @endif
            <form
                action="{{ route('tasks.edit', ['project_id' => $task->project_id, 'task_id' => $task->id]) }}"
                method="POST"
            >
              @method('PATCH')
              @csrf
              <div class="form-group">
                <label for="task_name">タスク名</label>
                {{-- 直前の入力値がない場合はテーブルの値を表示 --}}
                <input type="text" class="form-control @error('task_name') is-invalid @enderror" name="task_name" id="task_name"
                       value="{{ old('task_name') ?? $task->task_name }}" />
              </div>
              <div class="form-group">
                <label for="status">ステータス</label>
                <select name="status" id="status" class="form-control">
                  @foreach(\App\Task::STATUS as $key => $val)
                    <option
                        value="{{ $key }}"
                        {{ $key == old('status', $task->status) ? 'selected' : '' }}
                    >
                      {{ $val['status_name'] }}
                    </option>
                  @endforeach
                </select>
              </div>
              {{-- <div class="form-group">
                <label for="due_date">期限</label>
                <input type="text" class="form-control" name="due_date" id="due_date"
                       value="{{ old('due_date') ?? $task->formatted_due_date }}" />
              </div> --}}
              <div class="text-right">
                <button type="submit" class="btn btn-primary">送信</button>
              </div>
            </form>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @include('share.flatpickr.scripts')
@endsection
