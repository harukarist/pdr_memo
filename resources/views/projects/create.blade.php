@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <section class="row justify-content-center">
      <div class="col-sm-8">
        <h5 class="c-heading__title">プロジェクトの作成</h5>
        <div class="card">
          <div class="card-header">プロジェクト名を入力して作成</div>
          <div class="card-body">
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
                <div class="form-row ">
                  <div class="col-7 col-md-8">
                    <input type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" id="project_name"　value="{{ old('project_name') }}" placeholder="プロジェクト名"/>
                  </div>
                  <div class="col-auto">
                    <button type="submit" class="btn btn-primary">作成</button>
                  </div>
                </div>
              </div>
              {{-- メインカテゴリー --}}
              <div class="form-group">
                <label for="category_id">メインカテゴリー</label>
                <div class="pl-1">
                  <div class="form-check form-check-inline">
                    @foreach($categories as $category)
                      <input type="radio" class="form-check-input" name="category_id" id="{{ $category['id'] }}" value="{{ $category['id'] }}" @if(old('category_id')== $category['id']) checked @endif>
                      <label class="form-check-label pr-4" for="{{ $category['id'] }}">
                        <h4 class="c-form__category badge {{ $category['category_class'] }} p-1">{{ $category['category_name'] }}</h4>
                      </label>
                    @endforeach
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    {{-- プロジェクト一覧 --}}
    @component('components.project_list')
      @slot('projects', $projects)
    @endcomponent
  </div>
@endsection
