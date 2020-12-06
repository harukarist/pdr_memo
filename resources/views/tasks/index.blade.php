
@extends('layouts.app')

@section('styles')
  @include('plugins.flatpickr.styles')
@endsection

@section('content')
  <div class="container-fluid">
    <div class="test" id="heatmap"></div>
    <div class="row pt-4">
      <div class="l-left__wrapper col-md-3 mb-3">
        <div class="p-menu__wrapper">
          <div class="p-menu__project">
            {{-- プロジェクト一覧 --}}
            <ul class="list-group list-group-flush mt-2 mb-4">
              {{-- <li class="list-group-item bg-light p-1">プロジェクト</li> --}}
              {{-- プロジェクトIDが一致する場合はactiveクラスを出力 --}}
              @if(count($projects))
                @include('tasks.project_nav',['projects'=>$projects, 'current_project'=>$current_project])
              @endif
              <li class="list-group-item p-1">
                <a href="{{ route('projects.create') }}" class="btn btn-outline-secondary btn-block">
                  プロジェクトを追加
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="l-right__wrapper col-md-9">
        <div class="p-tasklist__wrapper">

          
          {{-- サマリー --}}
            @include('tasks.task_summary',['counter'=>$counter,'current_project'=>$current_project])
          

           {{-- タスク作成フォーム --}}
          <div class="p-tasklist__create mb-5">
            @include('components.flash_message')
            @include('tasks.task_create',['project_id'=>$current_project->id])
          </div>
          <div class="p-tasklist__list">
            {{-- タスクメニュー --}}
            @include('tasks.task_nav')
          
            {{-- タスクリスト --}}
            @if(count($tasks))
              @include('tasks.task_pdr',['tasks'=>$tasks])
            @endif
          </div>
        </div>
        <div class="my-4 d-flex justify-content-center">
          {{-- ページネーションリンク --}}
          @if(count($tasks))
          {{ $tasks->links() }}
          @endif
      </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @include('plugins.flatpickr.scripts')
@endsection
