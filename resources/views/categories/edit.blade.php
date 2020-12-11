@extends('layouts.app')

@section('content')
<div class="container c-container">
  <div class="col-sm-8">
    <div class="card">
      <div class="card-header">
        カテゴリーの編集
      </div>
      <div class="card-body">
                  {{-- 削除ボタン --}}
        <div class="p-category__delete text-right">
          <form action="{{ route('categories.delete', ['category_id' => $edit_category->id ]) }}" method="post">
            @method('DELETE')
            @csrf
          <button type="button" class="btn btn-outline-secondary btn-sm mb-3" data-toggle="modal" data-target="#modal1">
            このカテゴリーを削除する
          </button>
          <div class="modal fade text-left" id="modal1" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="label1">このカテゴリーを削除しますか？</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  カテゴリーを削除すると、そのカテゴリー内のタスクも一緒に削除されます。<br>
                  カテゴリーの内容は編集画面から変更できます。本当にこのカテゴリーを削除しますか？
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
        <div class="p-category__edit">
          <form action="{{ route('categories.edit', ['category_id' => $edit_category->id]) }}" method="post">
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

            {{-- カテゴリー名 --}}
            <div class="form-group">
              <label for="category_id">カテゴリー名</label>
                  {{-- 直前の入力値がない場合はテーブルの値を表示 --}}
                  <input type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name" id="category_name" value="{{ old('category_name') ?? $edit_category->category_name }}" />                    
            </div>
              <button type="submit" class="btn btn-primary">変更</button>
          </form>
        </div>
      </div>
    </div>

    @include('categories.category_list',['categories'=>$categories])
    @include('categories.form_create')

    
  </div>
</div>
@endsection
