@extends('layouts.app')

@section('content')
<div class="container c-container">
  <h5 class="mb-4">記録を追加</h5>

  <section class="mb-4">
    <form method="POST" action="{{ route('records.add') }}">
    @csrf

    <input type="hidden" name="url" value="{{ url()->previous() }}">

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

      <div class="form-group">
        <label for="project_id">プロジェクト</label>
        <select id="project_id" class="form-control @error('project_id') is-invalid @enderror" name="project_id">
          @foreach($projects as $project)
            <option value="{{ $project->id }}" @if(old('project_id') === $project->id) selected @endif>{{ $project->project_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="task_name">タスク名</label>
        <input type="text" class="form-control @error('task_name') is-invalid @enderror" name="task_name" id="task_name" value="{{ old('task_name') }}" />
      </div>

      <div class="form-group">
        <label for="prep_text">行った準備</label>
        <textarea id="prep_text" class="form-control @error('prep_text') is-invalid @enderror" name="prep_text" rows="6">{{ old('prep_text') }}</textarea>
      </div>

      <!-- 予定時間・ステップ数 -->
      <div class="form-inline mb-4">
        <div class="form-group col-6">
          <label for="unit_time" class="pr-2">単位時間</label>
          <select id="unit_time" class="form-control @error('unit_time') is-invalid @enderror" name="unit_time">
            @foreach(\App\Prep::UNIT_TIME as $unit_time)
              <option value="{{ $unit_time }}" @if(old('unit_time') === $unit_time) selected @elseif($unit_time === '30') selected @endif>{{ $unit_time }} 分</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-6">
          <label for="estimated_steps" class="pr-2">予想ステップ数</label>
          <select id="estimated_steps" class="form-control @error('estimated_steps') is-invalid @enderror" name="estimated_steps">
            @foreach(\App\Prep::ESTIMATED_STEPS as $step)
            <option value="{{ $step }}" @if(old('estimated_steps') === $step ) selected @elseif($step === 1) selected @endif>{{ $step }}回</option>
            @endforeach
          </select>
        </div>
      </div>
      
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
          <label for="actual_time" class="pr-2">実際に行った時間</label>
          <div class="input-group">
            <input type="tel" class="form-control @error('actual_time') is-invalid @enderror" id="actual_time" name="actual_time" value="{{ old('actual_time') }}" />
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
        <textarea id="review_text" class="form-control @error('review_text') is-invalid @enderror" name="review_text" rows="6">{{ old('review_text') }}</textarea>
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
                <input type="radio" class="custom-control-input ml-4 @error('category_id') is-invalid @enderror" name="category_id" id="{{ $category->id }}" value="{{ $category->id }}" @if(old('category_id')== $category->id||$category->id==1) checked @endif>
                <label class="custom-control-label" for="{{ $category->id }}">
                  <h4 class="c-form__category badge {{ $category['category_class'] }} p-1">{{ $category->category_name }}</h4>
                </label>
              </div>
              @empty
              @endforelse
          </div>
        </div>

        {{-- タスクを完了に切り替え --}}
        <div class="form-check mb-4 ml-4">
          <input class="form-check-input" type="checkbox" name="task_completed" id="task_completed" checked>
          <label class="form-check-label" for="task_completed">タスクを完了済みにする</label>
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
 