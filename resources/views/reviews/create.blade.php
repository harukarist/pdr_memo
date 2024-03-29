@extends('layouts.app')

@section('content')
<div class="container c-container">
  <h5 class="mb-4">振り返る</h5>
    <!-- プログレスバー -->
    <div class="progressbar__wrapper">
      <ul class="progressbar">
        <li class="active">Prep</li>
        <li class="active">Do</li>
        <li class="active">Review</li>
      </ul>
    </div>

  <!-- ガイド -->
  <section class="mb-4">
    @include('components.pdr_guide',['current_task'=>$current_task])
  </section>

  <section class="mb-4">
    <div class="text-center mb-5">
      <p class="p-form__guide">{{ $done_count }}回目おつかれさまでした！結果を振り返ってみましょう。</p>
    </div>

    <!-- Review入力フォーム -->
    <form method="POST" action="{{ route('reviews.create', ['project_id' => $current_task->project_id,'task_id' => $current_task->id,'prep_id' => $done_prep->id ]) }}">
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
      
      <!-- Reviewテキストエリア -->
      <!-- 実行時間 -->
      <div class="form-inline row mb-4">
        <div class="form-group col-auto">
          <label for="started_at" class="pr-2">開始日時</label>
          <div class="input-group">
            <input type="date" class="form-control @error('started_date') is-invalid @enderror" id="started_date" name="started_date" value="{{ old('started_date') ?? $started_date }}" />
          </div>
          <div class="input-group">
            <input type="time" class="form-control @error('started_time') is-invalid @enderror" id="started_time" name="started_time" value="{{ old('started_time') ?? $started_time }}" />
          </div>
        </div>
        <div class="form-group col-auto">
          <label for="actual_time" class="pr-2">行った時間</label>
          <div class="input-group">
            <input type="tel" class="form-control @error('actual_time') is-invalid @enderror" id="actual_time" name="actual_time" value="{{ old('actual_time') ?? $actual_time }}" />
            <div class="input-group-append">
              <span class="input-group-text">分間</span>
            </div>
          </div>
        </div>
      </div>

      <div class="form-inline row mb-4">
        <div class="form-group col-auto">
          <label for="flow_level" class="pr-2">集中度</label>
          <select id="flow_level" class="form-control @error('flow_level') is-invalid @enderror" name="flow_level">
            @foreach(\App\Review::FLOW_LEVEL as $index => $flow_level)
              <option value="{{ $flow_level['value'] }}" @if(old('flow_level') == $flow_level['value'] || empty(old('flow_level')) && $index == 3 ) selected @endif>{{ $flow_level['value'].'：'.$flow_level['level_name'] }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="review_text">振り返り</label>
        <textarea id="review_text" class="form-control @error('review_text') is-invalid @enderror" name="review_text" rows="6">{{ $current_task->memo_text ?? old('review_text') }}</textarea>
        <span id="help-review" class="form-text text-muted">
          <ul>
            <li>何を実行した？</li>
            <li>準備通りに実行できた？</li>
            <li>前に比べて変わった点は？</li>
            <li>学んだことは？</li>
            <li>次回に工夫したい点は？</li>
          </ul>
        </span>
      </div>
      <div class="row mb-3">
        <div class="form-group col-md">
          <label for="good_text">良かった点</label>
          <textarea id="good_text" class="form-control" name="good_text">{{ old('good_text') }}</textarea>
        </div>
        <div class="form-group col-md">
          <label for="problem_text">課題・改善点</label>
          <textarea id="problem_text" class="form-control" name="problem_text">{{ old('problem_text') }}</textarea>
        </div>
        <div class="form-group col-md">
          <label for="try_text">次回の工夫</label>
          <textarea id="try_text" class="form-control" name="try_text">{{ old('try_text') }}</textarea>
        </div>
      </div>
  
      <div class="form-inline row mb-4">
        <!-- カテゴリー -->
        <div class="form-group form-inline col-auto mb-3">
          <label for="category_id" class="mb-0">カテゴリー</label>
          <div class="pl-3">
            @forelse($categories as $category)
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input ml-4 @error('category_id') is-invalid @enderror" name="category_id" id="{{ $category->id }}" value="{{ $category->id }}" @if(old('category_id')== $category->id || $current_task->project->category_id == $category->id) checked @endif>
                <label class="custom-control-label" for="{{ $category->id }}">
                  <span class="c-form__category badge p-1 align-self-center">{{ $category->category_name }}</span>
                </label>
              </div>
              @empty
              @endforelse
          </div>
        </div>

        {{-- タスクを完了に切り替え --}}
        <div class="form-check mb-4 ml-4">
          <input class="form-check-input align-self-center" type="checkbox" name="task_completed" id="task_completed" @if(old('task_completed') === 'on' || $current_task->status == 4 ) checked @endif>
          <label class="form-check-label align-self-center" for="task_completed">タスクを完了済みにする</label>
        </div>
      </div>


      <!-- 送信 -->
      <div class="text-center">
        <button type="submit" class="btn btn-primary">完了！</button>
      </div>
    </form>

  </section>

</div>
@endsection
 