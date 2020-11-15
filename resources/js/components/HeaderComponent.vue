<template>
  <!-- ナビバー -->
  <nav class="navbar navbar-expand-sm navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="#">pdr-memo </a>

      <!-- ハンバーガーメニュー -->
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
          <router-link
            v-bind:to="{ name: 'record.list' }"
            class="c-navbar__link"
          >
            <li class="c-navbar__item nav-item nav-link">これまでの記録</li>
          </router-link>
          <router-link
            v-bind:to="{ name: 'prep.create' }"
            class="c-navbar__link"
          >
            <li class="c-navbar__item nav-item nav-link">タスクを実行する</li>
          </router-link>
          <li class="c-navbar__item nav-item nav-link">タスクを選ぶ</li>
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a
              id="navbarDropdown"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              v-pre
            >
              harukarist
              <span class="caret"></span>
            </a>
            <div
              class="dropdown-menu dropdown-menu-right"
              aria-labelledby="navbarDropdown"
            >
              <a class="dropdown-item" href="#">menu1</a>
              <a class="dropdown-item" href="#">menu2</a>
              <a class="dropdown-item" href="#">menu3</a>
              <a
                class="dropdown-item"
                href="http://127.0.0.1:8000/logout"
                onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();"
              >
                ログアウト
              </a>
              <form
                id="logout-form"
                action="http://127.0.0.1:8000/logout"
                method="POST"
                style="display: none"
              >
                <input
                  type="hidden"
                  name="_token"
                  value="hK6Mkl639IXtXzy8Kl73qjFPcZvV9FoeE68jrzQC"
                />
              </form>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {};
</script>

<style>
</style>
