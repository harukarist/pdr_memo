@extends('layouts.app')

@section('content')
<div class="container">
  <h5 class="mb-4">実行する</h5>
    <!-- プログレスバー -->
    <div class="progressbar__wrapper">
      <ul class="progressbar">
        <li class="active">Prep</li>
        <li class="active">Do</li>
        <li class="">Review</li>
      </ul>
    </div>

  <!-- ガイド -->
  <section class="bg-white border p-5 mb-5">
    <div class="text-center">
    <p class="p-guide__text">{{ $do_prep->unit_time }}分間、集中して取り組みましょう！</p>
    </div>
  </section>

  <section class="mb-5">
    <!-- Reviewへ進むボタン -->
    <form method="head" action="/records/review/create">
      <div class="text-center">
        <p>完了しましたか？</p>
        <a href="{{ route('reviews.create', ['prep_id' => $do_prep->id ]) }}" class="btn btn-primary">完了！</a>
      </div>
    </form>
  </section>
  <!-- Prep入力内容の表示 -->
  <section class="p-prep__wrapper mb-4">
    <article class="p-record bg-white border p-0 mb-2">
      <div class="p-record__title-wrapper p-3 mb-1">
        <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
        <h6 class="p-record__title d-inline mb-0 align-middle">
          {{ $do_prep->task->task_name }}</h6>
          <small class="pl-2"> - {{ $do_prep->task->project->project_name }}</small>
      </div>
      <!-- PDR -->
      <div class="p-record__details row justify-content-around mx-0 my-2">
        {{-- Prep --}}
        <div class="p-record__item-wrapper col-md-6">
          <div class="text-secondary clearfix">
            <span class="p-record__item-title float-left mb-0">Prep</span>
            <a href="{{ route('preps.edit', ['prep_id' => $do_prep->id ]) }}"><span class="float-right mb-0 ml-2 small">編集</span></a>
            <span class="float-right mb-0 small">{{ $do_prep->created_at }}</span>
          </div>
          <div class="p-record__item-text ml-1">
            {{-- e()でエスケープ処理、nl2br()で改行あり --}}
            <p class="mb-1">{!! nl2br(e($do_prep->prep_text)) !!}</p>
            <div class="p-record__item-detail">
              <p class="mb-1 text-secondary d-inline">予定：<strong>{{ $do_prep->unit_time }}分 × {{ $do_prep->estimated_steps }}ステップ</strong></p>
              <a href="#" class="badge badge-secondary ml-1">{{ $do_prep->category->category_name }}</a>
            </div>
          </div>
        </div>
        {{-- Review --}}
        <div class="p-record__item-wrapper col-md-6">
          @forelse ($do_prep->reviews as $review)
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
