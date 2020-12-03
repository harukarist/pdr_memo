@extends('layouts.app')

@section('content')
<div class="container c-container">
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
  <section class="mb-4">
    {{-- タスク名 --}}
    <div class="bg-white border p-3 mb-3">
      <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
      <h6 class="p-guide__task-name d-inline mb-0 align-middle">
        {{ $current_task->task_name }}</h6>
        <small class="pl-2"> - {{ $current_task->project->project_name }}</small>
    </div>
    <div class="text-center">
      <p>{{ $current_task->done_count + 1 }}回目</p>
      <p>開始日時：{{ $started_at }}</p>
      <p class="p-guide__text">{{ $do_prep->unit_time }}分間、集中して取り組みましょう！</p>
    </div>
  </section>



  <section class="mb-5">
    <!-- Doneボタン -->
      <div class="text-center">
        <p>完了しましたか？</p>
        <form method="POST" action="{{ route('preps.done', ['project_id' => $current_task->project_id,'task_id' => $current_task->id, 'prep_id' => $do_prep->id ]) }}">
          @csrf
        <input type="hidden" name="started_at" value="{{ $started_at }}">
        <input type="submit" class="btn btn-primary" value="完了！">
      </div>
  </section>

</div>
@endsection
