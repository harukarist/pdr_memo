
<template>
  <div class="container">
    <h5 class="mb-4">活動記録を編集する</h5>

    <button
      type="button"
      class="btn btn-outline-secondary btn-sm mb-3"
      data-toggle="modal"
      data-target="#modal1"
    >
      この記録を削除する
    </button>
    <div
      class="modal fade"
      id="modal1"
      tabindex="-1"
      role="dialog"
      aria-labelledby="label1"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="label1">この記録を削除しますか？</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            記録された内容は編集画面から変更できます。本当にこの記録を削除しますか？
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">
              No
            </button>
            <button type="button" class="btn btn-danger">Yes</button>
          </div>
        </div>
      </div>
    </div>

    <!-- プログレスバー -->
    <!-- <div class="progressbar__wrapper">
      <ul class="progressbar">
        <li class="active">Prep</li>
        <li class="active">Do</li>
        <li class="active">Review</li>
      </ul>
    </div> -->

    <!-- Prep/Review入力フォーム -->
    <form method="head" action="/records">
      <!-- レコードID -->
      <div class="form-group row clearfix">
        <label for="id" class="col-auto col-form-label">ID</label>
        <div class="col-auto">
          <input
            type="text"
            class="form-control-plaintext"
            readonly
            id="id"
            v-bind:value="recordId"
          />
        </div>
      </div>

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
          <option>90分</option>
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
              checked
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
        <button type="submit" class="btn btn-primary">記録する！</button>
        <!-- <input type="submit" class="btn btn-primary">Do!</input> -->
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: { recordId: String },
};
</script>

<style>
</style>
