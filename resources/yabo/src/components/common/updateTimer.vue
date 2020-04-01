<template>
  <div class="updateTime">{{update}}</div>
</template>
<script>
export default {
  data() {
    return {
      update: 0,
      timer: 0,
      isStop: false
    };
  },
  props: {
    updateTime: {
      type: Number,
      default: 60
    }
  },
  methods: {
    $_getUpdateTime() {
      this.update -= 1;
      // this.$emit("countDown", this.update);
      this.$nextTick(() => {
        if (this.update === 0) {
          this.$emit("updateFunc");
          this.update = this.updateTime;
        }
      });
    },
    $_clock() {
      if (!this.isStop) {
        this.$_getUpdateTime();
      }
    },
    stop() {
      this.isStop = true;
    },
    start() {
      this.isStop = false;
    },
    reCounter() {
      this.update = this.updateTime;
    }
  },
  watch: {
    isStop(val, oldVal) {
      if (!val && oldVal) {
        clearInterval(this.timer);
        this.timer = setInterval(this.$_clock, 1000);
      }
    }
  },
  created() {
    this.update = this.updateTime;
  },
  mounted() {
    this.timer = setInterval(this.$_clock, 1000);
  },
  beforeDestroy() {
    clearInterval(this.timer);
  }
};
</script>
<style lang="scss">
.updateTime {
  margin-left: 0 !important;
}
</style>