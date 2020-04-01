<template>
  <div :class="classObj" class="app-wrapper">
    <div class="main-container">
      <app-main />
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { AppMain } from './components'
// import { AppMain } from './components/AppMain'
import ResizeMixin from './mixin/ResizeHandler'
import {
  getAllEventCount
} from '@/api/bblivem'

export default {
  name: 'Layout',
  components: {
    AppMain
  },
  mixins: [ResizeMixin],
  data() {
    return {
      isOpenBetSlip: false,
      timeout: '',
      initTimer: false
    }
  },
  computed: {
    ...mapGetters([
      'showBetRecord',
      'getBetSlip',
      'showAvoid',
      'curGameCount',
      'getAllEventCount'
    ]),
    navPath() {
      return this.$route.path
    },
    device() {
      return this.$store.state.app.device
    },
    fixedHeader() {
      return this.$store.state.settings.fixedHeader
    },
    classObj() {
      return {
        withoutAnimation: false,
        mobile: this.device === 'mobile'
      }
    }
  },
  created() {
    if (!this.$cookie.get('odds_type')) {
      this.$cookie.set('odds_type', 2)
    }
    if (!this.$cookie.get('filterMatch')) {
      this.$cookie.set('filterMatch', 1)
    }
  },
  mounted() {
    this.getAllSportCount()
  },
  methods: {
    ...mapActions({
      actionSetAllEventCount: 'm6app/actionSetAllEventCount',
      actionSetNavHeader: 'm6app/actionSetNavHeader'
    }),
    NavRouter(page) {
      this.$router.push(page)
      this.ItemClass = page
      this.actionSetNavHeader(page)
    },
    navRouterReplace(val) {
      this.$router.replace(val)
    },
    closeBetRecord() {
      this.$store.dispatch('ball/toggleBetRecord', false)
    },
    closeBetSlip(val) {
      this.isOpenBetSlip = val
    },
    path(val) {
      if (val.indexOf('moreSport') > -1) {
        return true
      } else {
        return false
      }
    },
    back() {
      this.$router.go(-1) // 返回上一层
    },
    getAllSportCount() {
      getAllEventCount().then(res => {
        // console.log(res, 'in index')
        this.actionSetAllEventCount(res)
        if (!this.initTimer) {
          this.timeout = setInterval(() => {
            this.getAllSportCount()
            this.initTimer = true
          }, 30000)
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~@/styles/mixin.scss";
@import "~@/styles/variables.scss";
.main-container {
  padding-bottom: 0px !important;
  margin-left:  0px !important;
}
.header {
  width: 100%;
  background-color: #000;
  height: 35px;
}
.app-wrapper {
  @include clearfix;
  position: relative;
  height: 100%;
  width: 100%;

  .morenavbar-area {
    list-style: none;
    margin: 0;
    padding: 0;
    background-color: #000;
    color: #fff;
    // position: fixed;
    // top: 0;
    // width: 100vw;
    // z-index: 1001;
    li {
      display: inline-block;
      font-size: 14px;
      padding: 10px 5px;
    }
    .NAVactive {
      font-size: 16px;
      font-weight: bold;
    }
  }
  .navbar-area {
    list-style: none;
    color: grey;
    // margin: 0;
    padding: 0;
    width: 100%;
    font-size: 14px;
    margin: 0 auto;
    margin-top: 10px;
    text-align: center;
    min-width: 320px;
    li {
      display: inline-block;
      padding: 5px 7%;
      background-color: #fff;
      color: gray;
      font-weight: bold;
      border-radius: 5px 5px 0px 0px;
    }
    .NAVactive {
      background-color: #000;
      color: #d89e48;
    }
  }
}

.fixed-header {
  position: fixed;
  top: 0;
  right: 0;
  z-index: 9;
  width: calc(100% - #{$sideBarWidth});
  transition: width 0.28s;
}

.hideSidebar .fixed-header {
  width: calc(100% - 54px);
}

.mobile .fixed-header {
  width: 100%;
}
.grey-modal {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  background-color: #000;
  opacity: 0.77;
  z-index: 1;
}

.grey-modal {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  background-color: #000;
  opacity: 0.77;
}

.fade-enter-active,
.fade-leave-active {
  transition: all 0.5s ease-in-out 0s;
}

.fade-enter {
  transform: translate(-100%, 0);
  -webkit-transform: translate(-100%, 0);
}
.fade-enter-to {
  transform: translate(0, 0);
  -webkit-transform: translate(0, 0);
}

.fade-leave-to {
  transform: translate(-100%, 0);
  -webkit-transform: translate(-100%, 0);
}
</style>
