<template>
  <div class="betbox">
    <div class="bet-slip" @click="avoidClose">
      <div class="betMain">
        <div class="inputBox">
          <div class="Cbox">
            {{ $t('S_MAX_ERROR') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  data() {
    return {}
  },
  computed: {},
  mounted() {},
  methods: {
    avoidClose() {
      this.$store.state.ball.showAvoid = false
    }
  }
}

</script>
<style lang="scss" scoped>

.betbox {
    top: 0;
    z-index: 99;
    position: fixed;
    width: 100%;
    height: 100%;
    background-color: rgba(51, 51, 51, 0.452);
    overflow: scroll;
    padding: 10px;
}
.bet-slip {
    margin: 10px auto;
    z-index: 100;
    position: relative;
    width: 97%;
    height: auto;
    padding: 10rem 0;
}
.betMain {
    padding: 0;
    text-align: center;
    color: #fff;
    background: #000;
    border-radius: 10px;
//   min-height: 10rem;
}
.box {
    padding: 4px;
}
.Cbox {
    padding: 10px;
}
</style>
