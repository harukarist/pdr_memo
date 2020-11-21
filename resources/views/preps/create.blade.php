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
    <form method="POST" action="{{ route('preps.post') }}">
      @csrf
      <!-- タスク名 -->
      <div class="form-group">
        <label for="task_id">タスクを選ぶ</label>
        <select id="task_id" class="form-control @error('task_id') is-invalid @enderror" name="task_id" autofocus>
          <option value="" selected>選択してください</option>
          @foreach($tasks as $index => $task)
            <option value="{{ $task->id }}" @if(old('task_id') == $task->id) selected @endif>{{ $task->task_name }}</option>
          @endforeach
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
        <textarea id="prep_text" class="form-control @error('prep_text') is-invalid @enderror" name="prep_text" value="{{ old('prep_text') }}"></textarea>
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
          <div class="form-group @error('unit_time') is-invalid @enderror" name="unit_time">
            <label for="unit_time">単位時間</label>
            <select id="unit_time" class="form-control">
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
            <label for="estimated_step">×ステップ数</label>
            <select id="estimated_step" class="form-control">
              @foreach($estimated_steps as $estimated_step)
              <option value="{{ $estimated_step }}" @if(old('estimated_step')== $estimated_step) selected @endif>{{ $estimated_step }}回</option>
              @endforeach
            </select>
            @error('estimated_step')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

      <!-- カテゴリー -->
      <div class="form-group">
        <label for="category_id">カテゴリー</label>
        <div class="pl-1">
          @forelse($categories as $category)
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="category_id"
              id="{{ $category->id }}"
            />
            <label class="form-check-label" for="{{ $category->id }}">
              <h5><span class="badge badge-secondary mr-1">{{ $category->category_name }}</span></h5>
            </label>
          </div>
          @empty
            カテゴリーの登録はまだありません。
          @endforelse
        </div>
      </div>

      <!-- 送信 -->
      <div class="text-center">
        <button type="submit" class="btn btn-info">Do!</button>
      </div>
    </form>
  </div>
@endsection
