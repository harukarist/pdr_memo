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
    @include('components.pdr_guide',['current_task'=>$current_task])
  </section>

  <section class="mb-4">
    <div class="text-center">
      <p><span class="p-counter__number">
        <i class="fas fa-stopwatch mr-1"></i>{{ $done_count + 1 }}回目
      </span></p>
      <p>開始日時：
        <span class="p-counter__number">{{ $temp_started_at->format("m/d H:i") }}</span></p>
      {{-- タイマー --}}
      <countdown-timer unit-time="{{ $do_prep->unit_time }}"></countdown-timer>
    </div>
  </section>

  <section class="mb-5">
    <!-- Doneボタン -->
      <div class="text-center">
        <form method="POST" action="{{ route('preps.done', ['project_id' => $current_task->project_id,'task_id' => $current_task->id, 'prep_id' => $do_prep->id ]) }}">
          @csrf
        <input type="hidden" name="temp_started_at" value="{{ $temp_started_at }}">
        <input type="submit" class="btn btn-primary" value="完了！">
      </div>
  </section>

</div>
@endsection
