<template>
  <div class="p-tasklist__list">
    <ul v-for="task in tasks">
      <li>{{ task.task_name }}</li>
      <li>{{ task.project_name }}</li>
      <!-- <li>{{ task.due_date }}</li> -->
      <li>{{ task.status }}</li>
      <li>{{ task.priority }}</li>
    </ul>
  </div>
</template>

<script>
export default {
  // props: {
  //   currentProject: Object,
  //   tasks: Object,
  // },
  data: function () {
    return {
      tasks: [],
    };
  },
  methods: {
    getTasks() {
      // タスク一覧取得APIにアクセスし、レスポンスを空配列に格納
      axios.get("/api/list").then((res) => {
        this.tasks = res.data;
      });
    },
  },
  mounted() {
    // 画面描画時にタスク一覧取得メソッド実行
    this.getTasks();
  },
  computed: {
    // classCheckBox: function () {
    //   return {
    //     far: true,
    //     "fa-check-square": this.propsobj.isDone,
    //     "fa-square": !this.propsobj.isDone,
    //     "icon-checkbox": true,
    //   };
    // },
  },
};
</script>
