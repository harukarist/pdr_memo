@extends('layouts.app')

@section('content')
  <div class="container">
    <h5 class="mb-4">準備する</h5>
    <!-- プログレスバー -->
    <div class="progressbar__wrapper">
      <ul class="progressbar">
        <li class="active">Prep</li>
        <li class="">Do</li>
        <li class="">Review</li>
      </ul>
    </div>
    <!-- ガイド -->
    <section class="mb-4">
      {{-- タスク名 --}}
      <div class="border bg-white p-3 mb-3">
        <div class="p-guide__wrapper d-flex">
          <div class="p-guide__checkbox mr-2">
            @if($current_task->status == 3)
            <i class="far fa-check-square icon-checkbox" aria-hidden="true"></i>
            @else
            <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
            @endif
          </div>
          <div class="p-guide__contents text-justify p-0">
            <div class="p-guide__taskname">
              <h6 class="d-inline align-middle">
                {{ $current_task->task_name }}</h6>
              <small class="pl-2"> - {{ $current_task->project->project_name }}</small>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
      <p class="p-guide__text">タスク実行の準備をしましょう！</p>
      </div>
    </section>

    <section>
      <!-- Prep入力フォーム -->
      <form method="POST" action="{{ route('preps.create', ['project_id' => $current_task->project_id,'task_id' => $current_task->id]) }}">
        @csrf
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

        <!-- Prepテキストエリア -->
        <div class="form-group">
          <label for="prep_text">必要な準備をする</label>
          <textarea id="prep_text" class="form-control @error('prep_text') is-invalid @enderror" name="prep_text">{{ old('prep_text') }}</textarea>
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

        <!-- 予定時間・ステップ数 -->
        <div class="form-inline mb-4">
          <div class="form-group col-6">
            <label for="unit_time" class="pr-2">単位時間</label>
            <select id="unit_time" class="form-control @error('unit_time') is-invalid @enderror" name="unit_time">
              <option value="30" @empty(old('unit_time')) selected @endempty>30 分</option>
              @foreach($unit_times as $unit_time)
                <option value="{{ $unit_time }}" @if(old('unit_time') == $unit_time) selected @endif>{{ $unit_time }} 分</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-6">
            <label for="estimated_steps" class="pr-2">ステップ数</label>
            <select id="estimated_steps" class="form-control @error('estimated_steps') is-invalid @enderror" name="estimated_steps">
              <option value="1" @empty(old('estimated_steps')) selected @endempty>1 回</option>
              @foreach($estimated_steps as $step)
              <option value="{{ $step }}" @if(old('estimated_steps')== $step) selected @endif>{{ $step }}回</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- カテゴリー -->
        <div class="form-group form-inline mb-3">
          <label for="category_id" class="mb-0">カテゴリー</label>
          <div class="pl-3">
            <div class="form-check form-check-inline">
              @forelse($categories as $category)
                <input type="radio" class="form-check-input" name="category_id" id="{{ $category['id'] }}" value="{{ $category['id'] }}" @if(old('category_id')== $category['id'] || $current_task->project->category_id == $category['id'] ) checked @endif>
                <label class="form-check-label pr-4" for="{{ $category['id'] }}">
                  <h4 class="c-form__category badge {{ $category['category_class'] }} p-1">{{ $category['category_name'] }}</h4>
                </label>
              @empty
              @endforelse
            </div>
          </div>
        </div>

        <!-- 送信 -->
        <div class="text-center">
          <button type="submit" class="btn btn-primary">計画を登録！</button>
        </div>
      </form>
    </section>
  </div>
@endsection
