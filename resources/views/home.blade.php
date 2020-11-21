@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col col-md-offset-3 col-md-6">
        <nav class="panel panel-default">
          <div class="panel-heading">
            ようこそ！まずはプロジェクトを作成しましょう
          </div>
          <div class="panel-body">
            <div class="text-center">
              <a href="{{ route('projects.create') }}" class="btn btn-primary">
                プロジェクト作成ページへ
              </a>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection
