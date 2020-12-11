<template>
  <transition name="slide-item" tag="div">
    <div class="p-tasklist__item" v-show="!isDeleted">
      <div
        class="p-task__wrapper d-flex flex-column flex-md-row justify-content-between p-2 mt-3 mx-0 bg-white"
      >
        <div class="p-task__taskname col-md-9 mx-0 px-0">
          <div class="p-task__main p-0 d-flex">
            <!-- チェックボックス -->
            <div
              class="p-task__checkbox pr-2"
              :class="{ isDone: isDone }"
              @click="toggleDone"
            >
              <i :class="classCheckBox" aria-hiden="true"></i>
            </div>
            <div class="p-task__contents w-100">
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
                  v-show="isActiveText"
                  aria-hidden="true"
                ></i>
              </div>
              <!-- 編集ボックス -->
              <div
                v-show="showEditBox"
                class="p-task__editArea col-12 mx-0 px-0"
              >
                <textarea
                  type="text"
                  class="p-task__editBox"
                  :value="taskName_data"
                  @keyup.shift.enter="checkKeyUp($event)"
                >
                </textarea>
                <p class="text-muted small">Shift+Enterで変更</p>
              </div>
              <!-- タスク詳細 -->
              <div class="p-task__details d-flex py-1">
                <!-- 優先度 -->
                <div class="p-task__priority ml-1 mr-3">
                  <small
                    class="m-0 p-0"
                    v-show="showMenu || priority_level >= 1"
                  >
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
                <div
                  class="p-task__due-date"
                  @click="showDatePicker = !showDatePicker"
                >
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
                  <!-- <i
              v-show="showMenu"
              @click="deleteTask(taskId)"
              class="p-task__icon fas fa-trash-alt"
              aria-hidden="true"
            ></i> -->
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
          </div>
        </div>
        <slot name="task-action"></slot>
      </div>
      <slot name="prep-review"></slot>
    </div>
  </transition>
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
      dueDate_data: this.dueDate,
      taskName_data: this.taskName,
      isMust: false,
      isSmall: false,
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
    changeDueDate(inputDueDate) {
      axios
        .put("/api/tasks/" + this.taskId + "/changeDueDate", {
          due_date: inputDueDate,
        })
        .then((response) => {
          this.dueDate_data = inputDueDate;
          console.log("success", this.dueDate_data, this.taskId);
          this.showDatePicker = !this.showDatePicker;
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
