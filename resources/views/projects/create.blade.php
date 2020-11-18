@extends('layout')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col col-md-offset-3 col-md-6">
        <nav class="panel panel-default">
          <div class="panel-heading">プロジェクトを追加する</div>
          <div class="panel-body">
            {{-- バリデーションエラー --}}
            @if($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            {{-- 入力フォーム --}}
            <form action="{{ route('projects.create') }}" method="post">
              @csrf
              <div class="form-group">
                <label for="project_name">プロジェクト名</label>
                <input type="text" class="form-control" name="project_name" id="project_name"　value="{{ old('project_name') }}" />
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary">送信</button>
              </div>
            </form>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection
