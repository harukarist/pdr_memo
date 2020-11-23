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
  <section class="p-2 mb-5">
    {{-- タスク名 --}}
    <div class="bg-white border p-3 mb-3">
      <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
      <h6 class="p-record__title d-inline mb-0 align-middle">
        {{ $do_prep->task->task_name }}</h6>
        <small class="pl-2"> - {{ $do_prep->task->project->project_name }}</small>
    </div>
    <div class="text-center">
      <p>{{ $review_count }}回目</p>
      <p class="p-guide__text">{{ $do_prep->unit_time }}分間、集中して取り組みましょう！</p>
    </div>
  </section>

  <section class="mb-5">
    <!-- Doneボタン -->
      <div class="text-center">
        <p>完了しましたか？</p>
        <a href="{{ route('preps.done', ['prep_id' => $do_prep->id ]) }}" class="btn btn-primary">完了！</a>
      </div>
  </section>

</div>
@endsection
