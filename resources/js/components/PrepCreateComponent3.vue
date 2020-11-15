
<template>
  <div class="container">
    <h5 class="mb-4">準備する</h5>

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
        <label for="planned-time">Time</label>
        <select id="planned-time" class="form-control">
          <option>5分</option>
          <option>15分</option>
          <option selected>90分</option>
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
      <!-- カテゴリーボタンタイプ -->
      <!-- <div class="form-group">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-primary active">
                <input type="radio" name="categories" id="category-1" autocomplete="off" checked>Input
              </label>
              <label class="btn btn-success">
                <input type="radio" name="categories" id="category-2" autocomplete="off">Output
              </label>
              <label class="btn btn-secondary">
                <input type="radio" name="categories" id="category-3" autocomplete="off">Etc
              </label>
            </div>
          </div> -->

      <!-- 送信 -->
      <div class="text-center">
        <button type="submit" class="btn btn-info">Do!</button>
        <!-- <input type="submit" class="btn btn-info">Do!</input> -->
      </div>
    </form>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>
