<template>
  <transition name="slide-item" tag="div">
    <div class="p-tasklist__item mb-3" v-show="!isDeleted">
      <div
        class="p-task__wrapper d-flex flex-column flex-lg-row justify-content-between p-2 mx-0 bg-white"
      >
        <div class="p-task__main flex-grow-1 p-0 d-flex mx-0 px-0">
          <!-- チェックボックス -->
          <div
            class="p-task__checkbox px-2"
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
            <div v-show="showEditBox" class="p-task__editArea col-12 mx-0 px-0">
              <textarea
                type="text"
                class="p-task__editBox w-100"
                :value="taskName_data"
                @keydown.shift.enter.prevent="editTaskName($event)"
                @blur="showEditBox = false"
              >
              </textarea>
              <p class="text-muted small">Shift+Enterで変更</p>
            </div>
          </div>
        </div>
        <div class="p-task__details d-flex justify-content-end ml-2">
          <!-- メニューアイコン -->
          <task-menu
            @task-deleted="deleteTask()"
            :task-id="taskId"
            :priority="priority"
            :due-date="dueDate"
          >
          </task-menu>
          <slot name="task-action"></slot>
        </div>
      </div>
      <slot name="prep-review"></slot>
    </div>
  </transition>
</template>

<script>
import TaskMenu from "../components/TaskMenu.vue";
export default {
  name: "TaskList",
  components: {
    TaskMenu,
  },
  // props: ["taskId", "taskStatus", "taskName"],
  props: {
    taskId: { type: String },
    taskStatus: { type: String },
    taskName: { type: String },
    priority: { type: String },
    dueDate: { type: String },
  },
  data() {
    return {
      isDone: this.taskStatus == 4 ? true : false,
      showEditBox: false,
      keyDownCode: "",
      isActiveText: false,
      isDeleted: false,
      taskName_data: this.taskName,
    };
  },
  computed: {
    classCheckBox() {
      return {
        "far fa-check-circle": this.isDone,
        "far fa-circle": !this.isDone,
      };
    },
  },

  methods: {
    deleteTask() {
      this.isDeleted = true;
    },
    checkKeyUp(e) {
      // Shift+Enterキーが押された時
      let text = e.currentTarget.value;
      if (text) {
        this.editTaskName(text);
      }
      this.showEditBox = false;
    },
    editTaskName(e) {
      let text = e.currentTarget.value;
      if (text) {
        this.taskName_data = text;
        axios
          .put("/api/tasks/" + this.taskId + "/edit", {
            task_name: text,
          })
          .then((response) => {
            this.taskName_data = text;
            this.showEditBox = false;
            console.log("success", this.taskName_data);
          })
          .catch((error) => {
            console.log(error);
          });
      }
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
  },
};
</script>
