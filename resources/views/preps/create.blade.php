@extends('layouts.app')

@section('content')
  <div class="container c-container">
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
      @include('components.pdr_guide',['current_task'=>$current_task])
    </section>

    <section>
      <div class="text-center mb-5">
        <p class="p-form__guide">タスク実行の準備をしましょう！</p>
      </div>
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
              <li>障害になりそうなことは？</li>
              <li>他に関わる人は？</li>
            </ul>
          </span>
        </div>

        <!-- 予定時間・ステップ数 -->
        <div class="form-inline mb-4">
          <div class="form-group col-6">
            <label for="unit_time" class="pr-2">単位時間</label>
            <select id="unit_time" class="form-control @error('unit_time') is-invalid @enderror" name="unit_time">
              @foreach(\App\Prep::UNIT_TIME as $unit_time)
                <option value="{{ $unit_time }}" @if(old('unit_time') == $unit_time || empty(old('unit_time')) && $unit_time == 30) selected @endif>{{ $unit_time }} 分</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-6">
            <label for="estimated_steps" class="pr-2">ステップ数</label>
            <select id="estimated_steps" class="form-control @error('estimated_steps') is-invalid @enderror" name="estimated_steps">
              @foreach(\App\Prep::ESTIMATED_STEPS as $step)
              <option value="{{ $step }}" @if(old('estimated_steps') == $step || empty(old('estimated_steps')) && $step == 1) selected @endif>{{ $step }}回</option>
              @endforeach
            </select>
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
