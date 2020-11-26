<template>
  <!-- ナビバー -->
  <nav class="navbar navbar-expand-sm navbar-light bg-white shadow-sm">
    <div class="container">
      <!-- サイト名 -->
      <router-link v-bind:to="{ name: 'mypage' }" class="navbar-brand">
        <h5 class="navbar-brand mr-5 p-0">PDR-memo</h5>
      </router-link>
      <!-- <a class="navbar-brand" href="">PDR-memo </a> -->

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
        <!-- メニューリンク -->
        <ul class="navbar-nav mr-auto">
          <router-link
            v-bind:to="{ name: 'prep.create' }"
            class="c-navbar__link"
          >
            <li class="c-navbar__item nav-item nav-link">タスクを実行</li>
          </router-link>
          <router-link
            v-bind:to="{ name: 'record.list' }"
            class="c-navbar__link"
          >
            <li class="c-navbar__item nav-item nav-link">計画・振り返り</li>
          </router-link>
          <!-- <li class="c-navbar__item nav-item nav-link">タスクを選ぶ</li> -->
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
              <a
                class="dropdown-item"
                href="http://127.0.0.1:8000/logout"
                onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();"
              >
                ログアウト
              </a>
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
