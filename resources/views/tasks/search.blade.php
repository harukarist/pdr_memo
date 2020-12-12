
@extends('layouts.app')

@section('styles')
  @include('plugins.flatpickr.styles')
@endsection

@section('content')
        <div class="p-tasklist__wrapper">

           {{-- タスク作成フォーム --}}
          <div class="p-tasklist__create mb-4 p-2 @if(!count($tasks)) border border-primary @endif">
            @include('components.flash_message')
            @include('tasks.task_create',['project_id'=>$current_project->id])
          </div>
          <div class="p-tasklist__list">
            {{-- タスクメニュー --}}
            @include('tasks.task_nav',['keyword'=>$keyword])
          
            {{-- タスクリスト --}}
            @if(count($tasks))
              @include('tasks.task_list',['tasks'=>$tasks])
            @else
            <div class="p-task__notask alert alert-primary mt-4">
              タスクがまだありません。登録しましょう！
            </div>
            @endif
          </div>
        </div>
        <div class="my-4 d-flex justify-content-center">
          {{-- ページネーションリンク --}}
          @if(count($tasks))
          {{ $tasks->links() }}
          @endif
        </div>
@endsection

{{-- @section('scripts')
  @include('plugins.flatpickr.scripts')
@endsection --}}
