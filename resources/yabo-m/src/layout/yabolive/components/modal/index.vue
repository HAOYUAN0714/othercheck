<template>
  <div class="container">
    <div class="allLeave" @click="allLeave" />
    <div class="indexTop">
      <div class="betContain">
        <h1>恭喜您</h1>
        <p class="text">您已抽中 <strong>五等奖规则4水电费水电费水电费水电费水电费水电费水电费水电费水电费水电费水电费水电费水电费4水电费水4水电费水4水电费水4水电费水</strong></p>
        <div class="btn">
          <div @click="allLeave">重新抽奖</div>
          <div @click="allLeave">领取奖励</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  props: {
    openBetSlip: {
      type: Boolean,
      default: () => (false)
    }
  },
  data() {
    return {
    }
  },
  computed: {
  },
  watch: {

  },
  created() {
  },
  mounted() {
  },
  methods: {
    allLeave() {
      this.$emit('closeModal', false)
    }
  }
}
</script>
<style lang="scss" scoped>
.container{
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  z-index: 1;
  .allLeave{
    position: absolute;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(48, 47, 47, 0.7);
  }

  .indexTop {
    position: fixed;
    top: 55%;
    right: 0;
    // bottom: 0;
    width: 100vw;
    height: 50vh;
    // background-color: rgba(19, 18, 18, 0.6);
    @media (max-width: 320px) {
      top: 13%;
    }
    .clearfix {
      clear: both;
    }

    .betContain{
        text-align: center;
        background: #f5f9fb;
        width: 55%;
        margin: 0 auto;
        border-radius: 10px;
        padding: 10px 10px 0px 10px;
        .text {
          width: 100%;
          word-break: break-all;
          margin: 20px 0px;
          height: 80px;
          overflow-y: scroll;
        }
        .btn {
          border-top: 1px solid #c0c4cc;;
          div {
            display: inline-block;
            // border: 1px solid gray;
            padding: 10px;
            // width: 50%;
            &:first-child {
              border-right: 1px solid #c0c4cc;;
            }
          }
        }
    }
  }

}

</style>
