@extends('layouts.app')

@section('content')
<div class="container">
  <h5 class="mb-4">活動記録を編集する</h5>

  <button type="button" class="btn btn-outline-secondary btn-sm mb-3" data-toggle="modal" data-target="#modal1">
    この記録を削除する
  </button>
  <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="label1">この記録を削除しますか？</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          削除するとこれまでの合計時間も変更されます。<br />記録された内容は編集画面から変更できます。本当にこの記録を削除しますか？
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
    @method('PATCH')
    @csrf
    <!-- レコードID -->
    <div class="form-group row clearfix">
      <label for="id" class="col-auto col-form-label">ID</label>
      <div class="col-auto">
        <input type="text" class="form-control-plaintext" readonly id="id" v-bind:value="recordId" />
      </div>
    </div>

    <!-- タスク名 -->
    <div class="form-group">
      <label for="task-name">Task</label>
      <input type="text" id="task-name" class="form-control" />
    </div>

    <!-- カテゴリー -->
    <div class="form-group">
      <label for="category">Category</label>
      <div class="pl-1">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="category" id="category-a" checked />
          <label class="form-check-label" for="category-a">
            <h5><span class="badge badge-primary mr-1">Input</span></h5>
          </label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="category" id="category-b" />
          <label class="form-check-label" for="category-b">
            <h5><span class="badge badge-success mr-1">Output</span></h5>
          </label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="category" id="category-c" />
          <label class="form-check-label" for="category-c">
            <h5><span class="badge badge-secondary mr-1">Etc</span></h5>
          </label>
        </div>
      </div>
    </div>

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
        <input type="text" class="form-control" id="actual-time" value="30" />
        <div class="input-group-append">
          <div class="input-group-text">分間</div>
        </div>
      </div>
    </div>

    <!-- 送信 -->
    <div class="text-center">
      <button type="submit" class="btn btn-primary">修正する</button>
      <!-- <input type="submit" class="btn btn-primary">Do!</input> -->
    </div>
  </form>
</div>
@endsection
