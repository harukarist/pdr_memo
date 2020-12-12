@extends('layouts.app')

@section('content')
  <div class="container c-container">
    <h5 class="c-heading__title">メールアドレスの変更</h5>
    <div class="c-form__wrapper">
      {{-- <form method="POST" action="{{ route('projects.post') }}"> --}}




        <form method="POST" action="{{ route('profile.reset') }}" >
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
            新しいメールアドレスを入力してください
            <input type="email" name="new_email">
            <input type="submit">
        </form>
        
    </div>
  </div>
@endsection
