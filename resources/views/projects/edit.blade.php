@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-sm-8">
      <h5 class="c-heading__title">プロジェクトの編集</h5>

      <div class="card">
        <div class="card-header">プロジェクト名の編集</div>
        <div class="card-body">

          <form action="{{ route('projects.edit', ['project_id' => $project->id]) }}" method="post">
            @csrf
            <div class="form-group">
              <div class="form-row ">
                <div class="col-7 col-md-8">
                  {{-- 直前の入力値がない場合はテーブルの値を表示 --}}
                  <input type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" id="project_name" value="{{ old('project_name') ?? $project->project_name }}" placeholder="プロジェクト名" />                    
                  @error('project_name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary">変更</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-sm-8">
      <div class="card">
        <div class="card-header">プロジェクトの編集・削除</div>
        <div class="card-body">
          <table class="table">
            <tbody>
              @foreach($projects as $project)
              <tr>
                <td>{{ $project->project_name }}</td>
                <td><a href="{{ route('projects.edit', ['project_id' => $project->id]) }}">編集</a></td>
                <td><a href="{{ route('projects.delete', ['project_id' => $project->id]) }}">削除</a></td>
              </tr>
              @endforeach
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
