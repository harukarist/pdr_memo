@extends('layouts.app')

@section('content')
<div class="container">

  <h5 class="mb-4">計画を修正する</h5>

  <!-- プログレスバー -->
  <div class="progressbar__wrapper">
    <ul class="progressbar">
      <li class="active">Prep</li>
      <li class="">Do</li>
      <li class="">Review</li>
    </ul>
  </div>

  {{-- 削除ボタン --}}
  <form action="{{ route('preps.delete', ['prep_id' => $editing_prep->id]) }}" method="post">
    @csrf
  <button type="button" class="btn btn-outline-secondary btn-sm mb-3" data-toggle="modal" data-target="#modal1">
    この計画を削除する
  </button>
  <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="label1">この計画を削除しますか？</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          記録された内容は編集画面から変更できます。本当にこの計画を削除しますか？
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

  <!-- Prep入力フォーム -->
  <form action="{{ route('preps.edit', ['prep_id' => $editing_prep->id]) }}" method="post">
    @csrf
    <!-- タスク名 -->
    <div class="form-group">
      <label for="task_id">タスクを選ぶ</label>
      <select id="task_id" class="form-control @error('task_id') is-invalid @enderror" name="task_id">
        @forelse($tasks as $index => $task)
          <option value="{{ $task->id }}" @if(old('task_id') == $task->id || $editing_prep->task_id == $task->id) selected @endif>{{ $task->task_name }}</option>
        @empty
          <a href="{{ route('home') }}">タスクを登録してください</a>
        @endforelse
      </select>
      @error('task_id')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <!-- Prepテキストエリア -->
    <div class="form-group">
      <label for="prep_text">必要な準備をする</label>
      <textarea id="prep_text" class="form-control @error('prep_text') is-invalid @enderror" name="prep_text">{{ old('prep_text') ?? $editing_prep->prep_text }}</textarea>
      @error('prep_text')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
      <span id="help-prep" class="form-text text-muted">
        <ul>
          <li>これから何をする？</li>
          <li>その理由、目標、目的は？</li>
          <li>どのようなプロセスで行う？</li>
          <li>必要なリソースは？</li>
          <li>他に関わる人は？</li>
        </ul>
      </span>
    </div>

    <!-- 予定時間 -->
      <div class="form-inline mb-4">
        <div class="form-group">
          <label for="unit_time">単位時間</label>
          <select id="unit_time" class="form-control @error('unit_time') is-invalid @enderror" name="unit_time">
            @foreach($unit_times as $unit_time)
              <option value="{{ $unit_time }}" @if(old('unit_time') == $unit_time || $editing_prep->unit_time == $unit_time) selected @endif>{{ $unit_time }} 分</option>
            @endforeach
          </select>
          @error('unit_time')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="estimated_steps">×ステップ数</label>
          <select id="estimated_steps" class="form-control @error('estimated_steps') is-invalid @enderror" name="estimated_steps">
            @foreach($estimated_steps as $step)
            <option value="{{ $step }}" @if(old('estimated_steps')== $step || $editing_prep->estimated_steps == $step) selected @endif>{{ $step }}回</option>
            @endforeach
          </select>
          @error('estimated_steps')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

    <!-- カテゴリー -->
    <div class="form-group">
      <label for="category_id">カテゴリー</label>
      <select id="category_id" class="form-control @error('category_id') is-invalid @enderror" name="category_id">
        @forelse($categories as $category)
          <option value="{{ $category->id }}" @if(old('category_id')== $category->id || $editing_prep->category_id == $category->id) selected @endif>{{ $category->category_name }}</option>
        @empty
          カテゴリーの登録はまだありません。
        @endforelse
      </select>
      @error('category_id')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
      @enderror
    </div>

    <!-- 送信 -->
    <div class="text-center">
      <button type="submit" class="btn btn-primary">計画を修正</button>
    </div>
  </form>
</div>
@endsection
