@extends('layouts.app')

@section('content')
<div class="container">
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
    {{-- タスク名 --}}
    <div class="bg-white border p-3 mb-3">
      <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
      <h6 class="p-record__title d-inline mb-0 align-middle">
        {{ $done_prep->task->task_name }}</h6>
        <small class="pl-2"> - {{ $done_prep->task->project->project_name }}</small>
    </div>
    <div class="text-center">
    <p class="p-guide__text">{{ $review_count }}回目おつかれさまでした！結果を振り返ってみましょう。</p>
    </div>
  </section>

  <section class="mb-4">
    <!-- Review入力フォーム -->
    <form method="POST" action="{{ route('reviews.create', ['prep_id' => $done_prep->id ]) }}">
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
      <div class="form-group">
        <label for="review_text">振り返り</label>
        <textarea id="review_text" class="form-control @error('review_text') is-invalid @enderror" name="review_text">{{ old('review_text') }}</textarea>
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
  
      <!-- 実行時間 -->
      <div class="form-inline row mb-4">
        <div class="form-group col-6">
          <label for="actual_time" class="pr-2">実際に行った時間</label>
          <div class="input-group">
            <input type="tel" class="form-control @error('actual_time') is-invalid @enderror" id="actual_time" name="actual_time" value="{{ old('actual_time') ?? $done_prep->unit_time }}" />
            <div class="input-group-append">
              <span class="input-group-text">分間</span>
            </div>
          </div>
        </div>
        <div class="form-group col-6">
          <label for="step_counter" class="pr-2">ステップ数</label>
          <div class="input-group">
            <input type="tel" class="form-control @error('step_counter') is-invalid @enderror" id="step_counter" name="step_counter" value="{{ old('step_counter') ?? $review_count }}" />
            <div class="input-group-append">
              <span class="input-group-text">回目</span>
            </div>
          </div>
        </div>
      </div>

      <!-- カテゴリー -->
      <div class="form-group">
        <label for="category_id">カテゴリー</label>
        <div class="pl-1">
          <div class="form-check form-check-inline">
            @forelse($categories as $category)
              <input type="radio" class="form-check-input" name="category_id" id="{{ $category->id }}" value="{{ $category->id }}" @if(old('category_id')== $category->id || $done_prep->category_id == $category->id) checked @endif>
              <label class="form-check-label pr-4" for="{{ $category->id }}">
                <h4 class="c-form__category badge badge-primary p-1 ">{{ $category->category_name }}</h4>
              </label>
            @empty
            @endforelse
          </div>
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
 