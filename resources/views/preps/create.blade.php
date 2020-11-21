@extends('layouts.app')

@section('content')
  <div class="container">
    
    <h5 class="mb-4">準備をする</h5>
    <!-- プログレスバー -->
    <div class="progressbar__wrapper">
      <ul class="progressbar">
        <li class="active">Prep</li>
        <li class="">Do</li>
        <li class="">Review</li>
      </ul>
    </div>

    <!-- Prep入力フォーム -->
    <form method="POST" action="{{ route('preps.create') }}">
      @csrf
      <!-- タスク名 -->
      <div class="form-group">
        <label for="task_id">タスクを選ぶ</label>
        <select id="task_id" class="form-control @error('task_id') is-invalid @enderror" name="task_id" autofocus>
          <option value="" @empty(old('task_id')) selected @endempty>選択してください</option>
          @forelse($tasks as $index => $task)
            <option value="{{ $task->id }}" @if(old('task_id') == $task->id) selected @endif>{{ $task->task_name }}</option>
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
        <textarea id="prep_text" class="form-control @error('prep_text') is-invalid @enderror" name="prep_text">{{ old('prep_text') }}</textarea>
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
              <option value="30" @empty(old('unit_time')) selected @endempty>30 分</option>
              @foreach($unit_times as $unit_time)
                <option value="{{ $unit_time }}" @if(old('unit_time') == $unit_time) selected @endif>{{ $unit_time }} 分</option>
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
              <option value="1" @empty(old('estimated_steps')) selected @endempty>1 回</option>
              @foreach($estimated_steps as $step)
              <option value="{{ $step }}" @if(old('estimated_steps')== $step) selected @endif>{{ $step }}回</option>
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
          <option value="" @empty(old('category_id')) selected @endempty>選択してください</option>
          @forelse($categories as $category)
            <option value="{{ $category->id }}" @if(old('category_id')== $category->id) selected @endif>{{ $category->category_name }}</option>
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
        <button type="submit" class="btn btn-info">Do!</button>
      </div>
    </form>
  </div>
@endsection
