<template>
  <div class="betbox">
    <div class="bet-slip">
      <div class="header">
        <div class="header-left">
          <div class="box">
            <p style="color:#fff;font-weight: 500;">{{ $t('S_INFO') }}</p>
          </div>
          <div class="header-right" @click="avoidClose">
            <font-awesome-icon style="color:#fff" :icon="['fas','times']" />
          </div>
        </div>
      </div>
      <div class="betMain">
        <div class="inputBox">
          <div class="Cbox">
            {{ $t('S_MAX_ERROR') }}
            <div class="ok" @click="avoidClose">ok</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  data() {
    return {

    }
  },
  computed: {

  },
  mounted() {

  },
  methods: {
    avoidClose() {
      this.$store.state.ball.showAvoid = false
    }
  }
}

</script>
<style lang="scss" scoped>
.ok {
  font-size: 1.5rem;
  position: relative;
  width: 10rem;
  text-align: center;
  background-color: #b98d50;
  color: #fff;
  border: 1px solid #333;
  border-radius: 5px;
  line-height: 50px;
  margin: 67px auto 0 auto;
}
.header {
  display: flex;
  // background: linear-gradient(to right, #955d2c, #f3d382 70%, #f3d382 80%, #955d2c 100%);
  background: #B98D50;
  &-left {
    width: 100%;
    display: flex;
    padding: 10px;

    & .header-right {
      font-size: 25px;
      right: 14px;
      position: absolute;
    }
  }
}
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
  background: #fff;
  min-height: 10rem;
}
.box {
  padding: 4px;
}
.Cbox {
  padding: 10px;
}
</style>
