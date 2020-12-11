<template>
  <transition name="slide-item" tag="div">
    <div>
      <div class="text-right">
        <small>
          <a class="btn btn-light px-1 py-0 mb-2" @click="showPrep = !showPrep">
            <span v-show="!showPrep">
              <i class="fas fa-caret-down"></i>
              Prepを表示
            </span>
            <span v-show="showPrep">
              <i class="fas fa-caret-up"></i>
              Prepを非表示
            </span>
            <i class="far fa-clipboard" aria-hidden="true"></i>
          </a>
        </small>
      </div>
      <div v-show="showPrep">
        <slot name="prep"></slot>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      showPrep: false,
    };
  },
};
</script>
