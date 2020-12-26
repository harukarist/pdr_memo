<template>
  <div>
    <label for="memo_text">- Memo -</label><br />
    <textarea
      type="text"
      id="memo_text"
      name="memo_text"
      v-model="inputMemo"
      rows="6"
      class="w-100"
      autofocus
      placeholder="メモ欄としてご自由にご利用ください。入力された内容は振り返りフォームに引き継がれます"
    ></textarea>
  </div>
</template>

<script>
export default {
  name: "DoingMemo",
  props: {
    taskId: { type: String },
    memoText: { type: String },
  },
  data() {
    return {
      memo_data: this.memoText,
    };
  },
  computed: {
    inputMemo: {
      get() {
        return this.memo_data;
      },
      set(value) {
        this.postMemo(value);
      },
    },
  },
  methods: {
    postMemo(inputMemo) {
      axios
        .post("/api/tasks/" + this.taskId + "/memo", {
          memo_text: inputMemo,
        })
        .then((response) => {
          // this.memo_data = inputMemo;
          console.log("success", this.memo_data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
