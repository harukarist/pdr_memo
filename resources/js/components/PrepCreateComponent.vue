
<template>
  <div class="container py-4">
    <!-- ガイド -->
    <!-- <section class="p-guide__text mx-1 my-3">
      <p>
        以下の内容を記録しました。おめでとう！<br />
        少し休憩したら、次の準備を始めましょう。
      </p>
    </section> -->
    <!-- 個別の記録 -->
    <!-- <article class="p-record bg-white border p-2 mb-5">
      <div class="p-guide__task-name-wrapper mb-3">
        <a href="#" class="badge badge-success mr-1">Output</a>
        <h6 class="p-guide__task-name d-inline mb-0 align-middle">サンプル</h6>
      </div>
      <div class="p-record__details row">
        <div class="col-sm mb-2 mb-sm-1">
          <div class="text-secondary border-bottom px-1 clearfix">
            <span class="float-left mb-0">Prep</span>
            <router-link
              v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
            >
              <span class="float-right mb-0 ml-2 small">編集</span>
            </router-link>
            <span class="float-right mb-0 small">17:20</span>
          </div>
          <div class="p-record__text p-1">
            <p class="mb-1">入力画面下の一覧表示を作成する。</p>
            <strong class="mb-0 text-secondary">予定：90分</strong>
          </div>
        </div>
        <div class="col-sm mb-1">
          <div class="text-secondary border-bottom px-1 clearfix">
            <span class="float-left mb-0">Review</span>
            <router-link
              v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
            >
              <span class="float-right mb-0 ml-2 small">編集</span>
            </router-link>
            <span class="float-right mb-0 small">19:32</span>
          </div>
          <div class="p-record__text p-1">
            <p class="mb-1">
              一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
              Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
            </p>
            <strong class="mb-0 text-secondary">実際：120分</strong>
          </div>
        </div>
      </div>
    </article> -->

    <h5 class="mb-4">準備をする</h5>
    <!-- プログレスバー -->
    <div class="progressbar__wrapper">
      <ul class="progressbar">
        <li class="active">Prep</li>
        <li class="">Do</li>
        <li class="">Review</li>
      </ul>
    </div>

    <!-- Prep入力フォーム -->
    <form method="head" action="/records/do/create">
      <!-- タスク名 -->
      <div class="form-group">
        <label for="task-name">Task</label>
        <input type="text" id="task-name" class="form-control" />
      </div>

      <!-- Prepテキストエリア -->
      <div class="form-group">
        <label for="prep">Prep</label>
        <textarea id="prep" class="form-control"></textarea>
        <!-- $('[data-toggle="tooltip"]').tooltip(); で呼び出す -->
        <!-- <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 　title="Tooltip message"></i> -->
        <span id="help-prep" class="form-text text-muted">
          <ul>
            <li>これから何をする？</li>
            <li>その理由、目標、目的は？</li>
            <li>どのようなプロセスで行う？</li>
            <li>必要なリソースは？</li>
            <li>他に関わる人は？</li>
          </ul>
        </span>
      </div>

      <!-- 予定時間 -->
      <div class="form-group">
        <label for="planned-time">予定時間</label>
        <select id="planned-time" class="form-control">
          <option>5分</option>
          <option>15分</option>
          <option selected>30分</option>
          <option>45分</option>
          <option>60分</option>
          <option>90分</option>
        </select>
      </div>

      <!-- カテゴリー -->
      <div class="form-group">
        <label for="category">Category</label>
        <div class="pl-1">
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="category"
              id="category-a"
            />
            <label class="form-check-label" for="category-a">
              <h5><span class="badge badge-primary mr-1">Input</span></h5>
            </label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="category"
              id="category-b"
            />
            <label class="form-check-label" for="category-b">
              <h5><span class="badge badge-success mr-1">Output</span></h5>
            </label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="category"
              id="category-c"
            />
            <label class="form-check-label" for="category-c">
              <h5><span class="badge badge-secondary mr-1">Etc</span></h5>
            </label>
          </div>
        </div>
      </div>

      <!-- 送信 -->
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Do!</button>
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
