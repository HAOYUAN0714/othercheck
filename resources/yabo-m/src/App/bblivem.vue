<template>
  <div
    id="app"
    :class="{
      open: isFuncBetOpen === true,
    }"
  >
    <popUp />
    <router-view />
  </div>
</template>

<script>
// import { getExtendSession } from '@/utils/getBallData'
import { mapGetters } from 'vuex'
import popUp from '@/layout/bblivem/components/popUp'

export default {
  name: 'App',
  components: {
    popUp
  },
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
#app {
  position: relative;
}
.open {
  position: fixed;
  width: 100%;
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

