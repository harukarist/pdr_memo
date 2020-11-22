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
  <section class="p-2 mb-2">
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
            <input type="text" class="form-control @error('actual_time') is-invalid @enderror" id="actual_time" name="actual_time" value="{{ old('actual_time') ?? $done_prep->unit_time }}" />
            <div class="input-group-append">
              <span class="input-group-text">分間</span>
            </div>
          </div>
        </div>
        <div class="form-group col-6">
          <label for="step_counter" class="pr-2">ステップ数</label>
          <div class="input-group">
            <input type="text" class="form-control @error('step_counter') is-invalid @enderror" id="step_counter" name="step_counter" value="{{ old('step_counter') ?? $review_count }}" />
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
        <!-- <input type="submit" class="btn btn-primary">Do!</input> -->
      </div>
    </form>

  </section>

  <!-- Prep入力内容の表示 -->
  <section class="p-prep__wrapper mb-4">
    <article class="p-record bg-white border p-0 mb-2">
      <div class="p-record__title-wrapper p-3 mb-1">
        <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
        <h6 class="p-record__title d-inline mb-0 align-middle">
          {{ $done_prep->task->task_name }}</h6>
          <small class="pl-2"> - {{ $done_prep->task->project->project_name }}</small>
      </div>
      <!-- PDR -->
      <div class="p-record__details row justify-content-around mx-0 my-2">
        {{-- Prep --}}
        <div class="p-record__item-wrapper col-md-6">
          <div class="text-secondary clearfix">
            <span class="p-record__item-title float-left mb-0">Prep</span>
            <a href="{{ route('preps.edit', ['prep_id' => $done_prep->id ]) }}"><span class="float-right mb-0 ml-2 small">編集</span></a>
            <span class="float-right mb-0 small">{{ $done_prep->created_at }}</span>
          </div>
          <div class="p-record__item-text ml-1">
            {{-- e()でエスケープ処理、nl2br()で改行あり --}}
            <p class="mb-1">{!! nl2br(e($done_prep->prep_text)) !!}</p>
            <div class="p-record__item-detail">
              <p class="mb-1 text-secondary d-inline">予定：<strong>{{ $done_prep->unit_time }}分 × {{ $done_prep->estimated_steps }}ステップ</strong></p>
              <a href="#" class="badge badge-secondary ml-1">{{ $done_prep->category->category_name }}</a>
            </div>
          </div>
        </div>
        {{-- Review --}}
        <div class="p-record__item-wrapper col-md-6">
          @forelse ($done_prep->reviews as $review)
          <div class="p-record__review-wrapper mb-2">
            <div class="text-secondary clearfix">
              <span class="p-record__item-title float-left mb-0">Review</span>
              <router-link
                v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
              >
                <span class="float-right mb-0 ml-2 small">編集</span>
              </router-link>
              <span class="float-right mb-0 small">{{ $review->created_at }}</span>
            </div>
            <div class="p-record__item-text ml-1">
              <p class="mb-1">
                {!! nl2br(e($review->review_text)) !!}
              </p>
              <div class="p-record__item-detail mb-2">
                <p class="text-secondary d-inline">実際：<strong>{{ $review->actual_time }}分</strong> <small>/ステップ{{ $review->step_counter }}</small></p>
                <a href="#" class="badge badge-secondary ml-1">{{ $review->category->category_name }}</a>
              </div>
              <div class="p-record__item-kpt border p-1">
                <p class="mb-1">
                  Good/Keep：{!! nl2br(e($review->good_text)) !!}
                </p>
                <p class="mb-1">
                  Problem：{!! nl2br(e($review->problem_text)) !!}
                </p>
                <p class="mb-1">
                  Try：{!! nl2br(e($review->try_text)) !!}
                </p>
              </div>
            </div>
          </div>
          @empty
          @endforelse
        </div>
      </div>
    </article>
  </section>
</div>
@endsection
