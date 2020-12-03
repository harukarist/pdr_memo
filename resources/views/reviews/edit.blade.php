@extends('layouts.app')

@section('content')
<div class="container c-container">
  <h5 class="mb-4">振り返りを編集する</h5>
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
    <div class="border bg-white p-3 mb-3">
      <div class="p-guide__wrapper d-flex">
        <div class="p-guide__checkbox mr-2">
          @if($current_task->status == 4)
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
          <div class="p-guide__prep-detail py-2">
            <small>
              <mark class="mr-2">
              {{ $editing_review->prep->unit_time }}分 × 
              {{ $editing_review->prep->estimated_steps }}ステップ
              </mark>
              <span class="badge badge-light">{{ $editing_review->prep->category->category_name }}</span>
            </small>
          </div>
          <div class="p-guide__prep-text">
              {{ $editing_review->prep->prep_text }}
          </div>
        </div>
      </div>
    </div>
  </section>

   {{-- 削除ボタン --}}
   <div class="p-delete text-right">
    <form action="{{ route('reviews.delete', ['project_id' => $current_task->project_id, 'task_id'=> $current_task->id,'prep_id'=>$editing_review->prep_id, 'review_id' => $editing_review->id]) }}" method="post">
      @method('DELETE')
      @csrf
    <button type="button" class="btn btn-outline-secondary btn-sm mb-3 text-right" data-toggle="modal" data-target="#modal1">
      この振り返りを削除する
    </button>
    <div class="modal fade text-left" id="modal1" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
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
  </div>

  <section class="mb-4">
    <!-- Review入力フォーム -->
    <form method="POST" action="{{ route('reviews.edit', ['project_id' => $current_task->project_id, 'task_id'=> $current_task->id,'prep_id'=>$editing_review->prep_id, 'review_id' => $editing_review->id ]) }}">
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
      <!-- 実行時間 -->
      <div class="form-inline row mb-4">
        <div class="form-group col-auto">
          <label for="started_at" class="pr-2">開始</label>
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
            <input type="tel" class="form-control @error('actual_time') is-invalid @enderror" id="actual_time" name="actual_time" value="{{ old('actual_time') ?? $editing_review->actual_time }}" />
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
            @foreach(\App\Review::FLOW_LEVEL as $flow_level)
              <option value="{{ $flow_level['value'] }}" @if(old('flow_level') === $flow_level['value']) selected @elseif($editing_review->flow_level === $flow_level['value']) selected @endif>{{ $flow_level['value'].'：'.$flow_level['level_name'] }}</option>
            @endforeach
          </select>
        </div>
      </div>

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

      <div class="form-inline row mb-4">
        <!-- カテゴリー -->
        <div class="form-group form-inline col-auto mb-3">
          <label for="category_id" class="mb-0">カテゴリー</label>
          <div class="pl-3">
            <div class="form-check form-check-inline">
              @forelse(\App\Project::CATEGORIES as $category)
                <input type="radio" class="form-check-input" name="category_id" id="{{ $category['id'] }}" value="{{ $category['id'] }}" @if(old('category_id')== $category['id'] || $editing_review->category_id == $category['id']) checked @endif>
                <label class="form-check-label pr-4 " for="{{ $category['id'] }}">
                  <span class="c-form__category badge p-1 align-self-center">{{ $category['category_name'] }}</span>
                </label>
              @empty
              @endforelse
            </div>
          </div>
        </div>

        {{-- タスクを完了に切り替え --}}
        <div class="form-check col-auto mb-4 ml-4">
          <span class="d-flex">
          <input class="form-check-input align-self-center" type="checkbox" name="task_completed" id="task_completed" @if(old('task_completed') === 'on' || $current_task->status == 4 ) checked @endif>
          <label class="form-check-label align-self-center" for="task_completed">タスクを完了済みにする</label>
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
