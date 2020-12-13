<template>
  <div id="timer">
    <div class="timer">
      <div class="time">
        {{ formatTime }}
      </div>
      <button v-on:click="start" v-if="!timerOn">Start</button>
      <button v-on:click="stop" v-if="timerOn">Stop</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "CountdownTimer",
  data() {
    return {
      min: 59,
      sec: 59,
      timerOn: false, //Start,Stopの表示切り替え
      timerObj: null, //setTimeoutを格納
    };
  },
  methods: {
    // count関数 = setIntervalにより毎秒呼ばれる関数
    // ①0秒以下 && 1分を切っていない → 分数を-1、秒を59にリセット
    // ②0秒以下 && 0分台 → 終了
    // ③それ以外 → 秒数を-1
    count: function () {
      if (this.sec <= 0 && this.min >= 1) {
        this.min--;
        this.sec = 59;
      } else if (this.sec <= 0 && this.min <= 0) {
        this.complete();
      } else {
        this.sec--;
      }
    },

    start: function () {
      let self = this;
      this.timerObj = setInterval(function () {
        self.count();
      }, 1000);
      this.timerOn = true; //timerがOFFであることを状態として保持
    },

    stop: function () {
      clearInterval(this.timerObj);
      this.timerOn = false; //timerがOFFであることを状態として保持
    },

    complete: function () {
      clearInterval(this.timerObj);
    },
  },
  computed: {
    formatTime: function () {
      let timeStrings = [this.min.toString(), this.sec.toString()].map(
        function (str) {
          if (str.length < 2) {
            return "0" + str; //分数・秒数が一桁台であれば 01
          } else {
            return str;
          }
        }
      );
      return timeStrings[0] + ":" + timeStrings[1];
    },
  },
};
</script>

<style scoped>
#timer {
  display: flex;
  align-items: center;
  justify-content: center;
}
.time {
  font-size: 100px;
}
</style>
