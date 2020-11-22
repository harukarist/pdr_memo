
<template>
  <div class="container">
    <h5 class="mb-4">振り返る</h5>

    <!-- プログレスバー -->
    <div class="progressbar__wrapper">
      <ul class="progressbar">
        <li class="active">Prep</li>
        <li class="active">Do</li>
        <li class="active">Review</li>
      </ul>
    </div>
    <!-- ガイド -->
    <section class="p-guide__text mx-1 my-3">
      <p>おつかれさまでした！結果を振り返ってみましょう。</p>
    </section>

    <!-- Prep入力内容の表示 -->
    <section class="p-prep__wrapper mb-4">
      <article class="p-record bg-white border p-2 mb-2">
        <div class="p-record__title-wrapper mb-3">
          <a href="#" class="badge badge-success mr-1">Output</a>
          <h6 class="p-record__title d-inline mb-0 align-middle">サンプル</h6>
        </div>
        <div class="p-record__details row">
          <div class="col-sm mb-2 mb-sm-1">
            <div class="text-secondary border-bottom px-1 clearfix">
              <span class="float-left mb-0">Prep</span>
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

    <!-- Review入力フォーム -->
    <form method="head" action="/records/prep/create2">
      <!-- Reviewテキストエリア -->
      <div class="form-group">
        <label for="review">Review</label>
        <textarea id="review" class="form-control"></textarea>
        <span id="help-review" class="form-text text-muted">
          <ul>
            <li>何を実行した？</li>
            <li>準備通りに実行できた？</li>
            <li>前に比べて変わった点は？</li>
            <li>学んだことは？</li>
            <li>次回に工夫したい点は？</li>
          </ul>
        </span>
      </div>

      <!-- 実行時間 -->
      <div class="form-group">
        <label for="actual-time">実際の時間</label>
        <div class="input-group w-25">
          <input type="text" class="form-control" id="actual-time" value="90" />
          <div class="input-group-append">
            <div class="input-group-text">分間</div>
          </div>
        </div>
      </div>

      <!-- 送信 -->
      <div class="text-center">
        <button type="submit" class="btn btn-primary">完了！</button>
        <!-- <input type="submit" class="btn btn-primary">Do!</input> -->
      </div>
    </form>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>
