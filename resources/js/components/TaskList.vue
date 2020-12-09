<template>
  <div>
    <div
      :class="classTaskItem"
      @mouseover="isShowIcon = true"
      @mouseleave="isShowIcon = false"
    >
<slot name="task-body"></slot>


      <span>
        <i
          v-show="isShowIcon || isMust"
          :class="classMustIcon"
          class="todoList__icon"
          @click="toggleMust"
          aria-hidden="true"
        ></i>
        <i
          v-show="isShowIcon || isSmall"
          :class="classSmallIcon"
          class="todoList__icon"
          @click="toggleSmall"
          aria-hidden="true"
        ></i>
        <i
          v-show="isShowIcon || isRemoved"
          :class="classTrashIcon"
          class="todoList__icon"
          @click="toggleRemove"
          aria-hidden="true"
        ></i>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  // props: ["taskId", "taskStatus", "taskName", "priority", "dueDate"],
  props: {
    taskId: { type: String },
    taskStatus: { type: String },
    taskName: { type: String },
  },
  data() {
    return {
      isDone: this.taskStatus == 4 ? true : false,

      isShowIcon: false,


      isMust: false,
      isSmall: false,
      isRemoved: false,
    };
  },
  computed: {
    classCheckBox: function () {
      return {
        "far fa-check-circle": this.isDone,
        "far fa-circle": !this.isDone,
      };
    },
    classTaskItem: function () {
      return {
        todoList__item: true,
        "todoList__item--done": this.isDone,
        "todoList__item--must": this.isMust,
        "todoList__item--small": this.isSmall,
      };
    },
    classMustIcon: function () {
      return {
        "fas true": this.isMust,
        "far false": !this.isMust,
        "fa-star": true,
        "icon-star": true,
      };
    },
    classSmallIcon: function () {
      return {
        fas: true,
        "fa-stopwatch": true,
        "icon-stopwatch": true,
        true: this.isSmall,
        false: !this.isSmall,
      };
    },
    classTrashIcon: function () {
      return {
        "fas fa-trash-alt icon-trash": true,
        true: this.isRemoved,
        false: !this.isRemoved,
      };
    },
  },
  methods: {

    toggleRemove: function () {
      this.isRemoved = !this.isRemoved;
    },

    toggleMust: function () {
      this.isMust = !this.isMust
    },
    toggleSmall: function () {
      this.isSmall = !this.isSmall
    },

  },
};
</script>
