<template>
  <div class="container py-4">
    <h5 class="mb-4">計画・振り返り</h5>
    <!-- 記録一覧 -->
    <div class="c-contents__wrapper">
      <!-- 総合計時間 -->
      <section class="p-total-time__wrapper mb-5">
        <h3 class="p-total-time__title ml-1">
          合計時間<small>（2019/11/1〜）</small>
        </h3>
        <div class="p-total-time__detail mb-3 ml-2">
          <a href="#" class="badge badge-primary mr-1">Input</a>
          <p class="d-inline mb-0 align-middle mr-2">424.5h</p>
          <a href="#" class="badge badge-success mr-1">Output</a>
          <p class="d-inline mb-0 align-middle mr-2">193h</p>
          <a href="#" class="badge badge-secondary mr-1">Etc</a>
          <p class="d-inline mb-0 align-middle mr-2">144.5h</p>
        </div>
      </section>

      <!-- 1日の合計 -->
      <section class="p-daily__wrapper mb-5">
        <h4 class="p-daily__title ml-1">2020/11/15（日）</h4>
        <div class="p-daily__details mb-3 ml-2">
          <a href="#" class="badge badge-primary mr-1">Input</a>
          <p class="d-inline mb-0 align-middle mr-2">1h</p>
          <a href="#" class="badge badge-success mr-1">Output</a>
          <p class="d-inline mb-0 align-middle mr-2">2h</p>
          <a href="#" class="badge badge-secondary mr-1">Etc</a>
          <p class="d-inline mb-0 align-middle mr-2">0.5h</p>
        </div>

        <!-- 個別の記録 -->
        <article class="p-record bg-white border p-0 mb-2">
          <div class="p-guide__task-name-wrapper p-2 mb-1">
            <a href="#" class="badge badge-success mr-1">Output</a>
            <h6 class="p-guide__task-name d-inline mb-0 align-middle">
              サンプル
            </h6>
          </div>
          <!-- PDR -->
          <div class="p-record__details row mx-0 my-2 border-bottom">
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <router-link
                  v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">17:20</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">入力画面下の一覧表示を作成する。</p>
                <strong class="mb-0 text-secondary">予定：90分</strong>
              </div>
            </div>
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Review</span>
                <router-link
                  v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">19:32</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">
                  一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
                  Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
                </p>
                <strong class="mb-0 text-secondary">実際：120分</strong>
              </div>
            </div>
          </div>
          <!-- PDR -->
          <div class="p-record__details row mx-0 my-2 border-bottom">
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <router-link
                  v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">17:20</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">入力画面下の一覧表示を作成する。</p>
                <strong class="mb-0 text-secondary">予定：90分</strong>
              </div>
            </div>
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Review</span>
                <router-link
                  v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">19:32</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">
                  一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
                  Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
                </p>
                <strong class="mb-0 text-secondary">実際：120分</strong>
              </div>
            </div>
          </div>
          <!-- PDR -->
          <div class="p-record__details row mx-0 my-2 border-bottom">
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <router-link
                  v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">17:20</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">入力画面下の一覧表示を作成する。</p>
                <strong class="mb-0 text-secondary">予定：90分</strong>
              </div>
            </div>
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Review</span>
                <router-link
                  v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">19:32</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">
                  一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
                  Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
                </p>
                <strong class="mb-0 text-secondary">実際：120分</strong>
              </div>
            </div>
          </div>
          <!-- PDR -->
          <div class="p-record__details row mx-0 my-2">
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <router-link
                  v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">17:20</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">入力画面下の一覧表示を作成する。</p>
                <strong class="mb-0 text-secondary">予定：90分</strong>
              </div>
            </div>
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Review</span>
                <router-link
                  v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">19:32</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">
                  一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
                  Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
                </p>
                <strong class="mb-0 text-secondary">実際：120分</strong>
              </div>
            </div>
          </div>
        </article>
      </section>
      <!-- 1日の合計 -->
      <section class="p-daily__wrapper mb-5">
        <h4 class="p-daily__title ml-1">2020/11/15（日）</h4>
        <div class="p-daily__details mb-3 ml-2">
          <a href="#" class="badge badge-primary mr-1">Input</a>
          <p class="d-inline mb-0 align-middle mr-2">1h</p>
          <a href="#" class="badge badge-success mr-1">Output</a>
          <p class="d-inline mb-0 align-middle mr-2">2h</p>
          <a href="#" class="badge badge-secondary mr-1">Etc</a>
          <p class="d-inline mb-0 align-middle mr-2">0.5h</p>
        </div>

        <!-- 個別の記録 -->
        <article class="p-record bg-white border p-0 mb-2">
          <div class="p-guide__task-name-wrapper p-2 mb-1">
            <a href="#" class="badge badge-success mr-1">Output</a>
            <h6 class="p-guide__task-name d-inline mb-0 align-middle">
              サンプル
            </h6>
          </div>
          <!-- PDR -->
          <div class="p-record__details row mx-0 my-2 border-bottom">
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <router-link
                  v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">17:20</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">入力画面下の一覧表示を作成する。</p>
                <strong class="mb-0 text-secondary">予定：90分</strong>
              </div>
            </div>
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Review</span>
                <router-link
                  v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">19:32</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">
                  一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
                  Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
                </p>
                <strong class="mb-0 text-secondary">実際：120分</strong>
              </div>
            </div>
          </div>
          <!-- PDR -->
          <div class="p-record__details row mx-0 my-2 border-bottom">
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <router-link
                  v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">17:20</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">入力画面下の一覧表示を作成する。</p>
                <strong class="mb-0 text-secondary">予定：90分</strong>
              </div>
            </div>
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Review</span>
                <router-link
                  v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">19:32</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">
                  一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
                  Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
                </p>
                <strong class="mb-0 text-secondary">実際：120分</strong>
              </div>
            </div>
          </div>
          <!-- PDR -->
          <div class="p-record__details row mx-0 my-2 border-bottom">
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <router-link
                  v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">17:20</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">入力画面下の一覧表示を作成する。</p>
                <strong class="mb-0 text-secondary">予定：90分</strong>
              </div>
            </div>
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Review</span>
                <router-link
                  v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">19:32</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">
                  一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
                  Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
                </p>
                <strong class="mb-0 text-secondary">実際：120分</strong>
              </div>
            </div>
          </div>
          <!-- PDR -->
          <div class="p-record__details row mx-0 my-2">
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Prep</span>
                <router-link
                  v-bind:to="{ name: 'prep.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">17:20</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">入力画面下の一覧表示を作成する。</p>
                <strong class="mb-0 text-secondary">予定：90分</strong>
              </div>
            </div>
            <div class="p-record__item-wrapper col-sm">
              <div class="text-secondary clearfix">
                <span class="p-record__item-title float-left mb-0">Review</span>
                <router-link
                  v-bind:to="{ name: 'review.edit', params: { recordId: 1 } }"
                >
                  <span class="float-right mb-0 ml-2 small">編集</span>
                </router-link>
                <span class="float-right mb-0 small">19:32</span>
              </div>
              <div class="p-record__item-text">
                <p class="mb-1">
                  一覧表示を作成。細かい位置調整に手間取って時間がかかった。<br />
                  Bootstrapのクラス名をチートシートで確認しつつマークアップしたが、margin,paddingや文字のスタイルはSass側で微調整した方がよかったかも。
                </p>
                <strong class="mb-0 text-secondary">実際：120分</strong>
              </div>
            </div>
          </div>
        </article>
      </section>
    </div>
  </div>
</template>

<script>
// import RecordPrepComponent from "./RecordPrepComponent";
// import RecordReviewComponent from "./RecordReviewComponent";
export default {};
</script>

<style>
</style>
