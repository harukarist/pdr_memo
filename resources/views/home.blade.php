@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <div class="row">
      <div class="col">
        <div class="card mt-5">
          <div class="card-header">
            ようこそ！まずはプロジェクトを作成しましょう
          </div>
          <div class="card-body">
            <div class="text-center">
              <a href="{{ route('projects.create') }}" class="btn btn-primary">
                プロジェクト作成ページへ
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
