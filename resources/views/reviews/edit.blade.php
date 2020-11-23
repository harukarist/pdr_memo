@extends('layouts.app')

@section('content')
<div class="container">
  <h5 class="mb-4">振り返りを編集</h5>
  <!-- プログレスバー -->
  <div class="progressbar__wrapper">
    <ul class="progressbar">
      <li class="active">Prep</li>
      <li class="active">Do</li>
      <li class="active">Review</li>
    </ul>
  </div>

   {{-- 削除ボタン --}}
   <form action="{{ route('reviews.delete', ['prep_id'=>$editing_review->prep_id, 'review_id' => $editing_review->id]) }}" method="post">
    @method('DELETE')
    @csrf
  <button type="button" class="btn btn-outline-secondary btn-sm mb-3" data-toggle="modal" data-target="#modal1">
    この振り返りを削除する
  </button>
  <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="label1">この振り返りを削除しますか？</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          振り返りを削除すると、記録した実績時間も削除されます。<br>
          振り返りは編集画面から変更できます。本当にこの振り返りを削除しますか？
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

  <section class="mb-4">
    <!-- Review入力フォーム -->
    <form method="POST" action="{{ route('reviews.edit', ['prep_id'=>$editing_review->prep_id, 'review_id' => $editing_review->id ]) }}">
      @csrf
      @method('PATCH')

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
        <textarea id="review_text" class="form-control @error('review_text') is-invalid @enderror" name="review_text">{{ old('review_text') ?? $editing_review->review_text }}</textarea>
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
          <textarea id="good_text" class="form-control" name="good_text">{{ old('good_text') ?? $editing_review->good_text}}</textarea>
        </div>
        <div class="form-group col-md">
          <label for="problem_text">課題・改善点</label>
          <textarea id="problem_text" class="form-control" name="problem_text">{{ old('problem_text')?? $editing_review->problem_text }}</textarea>
        </div>
        <div class="form-group col-md">
          <label for="try_text">次回の工夫</label>
          <textarea id="try_text" class="form-control" name="try_text">{{ old('try_text') ?? $editing_review->try_text}}</textarea>
        </div>
      </div>
  
      <!-- 実行時間 -->
      <div class="form-inline row mb-4">
        <div class="form-group col-6">
          <label for="actual_time" class="pr-2">実際に行った時間</label>
          <div class="input-group">
            <input type="tel" class="form-control @error('actual_time') is-invalid @enderror" id="actual_time" name="actual_time" value="{{ old('actual_time') ?? $editing_review->actual_time }}" />
            <div class="input-group-append">
              <span class="input-group-text">分間</span>
            </div>
          </div>
        </div>
        <div class="form-group col-6">
          <label for="step_counter" class="pr-2">ステップ数</label>
          <div class="input-group">
            <input type="tel" class="form-control @error('step_counter') is-invalid @enderror" id="step_counter" name="step_counter" value="{{ old('step_counter') ?? $editing_review->step_counter }}" />
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
              <input type="radio" class="form-check-input" name="category_id" id="{{ $category->id }}" value="{{ $category->id }}" @if(old('category_id')== $category->id || $editing_review->category_id == $category->id) checked @endif>
              <label class="form-check-label pr-4" for="{{ $category->id }}">
                <h4 class="c-form__category badge badge-pill badge-light p-1 ">{{ $category->category_name }}</h4>
              </label>
            @empty
            @endforelse
          </div>
        </div>
      </div>

      <!-- 送信 -->
      <div class="text-center">
        <button type="submit" class="btn btn-primary">振り返りを修正</button>
      </div>
    </form>

  </section>
</div>
@endsection
