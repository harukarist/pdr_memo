@extends('layouts.app')

@section('styles')
  @include('plugins.spectrum.styles')
@endsection

@section('content')
  <div class="container c-container">
    <h5 class="c-heading__title">プロジェクトの追加</h5>
    <div class="c-form__wrapper">
      {{-- 入力フォーム --}}
      <form action="{{ route('projects.create') }}" method="post">
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
              <input type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" id="project_name"　value="{{ old('project_name') }}"/>
        </div>

        {{-- プロジェクトの目標 --}}
        <div class="form-group">
          <label for="category_id">目標</label>
              <input type="text" class="form-control @error('project_target') is-invalid @enderror" name="project_target" id="project_target"　value="{{ old('project_target') }}"/>
        </div>

        {{-- テーマカラー --}}
        <div class="form-group">
          <label for="project_color">テーマカラー</label>
          <input type="text" class="form-control  @error('project_color') is-invalid @enderror" id="picker" name="project_color" id="project_color" value="{{ old('project_color') ?? '#cccccc' }}">
        </div>


        {{-- メインカテゴリー --}}
        <div class="form-group">
          <label for="category_id">メインカテゴリー</label>
          <div class="pl-1">
            <div class="form-check form-check-inline">
              @foreach(\App\Project::CATEGORIES as $category)
                <input type="radio" class="form-check-input" name="category_id" id="{{ $category['id'] }}" value="{{ $category['id'] }}" @if(old('category_id')== $category['id']) checked @endif>
                <label class="form-check-label pr-4 " for="{{ $category['id'] }}">
                  <span class="c-form__category badge p-1 align-self-center">{{ $category['category_name'] }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">作成</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  @include('plugins.spectrum.scripts')
@endsection
