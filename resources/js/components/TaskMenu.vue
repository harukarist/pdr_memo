<template>
  <div>
    <div class="p-task__menu d-flex justify-content-end mr-3 p-0">
      <!-- 優先度 -->
      <div class="p-task__priority ml-1 mr-3">
        <small class="m-0 p-0">
          <i
            class="p-task__icon fas fa-star"
            :class="{
              active: scale >= 1,
              isShow: priority_level >= 1,
            }"
            @mouseover="scale = 1"
            @mouseleave="scale = 0"
            @click="changePriority(taskId, 1)"
            aria-hidden="true"
          ></i>
          <i
            class="p-task__icon fas fa-star"
            :class="{
              active: scale >= 2,
              isShow: priority_level >= 2,
            }"
            @mouseover="scale = 2"
            @mouseleave="scale = 0"
            @click="changePriority(taskId, 2)"
            aria-hidden="true"
          ></i>
          <i
            class="p-task__icon fas fa-star"
            :class="{
              active: scale >= 3,
              isShow: priority_level >= 3,
            }"
            @mouseover="scale = 3"
            @mouseleave="scale = 0"
            @click="changePriority(taskId, 3)"
            aria-hidden="true"
          ></i>
        </small>
      </div>
      <!-- 期限日設定 -->
      <div class="p-task__due-date" @click="showDatePicker = !showDatePicker">
        <i
          class="p-task__icon fas fa-calendar-day"
          aria-hidden="true"
          :class="{ active: inputDueDate }"
        ></i>
        <span class="badge badge-light"> {{ dueDate_data }}</span>
      </div>
      <!-- タスク削除 -->
      <span class="ml-2">
        <i
          @click="deleteTask(taskId)"
          class="p-task__icon fas fa-trash-alt"
          aria-hidden="true"
        ></i>
      </span>
    </div>
    <div v-show="showDatePicker">
      <input
        type="date"
        name="due_date"
        id="due_date"
        class="form-control"
        v-model="inputDueDate"
      />
    </div>
  </div>
</template>

<script>
import vuejsDatepicker from "vuejs-datepicker";
import { en, ja } from "vuejs-datepicker/dist/locale";

export default {
  name: "TaskMenu",
  components: {
    "vuejs-datepicker": vuejsDatepicker,
  },
  // props: ["taskId", "priority", "dueDate"],
  props: {
    taskId: { type: String },
    priority: { type: String },
    dueDate: { type: String },
  },
  data() {
    return {
      en,
      ja,
      isDeleted: false,
      priority_level: this.priority,
      scale: 0,
      showDatePicker: false,
      dueDate_data: this.dueDate,
    };
  },
  computed: {
    inputDueDate: {
      get() {
        return this.dueDate_data;
      },
      set(value) {
        this.changeDueDate(value);
      },
    },
  },
  methods: {
    // 期限日の変更
    changeDueDate(inputDueDate) {
      axios
        .put("/api/tasks/" + this.taskId + "/changeDueDate", {
          due_date: inputDueDate,
        })
        .then((response) => {
          this.dueDate_data = inputDueDate;
          // console.log("success", this.dueDate_data, this.taskId);
          this.showDatePicker = !this.showDatePicker;
        })
        .catch((error) => {
          // console.log(error);
        });
    },
    // 削除
    deleteTask(taskId) {
      if (
        confirm(
          "タスクを削除すると、予定や振り返りのデータも一緒に削除されます。本当にこのタスクを削除しますか？"
        )
      )
        axios
          .delete("/api/tasks/" + taskId + "/delete")
          .then((res) => {
            // console.log(taskId, " is deleted");
            this.isDeleted = true;
            this.$emit("task-deleted");
          })
          .catch((error) => {
            // console.log(error);
          });
    },
    changePriority(taskId, scale) {
      if (this.priority_level == scale) {
        scale = 0;
      }
      axios
        .put("/api/tasks/" + taskId + "/priority/" + scale)
        .then((response) => {
          this.priority_level = scale;
          // console.log(this.priority_level, "success");
          this.scale = 0; //scaleを0に戻す
        })
        .catch((error) => {
          // console.log(error);
        });
    },
  },
};
</script>
