<template>
  <div class="container">
    <h5 class="mb-4">実行する</h5>

    <!-- プログレスバー -->
    <div class="progressbar__wrapper text-center">
      <ul class="progressbar">
        <li class="active">Prep</li>
        <li class="active">Do</li>
        <li class="">Review</li>
      </ul>
    </div>

    <!-- ガイド -->
    <section class="p-guide__text mx-1 my-3">
      <p>90分間、集中して取り組みましょう！</p>
    </section>
    <!-- Prep入力内容の表示 -->
    <section class="p-prep__wrapper mb-5">
      <article class="p-record bg-white border p-2 mb-2">
        <div class="p-guide__task-name-wrapper mb-3">
          <a href="#" class="badge badge-success mr-1">Output</a>
          <h6 class="p-guide__task-name d-inline mb-0 align-middle">
            サンプル
          </h6>
        </div>
        <div class="p-record__details row">
          <div class="col-sm mb-2 mb-sm-1">
            <div class="text-secondary border-bottom px-1 clearfix">
              <span class="float-left mb-0">Prep</span>
              <!-- <router-link
                v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
              >
                <span class="float-right mb-0 ml-2 small">編集</span>
              </router-link> -->
              <span class="float-right mb-0 small">17:20</span>
            </div>
            <div class="p-record__text p-1">
              <p class="mb-1">入力画面下の一覧表示を作成する。</p>
              <strong class="mb-0 text-secondary">予定：90分</strong>
            </div>
          </div>
          <div class="col-sm mb-1"></div>
        </div>
      </article>
    </section>

    <!-- Reviewへ進むボタン -->
    <form method="head" action="/records/review/create">
      <div class="text-center">
        <p>完了しましたか？</p>
        <button type="submit" class="btn btn-primary">Review!</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>
