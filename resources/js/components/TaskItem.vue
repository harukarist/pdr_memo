<template>
  <div
    class="p-task__wrapper col-10"
    v-show="!isDeleted"
    @mouseover="showMenu = true"
    @mouseout="showMenu = false"
  >
    <!-- <div @mouseover="showMenu = true" @mouseout="showMenu = false"> -->
    <div>
      <div class="p-task__main p-0 d-flex">
        <!-- チェックボックス -->
        <div
          class="p-task__checkbox pr-2"
          :class="{ isDone: isDone }"
          @click="toggleDone"
        >
          <i :class="classCheckBox" aria-hiden="true"></i>
        </div>
        <!-- タスク名 -->
        <div
          v-show="!showEditBox"
          class="p-task__taskName"
          :class="{ active: isActiveText, isDone: isDone }"
          @mouseover="isActiveText = true"
          @mouseleave="isActiveText = false"
          @click="showEditBox = true"
        >
          {{ taskName_data }}
          <i
            class="p-task__icon fas fa-pencil-alt"
            v-show="showMenu"
            aria-hidden="true"
          ></i>
        </div>
        <!-- 編集ボックス -->
        <div v-show="showEditBox" class="p-task__editArea col-12 mx-0 px-0">
          <!-- <textarea
            type="text"
            class="p-task__editBox"
            :value="taskName_data"
            ref="editBox"
            @change="editTaskName($event)"
            @keyup.enter="checkKeyUp($event)"
            @blur="editTaskName($event)"
          > -->
          <textarea
            type="text"
            class="p-task__editBox"
            :value="taskName_data"
            @keyup.shift.enter="checkKeyUp($event)"
          >
          </textarea>
        </div>
      </div>
      <!-- <span>
        <i
          v-show="showMenu || isMust"
          :class="classMustIcon"
          class="p-task__icon fa-star icon-star"
          @click="toggleMust"
          aria-hidden="true"
        ></i>
        <i
          v-show="showMenu || isSmall"
          :class="classSmallIcon"
          class="p-task__icon fa-stopwatch icon-stopwatch"
          @click="toggleSmall"
          aria-hidden="true"
        ></i>
      </span> -->
    </div>
    <div class="p-task__details">
      <!-- <div
      class="p-task__details"
      @mouseover="showMenu = true"
      @mouseout="showMenu = false"
    > -->
      <!-- 優先度 -->
      <div class="p-task__priority d-inline-block p-3">
        <small class="m-0 p-0">
          <i
            class="p-task__icon fas fa-star"
            v-show="showMenu || priority_level >= 1"
            :class="{ active: scale >= 1, isShow: priority_level >= 1 }"
            @mouseover="scale = 1"
            @mouseleave="scale = 0"
            @click="changePriority(taskId, 1)"
            aria-hidden="true"
          ></i>
          <i
            class="p-task__icon fas fa-star"
            v-show="showMenu || priority_level >= 1"
            :class="{ active: scale >= 2, isShow: priority_level >= 2 }"
            @mouseover="scale = 2"
            @mouseleave="scale = 0"
            @click="changePriority(taskId, 2)"
            aria-hidden="true"
          ></i>
          <i
            class="p-task__icon fas fa-star"
            v-show="showMenu || priority_level >= 1"
            :class="{ active: scale >= 3, isShow: priority_level >= 3 }"
            @mouseover="scale = 3"
            @mouseleave="scale = 0"
            @click="changePriority(taskId, 3)"
            aria-hidden="true"
          ></i>
        </small>
      </div>
      <!-- 期限日設定 -->
      <i
        class="p-task__icon fas fa-calendar-day"
        aria-hidden="true"
        v-show="showMenu || due_date"
        :class="{ active: due_date }"
        @click="showDatePicker = !showDatePicker"
      ></i>
      <span class="badge badge-light"> {{ due_date }}</span>
      <div v-show="showDatePicker">
        <input
          type="date"
          name="due_date"
          id="due_date"
          class="form-control"
          @change="changeDueDate(due_date)"
          :value="due_date"
        />
        <button
          type="submit"
          @click="changeDueDate(due_date)"
          class="btn btn-primary"
        >
          変更
        </button>
      </div>
      <!-- タスク削除 -->
      <span class="ml-2">
        <i
          v-show="showMenu"
          @click="deleteTask(taskId)"
          class="p-task__icon fas fa-trash-alt"
          aria-hidden="true"
        ></i>
      </span>
    </div>
  </div>
</template>

<script>
import vuejsDatepicker from "vuejs-datepicker";
import { en, ja } from "vuejs-datepicker/dist/locale";

export default {
  components: {
    "vuejs-datepicker": vuejsDatepicker,
  },
  props: ["taskId", "taskStatus", "taskName", "priority", "dueDate"],
  // props: {
  //   taskId: { type: String },
  //   taskStatus: { type: String },
  //   taskName: { type: String },
  //   priority: { type: String },
  //   dueDate: { type: String },
  // },
  data() {
    return {
      en,
      ja,
      isDone: this.taskStatus == 4 ? true : false,
      showEditBox: false,
      keyDownCode: "",
      showMenu: false,
      isActiveText: false,
      isDeleted: false,
      priority_level: this.priority,
      scale: 0,
      showDatePicker: false,
      due_date: this.dueDate,
      taskName_data: this.taskName,
      isMust: false,
      isSmall: false,
    };
  },
  computed: {
    classCheckBox() {
      return {
        "far fa-check-circle": this.isDone,
        "far fa-circle": !this.isDone,
      };
    },
    classMustIcon: function () {
      return {
        "fas isOn": this.isMust,
        "far isOff": !this.isMust,
      };
    },
    classSmallIcon: function () {
      return {
        isOn: this.isSmall,
        isOff: !this.isSmall,
      };
    },
  },

  methods: {
    checkKeyUp: function (e) {
      // Shift+Enterキーが押された時
      let text = e.currentTarget.value;
      if (text) {
        this.editTaskName(text);
      }
      this.showEditBox = false;
    },
    editTaskName: function (text) {
      this.taskName_data = text;
      axios
        .put("/api/tasks/" + this.taskId + "/edit", {
          task_name: text,
        })
        .then((response) => {
          this.taskName_data = text;
          console.log("success", this.taskName_data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    toggleDone() {
      if (this.isDone) {
        this.changeToUndone(this.taskId);
        this.isDone = !this.isDone;
      } else {
        this.changeToDone(this.taskId);
        this.isDone = !this.isDone;
      }
    },
    changeToDone(taskId) {
      axios
        .put("/api/tasks/" + taskId + "/done")
        .then((response) => {
          console.log("success");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    changeToUndone(taskId) {
      axios
        .put("/api/tasks/" + taskId + "/undone")
        .then((response) => {
          console.log("success");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    changeDueDate(due_date) {
      this.due_date = due_date;
      axios
        .put("/api/tasks/" + this.taskId + "/edit", {
          due_date: this.due_date,
        })
        .then((response) => {
          console.log("success", this.due_date, this.taskId);
          // this.due_date = due_date;
        })
        .catch((error) => {
          console.log(error);
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
            console.log(taskId, " is deleted");
            this.isDeleted = true;
          })
          .catch((error) => {
            console.log(error);
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
          console.log(this.priority_level, "success");
          scale = 0; //scaleを0に戻す
        })
        .catch((error) => {
          console.log(error);
        });
    },
    toggleMust: function () {
      this.isMust = !this.isMust;
    },
    toggleSmall: function () {
      this.isSmall = !this.isSmall;
    },
  },
};
</script>
