@extends('layouts.app')

@section('content')
<div class="container">
  <section class="row justify-content-center">
    <div class="col-sm-8">
      <h5 class="c-heading__title">プロジェクトの編集</h5>

      <div class="card">
        <div class="card-header">プロジェクト名の編集</div>
        <div class="card-body">
          <form action="{{ route('projects.edit', ['project_id' => $edit_project->id]) }}" method="post">
            @method('PATCH')
            @csrf
            <div class="form-group">
              <div class="form-row ">
                <div class="col-7 col-md-8">
                  {{-- 直前の入力値がない場合はテーブルの値を表示 --}}
                  <input type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" id="project_name" value="{{ old('project_name') ?? $edit_project->project_name }}" placeholder="プロジェクト名" />                    
                  @error('project_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary">変更する</button>
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
