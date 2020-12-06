@extends('layouts.app')

@section('styles')
  @include('plugins.flatpickr.styles')
@endsection

@section('content')
  <div class="container c-container">
    <div class="row">
      <div class="col col-md-offset-3 col-md-6">
        <div class="card">
          <div class="card-header">タスクを追加する</div>
          <div class="card-body">
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
            <form action="{{ route('tasks.create', ['project_id' => $project_id]) }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="task_name">タスク名</label>
                <input type="text" class="form-control" name="task_name" id="task_name" value="{{ old('task_name') }}" />
              </div>
              <div class="form-group">
                <label for="due_date">期限</label>
                <input type="text" class="form-control" name="due_date" id="due_date" value="{{ old('due_date') }}" />
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary">送信</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @include('plugins.flatpickr.scripts')
@endsection
