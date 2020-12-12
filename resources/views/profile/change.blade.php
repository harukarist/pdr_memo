@extends('layouts.app')

@section('content')
  <div class="container c-container">

    <div class="card mb-2">
      <div class="card-header">お名前の変更</div>
      <div class="card-body">
        <div class="p-profile__current mb-4">
          現在のお名前
          {{ Auth::user()->name }} さん
        </div>

      <form method="POST" action="{{ route('profile.change') }}" >
        @method('PATCH')
        @csrf
        
        <div class="form-group form-inline">
          <label for="name">新しいお名前</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"　value="{{ old('name') }}"/>
              <button type="submit" class="btn btn-primary">変更</button>

              @error('name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
        </div>
      </form>

      </div>
    </div>


    <div class="card">
      <div class="card-header">メールアドレスの変更</div>
      <div class="card-body">
        <div class="p-profile__current mb-4">
          現在のメールアドレス
          {{ Auth::user()->email }}
        </div>
            
      <form method="POST" action="{{ route('email.change') }}" >
        @method('PATCH')
        @csrf

        <div class="form-group form-inline">
          <label for="new_email">新しいメールアドレス</label>
              <input type="email" class="form-control @error('new_email') is-invalid @enderror" name="new_email" id="new_email"　value="{{ old('new_email') }}"/>
              <button type="submit" class="btn btn-primary">変更</button>
              @error('new_email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
        </div>
      </form>

      </div>
    </div>

        
  </div>
@endsection
