
<template>
  <div>
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
  </div>
</template>

<script>
export default {
  props: { recordId: String },
};
</script>

<style>
</style>
