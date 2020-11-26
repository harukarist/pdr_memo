@extends('layouts.app')

@section('styles')
  @include('share.flatpickr.styles')
@endsection

@section('content')
  <div class="container">
    <h5 class="mb-4">タスクを修正する</h5>
    {{-- 削除ボタン --}}
    <div class="p-delete text-right">
      <form action="{{ route('tasks.delete', ['project_id'=>$editing_task->project_id, 'task_id' => $editing_task->id]) }}" method="post">
        @method('DELETE')
        @csrf
      <button type="button" class="btn btn-outline-secondary btn-sm mb-3" data-toggle="modal" data-target="#modal1">
        このタスクを削除する
      </button>
      <div class="modal fade text-left" id="modal1" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="label1">このタスクを削除しますか？</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              タスクを削除すると、予定や振り返りのデータも一緒に削除されます。<br>
              タスクは編集画面から変更できます。本当にこのタスクを削除しますか？
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">
                No
              </button>
              <button type="submit" class="btn btn-danger">Yes</button>
            </div>
          </div>
        </div>
      </div>
      </form>
    </div>
    <div class="form_edit">
      @if($errors->any())
        <div class="alert alert-danger">
          @foreach($errors->all() as $message)
            <p>{{ $message }}</p>
          @endforeach
        </div>
      @endif
      <form action="{{ route('tasks.edit', ['project_id' => $editing_task->project_id, 'task_id' => $editing_task->id]) }}"  method="POST">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="task_name">タスク名</label>
          {{-- 直前の入力値がない場合はテーブルの値を表示 --}}
          <input type="text" class="form-control @error('task_name') is-invalid @enderror" name="task_name" id="task_name"
                  value="{{ old('task_name') ?? $editing_task->task_name }}" />
        </div>
        <div class="form-group">
          <label for="status">ステータス</label>
          <select name="status" id="status" class="form-control">
            @foreach(\App\Task::STATUS as $key => $val)
              <option
                  value="{{ $key }}"
                  {{ $key == old('status', $editing_task->status) ? 'selected' : '' }}
              >
                {{ $val['status_name'] }}
              </option>
            @endforeach
          </select>
        </div>
        {{-- <div class="form-group">
          <label for="due_date">期限</label>
          <input type="text" class="form-control" name="due_date" id="due_date"
                  value="{{ old('due_date') ?? $editing_task->formatted_due_date }}" />
        </div> --}}
        <div class="text-right">
          <button type="submit" class="btn btn-primary">送信</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  @include('share.flatpickr.scripts')
@endsection
