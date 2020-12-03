@extends('layouts.app')

@section('content')
<div class="jumbotron jumbotron-fluid">
  <div class="container c-container__fluid text-center">
    <h3 class="mb-4">記録と振り返りを力に変えよう！</h3>

    <p>PDR-memo は、ハーバードビジネススクールで提唱されている<br>
      PDR(Prep-Do-Review) というマネジメント手法を活用して<br>
      ステップごとに計画・振り返りを行うことで<br>
      仕事や勉強を楽しく集中して続けられるアプリです。</p>

    <div class="py-3">
      <a href="/register" class="btn btn-dark">ユーザー登録</a>
    </div>
    <div class="py-3">
      <a href="/login" class="btn btn-dark">ログイン</a>
    </div>
  </div>
</div>
@endsection
