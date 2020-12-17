@extends('layouts.app')

@section('content')
<div class="container c-container" v-pre>

  <h5 class="mb-4">計画を修正する</h5>

  <!-- プログレスバー -->
  <div class="progressbar__wrapper text-center">
    <ul class="progressbar">
      <li class="active">Prep</li>
      <li class="">Do</li>
      <li class="">Review</li>
    </ul>
  </div>

  <!-- ガイド -->
  <section class="mb-4">
    {{-- タスク名 --}}
    <div class="border p-3 mb-3">
      <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
      <h6 class="p-guide__task-name d-inline mb-0 align-middle">
        {{ $current_task->task_name }}</h6>
        <small class="pl-2"> - {{ $current_task->project->project_name }}</small>
    </div>
  </section>


  {{-- 削除ボタン --}}
  <div class="p-delete text-right">
    <form action="{{ route('preps.delete', ['project_id' => $current_task->project_id, 'task_id'=> $current_task->id,'prep_id'=>$editing_prep->id ]) }}" method="post">
      @method('DELETE')
      @csrf
    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" data-toggle="modal" data-target="#modal1">
      この計画を削除する
    </button>
    <div class="modal fade text-left" id="modal1" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="label1">この計画を削除しますか？</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            計画を削除すると、振り返りデータも一緒に削除されます。計画は編集画面から変更できます。本当にこの計画を削除しますか？
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

      

  <!-- Prep入力フォーム -->
  <form action="{{ route('preps.edit', ['project_id' => $current_task->project_id, 'task_id'=> $current_task->id, 'prep_id'=>$editing_prep->id ]) }}" method="post">
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

    <!-- Prepテキストエリア -->
    <div class="form-group">
      <label for="prep_text">必要な準備をする</label>
      <textarea id="prep_text" class="form-control @error('prep_text') is-invalid @enderror" name="prep_text">{{ old('prep_text') ?? $editing_prep->prep_text }}</textarea>
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

    <!-- 予定時間 -->
      <div class="form-inline mb-4">
        <div class="form-group col-6">
          <label for="unit_time">単位時間</label>
          <select id="unit_time" class="form-control @error('unit_time') is-invalid @enderror" name="unit_time">
            @foreach(\App\Prep::UNIT_TIME as $unit_time)
              <option value="{{ $unit_time }}" @if(old('unit_time') == $unit_time) selected @elseif($editing_prep->unit_time == $unit_time) selected @endif>{{ $unit_time }} 分</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-6">
          <label for="estimated_steps">ステップ数</label>
          <select id="estimated_steps" class="form-control @error('estimated_steps') is-invalid @enderror" name="estimated_steps">
            @foreach(\App\Prep::ESTIMATED_STEPS as $step)
            <option value="{{ $step }}" @if(old('estimated_steps')== $step) selected @elseif($editing_prep->estimated_steps == $step) selected @endif>{{ $step }}回</option>
            @endforeach
          </select>
        </div>
      </div>

    <!-- 送信 -->
    <div class="text-center">
      <button type="submit" class="btn btn-primary">計画を修正</button>
    </div>
  </form>
</div>
@endsection
