<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'PDR-memo') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    @yield('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <header>
            {{-- スマホではハンバーガーアイコン表示 navbar-expand-sm--}}
            <nav class="navbar navbar-expand-sm navbar-light bg-white">
                {{-- サイト名 --}}
                <a class="navbar-brand mr-5 p-0" href="/">PDR-memo</a>

                {{-- ハンバーガーメニュー --}}
                <button class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="navbar-nav">
                        @guest
                        {{-- 未ログイン --}}
                            <a class="c-navbar__link navbar-item" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt mr-1" aria-hidden="true"></i>ログイン</a>
                            ｜
                            <a class="c-navbar__link navbar-item" href="{{ route('register') }}">
                                <i class="fas fa-user-plus mr-1" aria-hidden="true"></i>ユーザー登録</a>
                        @else
                        {{-- ログイン後 --}}
                            {{-- <p class="c-navbar__item nav-item">こんにちは, {{ Auth::user()->name }}さん</p> --}}
                            {{-- ｜ --}}
                            <a href="{{ route('home') }}" class="c-navbar__item nav-item nav-link">
                                <i class="fas fa-list-ul mr-1" aria-hidden="true"></i>タスクリスト</a>
                            <a href="{{ route('records.index') }}" class="c-navbar__item nav-item nav-link">
                                <i class="fas fa-medal mr-1" aria-hidden="true"></i>これまでの記録</a>

                            <a href="#" id="js-logout" class="c-navbar__link navbar-item nav-link" onclick="event.preventDefault();
                            document.getElementById('js-logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-1" aria-hidden="true"></i>ログアウト</a>
                            <form id="js-logout-form mr-1" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                            </form>
                        @endguest
                    </div>
                </div>
            </nav>
        </header>
        {{-- フラッシュメッセージ --}}
        @if (session('flash_message'))
            <div class="alert alert-primary text-center" role="alert">
                {{ session('flash_message') }}
            </div>
        @endif
        <main>
            @yield('content')
            {{-- Router View resources/js/app.jsで指定 --}}
            {{-- <router-view></router-view> --}}
        </main>
    </div>
</body>
</html>
