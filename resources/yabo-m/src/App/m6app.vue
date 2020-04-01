<template>
  <div
    id="app"
    :class="{
      open: isFuncBetOpen === true,
    }"
  >
    <router-view />
  </div>
</template>

<script>
// import { getExtendSession } from '@/utils/getBallData'
import { mapGetters } from 'vuex'

export default {
  name: 'App',
  data() {
    return {
      timeout: ''
    }
  },
  computed: {
    ...mapGetters({
      isFuncBetOpen: 'm6app/getFuncBetOpen'
    })
  }
  // created() {
  //   this.timeout = setInterval(() => {
  //     getExtendSession()
  //   }, 180000)
  // },
  // beforeDestroy() {
  //   clearInterval(this.timeout)
  // }
}
</script>

<style lang="scss">
// #app {
//   position: fixed;
//   width: 100%;
// }
.open {
  position: fixed;
  width: 100%;
}
.loading {
  opacity: 0;
  transition: all .5s;
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  z-index: -1;
  background: rgba(0, 0, 0, .6) url('~@/assets/betball/loading.gif') no-repeat center;

  &.open {
    opacity: 1;
    z-index: 4; // 目前最高
  }
}

html, body, p, h1, h2, h3, h4, h5, h6 {
    padding: 0;
    margin: 0;
}

div {
  box-sizing: border-box;
}

/* clear float */
.clear {
    margin: 0;
    padding: 0;
    clear: both;
}

.clearfix {
  zoom: 1;

  &:before {
      display: table;
      content: "";
  }

  &:after {
      display: table;
      content: "";
      clear: both;
  }
}
</style>

