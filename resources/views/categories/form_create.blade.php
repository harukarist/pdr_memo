
<div class="card">
  <div class="card-header">
    カテゴリーの作成
  </div>
  <div class="card-body">
    {{-- 入力フォーム --}}
    <form method="POST" action="{{ route('categories.create') }}">
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
        <label for="category_name">カテゴリー名</label>
            <input type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name" id="category_name"　value="{{ old('category_name') }}"/>
      </div>

        <button type="submit" class="btn btn-primary">作成</button>
    </form>
  </div>
</div>
