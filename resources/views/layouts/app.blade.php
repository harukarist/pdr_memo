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
    <script src="https://kit.fontawesome.com/f2d7c28546.js" crossorigin="anonymous"></script>
    
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> --}}
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Noto+Sans+JP:wght@400;500&display=swap" rel="stylesheet">

    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    <link rel="shortcut icon" href="https://pdr-memo.sakuraweb.com/favicon.ico">
    <link rel="icon" href="https://pdr-memo.sakuraweb.com/favicon.ico">
</head>
<body>
    <div id="app">
        <header>
            {{-- スマホではハンバーガーアイコン表示 navbar-expand-sm--}}
            <nav class="navbar navbar-expand-sm navbar-light bg-white">
                {{-- サイト名 --}}
                <a class="navbar-brand mr-5 p-0" href="{{ config('app.url', 'https://harukarist.sakura.ne.jp/pdr-memo') }}">PDR-memo</a>

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
                            <a class="c-navbar__item nav-item nav-link text-dark" href="{{ route('register') }}">
                                <i class="fas fa-user-plus mr-1" aria-hidden="true"></i>ユーザー登録</a>
                            <a class="c-navbar__item nav-item nav-link text-dark" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt mr-1" aria-hidden="true"></i>ログイン</a>
                        @else
                        {{-- ログイン後 --}}
                            {{-- <p class="c-navbar__item nav-item">こんにちは, {{ Auth::user()->name }}さん</p> --}}
                            {{-- ｜ --}}
                            <a href="{{ route('home') }}" class="c-navbar__item nav-item nav-link text-dark">
                                <i class="fas fa-list-ul mr-1" aria-hidden="true"></i>タスクリスト</a>

                                <div class="d-block d-sm-none">
                                    <ul class="list-group list-group-flush mt-2 mb-4">
                                    @php
                                        $projects = Auth::user()->projects;
                                    @endphp
                                    @if($projects)
                                    @include('tasks.project_nav',['projects'=>$projects, 'current_project'=>$projects->first()])
                                    @endif
                                    <li class="list-group-item p-1">
                                        <a href="{{ route('projects.create') }}" class="btn btn-outline-secondary btn-block">
                                          プロジェクトを追加
                                        </a>
                                      </li>
                                    </ul>
                                </div>
                            <a href="{{ route('reports.weekly') }}" class="c-navbar__item nav-item nav-link text-dark">
                                <i class="fas fa-medal mr-1" aria-hidden="true"></i>これまでの記録</a>

                            <a href="{{ route('profile.change') }}" class="c-navbar__item nav-item nav-link text-dark">
                                <i class="fas fa-user-edit mr-1" aria-hidden="true"></i>プロフィールの編集</a>
                                
                            <a href="{{ route('logout') }}" id="js-logout" class="c-navbar__link navbar-item nav-link text-dark" onclick="event.preventDefault();
                            document.getElementById('js-logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-1" aria-hidden="true"></i>ログアウト</a>
                            <form id="js-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
    @yield('scripts')
</body>
</html>
