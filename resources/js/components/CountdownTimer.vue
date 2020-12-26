<template>
  <div class="p-timer w-60">
    <div class="p-timer__form mb-4">
      <p class="p-timer__guide alert alert-primary d-inline" v-show="!isFinished">
        {{ unitTime }}分間、集中して取り組みましょう！
      </p>
      <div class="p-timer__time my-2">{{ formatTime }}</div>
      <button v-on:click="doStart" v-if="!isStarted" class="btn btn-primary">
        <i class="fas fa-caret-right mr-2"></i>Start
      </button>
      <button v-on:click="doStop" v-if="isStarted" class="btn btn-danger">
        <i class="fas fa-stop mr-2"></i>Stop
      </button>
    </div>
    <p class="p-timer__guide alert alert-primary" v-show="isFinished">
      お疲れさまでした！<br />
      {{ unitTime }}分が経過しました。 振り返りに進みましょう！
    </p>
  </div>
</template>

<script>
import Push from "push.js";
export default {
  name: "CountdownTimer",
  props: {
    unitTime: { String },
  },
  data() {
    return {
      isStarted: false,
      isFinished: false,
      timerObj: null,
      min: 30,
      sec: 0,
    };
  },
  created() {
    this.min = this.unitTime;
    Push.Permission.request();
  },
  computed: {
    formatTime() {
      // 数字が1桁の場合は0を付与して2桁にする
      // return (num < 10 ? "0" : "") + num;
      let timeStrings = [this.min.toString(), this.sec.toString()].map(
        function (str) {
          if (str.length < 2) {
            return "0" + str;
          } else {
            return str;
          }
        }
      );
      return timeStrings[0] + ":" + timeStrings[1];
    },
  },
  methods: {
    doStart() {
      let self = this;
      this.timerObj = setInterval(function () {
        self.countDown();
      }, 1000); // 1秒ごとのsetIntervalをObjに格納
      this.isStarted = true;
    },
    doStop() {
      this.isStarted = false;
      clearInterval(this.timerObj);
    },
    countDown() {
      if (this.sec <= 0 && this.min >= 1) {
        this.min--;
        this.sec = 59; // 1分以上で秒が0の場合は59に
      } else if (this.sec <= 0 && this.min <= 0) {
        this.finished(); // どちらも0の場合は終了
      } else {
        this.sec--; // それ以外は秒を-1
      }
    },
    countUp() {
      if (this.sec >= 59) {
        this.min++;
        this.sec = 0; // 59秒以上の場合は分に繰り上げ
      } else {
        this.sec++; // それ以外は秒を+1(60分以上もそのまま分としてカウント)
      }
    },
    finished() {
      let self = this;
      // 終了フラグをtrueに
      this.isFinished = true;
      // clearIntervalでカウントダウンの処理を解除
      clearInterval(this.timerObj);
      // プッシュ通知を表示
      this.createPush();

      // カウントアップの処理を開始
      this.min = this.unitTime;
      this.sec = 0;
      this.timerObj = setInterval(function () {
        self.countUp();
      }, 1000);
      this.isStarted = true;
    },
    createPush() {
      Push.create("タイマー終了", {
        body: this.unitTime + "分が経過しました",
        icon: "/images/icons-medal-50px.png",
        // timeout: 10000,
        requireInteraction: true, // 通知を表示し続ける
        onClick: function () {
          // 通知クリックで画面表示、通知を閉じる
          window.focus();
          this.close();
        },
      });
    },
  },
};
</script>
