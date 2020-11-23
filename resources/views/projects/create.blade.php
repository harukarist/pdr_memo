@extends('layouts.app')

@section('content')
  <div class="container">
    <section class="row justify-content-center">
      <div class="col-sm-8">
        <h5 class="c-heading__title">プロジェクトの作成</h5>
    
        <div class="card">
          <div class="card-header">プロジェクト名を入力して作成</div>
          <div class="card-body">
            {{-- 入力フォーム --}}
            <form action="{{ route('projects.create') }}" method="post">
              @csrf
              <div class="form-group">
                <div class="form-row ">
                  <div class="col-7 col-md-8">
                    <input type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" id="project_name"　value="{{ old('project_name') }}" placeholder="プロジェクト名"/>
                    @error('project_name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="col-auto">
                    <button type="submit" class="btn btn-primary">作成</button>
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
