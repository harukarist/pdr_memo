@extends('layouts.app')

@section('styles')
  @include('plugins.spectrum.styles')
@endsection

@section('content')
<div class="container c-container">
  <h5 class="c-heading__title">プロジェクトの編集</h5>

  {{-- 削除ボタン --}}
  <div class="p-delete text-right">
    <form action="{{ route('projects.delete', ['project_id' => $edit_project->id ]) }}" method="post">
      @method('DELETE')
      @csrf
    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" data-toggle="modal" data-target="#modal1">
      このプロジェクトを削除する
    </button>
    <div class="modal fade text-left" id="modal1" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="label1">このプロジェクトを削除しますか？</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            プロジェクトを削除すると、そのプロジェクト内のタスクも一緒に削除されます。<br>
            プロジェクトの内容は編集画面から変更できます。本当にこのプロジェクトを削除しますか？
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

  <div class="c-form__wrapper">
    <form action="{{ route('projects.edit', ['project_id' => $edit_project->id]) }}" method="post">
      @method('PATCH')
      @csrf
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

      {{-- プロジェクト名 --}}
      <div class="form-group">
        <label for="category_id">プロジェクト名</label>
            {{-- 直前の入力値がない場合はテーブルの値を表示 --}}
            <input type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" id="project_name" value="{{ old('project_name') ?? $edit_project->project_name }}" />                    
      </div>

      {{-- プロジェクトの目標 --}}
      <div class="form-group">
        <label for="category_id">目標</label>
            <input type="text" class="form-control @error('project_target') is-invalid @enderror" name="project_target" id="project_target"　value="{{ old('project_target') ?? $edit_project->project_target }}"/>
      </div>

      {{-- テーマカラー --}}
      <div class="form-group">
        <label for="project_color">テーマカラー</label>
        <input type="text" class="form-control  @error('project_color') is-invalid @enderror" id="picker" name="project_color" id="project_color" value="{{ old('project_color') ?? $edit_project->project_color }}">
      </div>
      
      {{-- メインカテゴリー --}}
      <div class="form-group">
        <label for="category_id">メインカテゴリー</label>
        <div class="pl-1">
          <div class="form-check form-check-inline">
            @foreach(\App\Project::CATEGORIES as $category)
              <input type="radio" class="form-check-input" name="category_id" id="{{ $category['id'] }}" value="{{ $category['id'] }}" @if(old('category_id')== $category['id'] || $edit_project->category_id == $category['id']) checked @endif>
              <label class="form-check-label pr-4 " for="{{ $category['id'] }}">
                <span class="c-form__category badge p-1 align-self-center">{{ $category['category_name'] }}</span>
              </label>
            @endforeach
          </div>
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">変更</button>
      </div>
    </form>
  </div>

</div>
@endsection

@section('scripts')
  @include('plugins.spectrum.scripts')
@endsection
