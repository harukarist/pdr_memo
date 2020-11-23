@extends('layouts.app')

@section('content')
  <div class="container">
    <h5 class="mb-4">これまでの記録</h5>
    <!-- 記録一覧 -->
    <div class="c-contents__wrapper">
        @foreach ($preps as $prep)
        <!-- 個別の記録 -->
        <article class="p-record bg-white border p-0 mb-2">
          <div class="p-record__title-wrapper p-3 mb-1">
            <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
            <h6 class="p-record__title d-inline mb-0 align-middle">
              {{ $prep->task->task_name }}</h6>
              <small class="pl-2"> - {{ $prep->task->project->project_name }}</small>
          </div>
          <!-- PDR -->
          <div class="p-record__details row justify-content-around mx-0 my-2">
            {{-- Prep --}}
            <div class="p-record__item-wrapper col-md-4">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <a href="{{ route('preps.edit', ['prep_id' => $prep->id ]) }}"><span class="float-right mb-0 ml-2 small">編集</span></a>
                <span class="float-right mb-0 small">{{ $prep->created_at }}</span>
              </div>
              <div class="p-record__item-text ml-1">
                {{-- e()でエスケープ処理、nl2br()で改行あり --}}
                <p class="mb-1">{!! nl2br(e($prep->prep_text)) !!}</p>
                <div class="p-record__item-detail">
                  <p class="mb-1 text-secondary d-inline">予定：<strong>{{ $prep->unit_time }}分 × {{ $prep->estimated_steps }}ステップ</strong></p>
                  <span class="badge badge-secondary ml-1">{{ $prep->category->category_name }}</span>
                </div>
              </div>
            </div>
            {{-- Do --}}
            <div class="p-record__item-wrapper col-auto">
              <a href="{{ route('preps.do', ['prep_id' => $prep->id ]) }}" class="btn btn-primary my-3">Do！</a>
            </div>
            {{-- Review --}}
            <div class="p-record__item-wrapper col-md-6">
              @foreach ($prep->reviews as $review)
              <div class="p-record__review-wrapper mb-2">
                <div class="text-secondary clearfix">
                  <span class="p-record__item-title float-left mb-0">Review-{{ $review->step_counter }}</span>
                  <a href="{{ route('reviews.edit', ['prep_id' => $review->prep_id,'review_id'=>$review->id ]) }}"><span class="float-right mb-0 ml-2 small">編集</span></a>
                  <span class="float-right mb-0 small">{{ $review->created_at }}</span>
                </div>
                <div class="p-record__item-text ml-1">
                  <p class="mb-1">
                    {!! nl2br(e($review->review_text)) !!}
                  </p>
                  <div class="p-record__item-detail mb-2">
                    <p class="text-secondary d-inline">Time：<strong>{{ $review->actual_time }}分</strong></p>
                    <span class="badge badge-secondary ml-1">{{ $review->category->category_name }}</span>
                  </div>
                  @if($review->good_text || $review->problem_text || $review->try_text)
                  <div class="p-record__item-kpt border p-1">
                    @isset($review->good_text)
                    <p class="mb-1">
                      Good/Keep：{!! nl2br(e($review->good_text)) !!}
                    </p>
                    @endisset
                    @isset($review->problem_text)
                    <p class="mb-1">
                      Problem：{!! nl2br(e($review->problem_text)) !!}
                    </p>
                    @endisset
                    @isset($review->try_text)
                    <p class="mb-1">
                      Try：{!! nl2br(e($review->try_text)) !!}
                    </p>
                    @endisset
                  </div>
                  @endif
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </article>
        @endforeach
      </section>



    </div>
  </div>
@endsection
