<template>
  <div :class="classObj" class="app-wrapper">
    <div v-if="device==='mobile'&&sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
    <div class="main-container">
      <transition name="fade">
        <sidebar v-show="getSidebarStatus" />
      </transition>
      <div v-show="getSidebarStatus" class="grey-modal" @click="closeSidebar" />
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
  getAllEventCount,
  getChatRoomUrl,
  getChatRoomToken,
  getFavoriteMatch
} from '@/api/m6'
import sidebar from './components/sidebar'
import ChatroomJS from '@/utils/chatroom'

export default {
  name: 'Layout',
  components: {
    AppMain,
    sidebar
  },
  mixins: [ResizeMixin],
  data() {
    return {
      isOpenBetSlip: false,
      timeout: '',
      initTimer: false,
      isOpenSidebar: false
    }
  },
  computed: {
    ...mapGetters([
      'showBetRecord',
      'getBetSlip',
      'showAvoid',
      'curGameCount',
      'getAllEventCount',
      'getSidebarStatus'
    ]),
    navPath() {
      return this.$route.path
    },
    sidebar() {
      return this.$store.state.app.sidebar
    },
    // showAvoid() {
    //   return this.$store.state.ball.showAvoid
    // },
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
    this.getFavoriteEvent()
    this.init()
  },
  methods: {
    ...mapActions({
      actionSetAllEventCount: 'm6app/actionSetAllEventCount',
      actionSetNavHeader: 'm6app/actionSetNavHeader',
      actionGetFavoriteMatch: 'm6app/actionGetFavoriteMatch',
      actonSetSidebar: 'm6app/actonSetSidebar'
    }),
    NavRouter(page) {
      this.$router.push(page)
      this.ItemClass = page
      this.actionSetNavHeader(page)
    },
    navRouterReplace(val) {
      this.$router.replace(val)
    },
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
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
        this.actionSetAllEventCount(res.EventCount)
        if (!this.initTimer) {
          this.timeout = setInterval(() => {
            this.getAllSportCount()
            this.initTimer = true
          }, 30000)
        }
      })
    },
    init() {
      // getChatRoomUrl().then(res => {
      //   getChatRoomToken().then(res => {
      //     this.$store.dispatch(
      //       "m6app/actionsetSocketInstance",
      //       new ChatroomJS()
      //     );
      //   });
      // });
    },
    closeSidebar() {
      this.actonSetSidebar(false)
    },
    getFavoriteEvent() {
      getFavoriteMatch().then(res => {
        const eventId = []
        res.data.Sports.forEach(item => {
          Object.values(item.Events).forEach(matchInfo => {
            eventId.push(matchInfo.EventId)
          })
        })
        this.actionGetFavoriteMatch(eventId)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~@/styles/mixin.scss";
@import "~@/styles/variables.scss";
.header {
  width: 100%;
  background-color: #000;
  height: 35px;
  // position: fixed;
  // top: 0;
  .btn-sidebar {
    position: absolute;
    top: 5px;
    left: 0px;
    background: url("~@/assets/img/btn_menu.png") 50% 0 no-repeat;
    width: 36px;
    height: 36px;
  }
  .wager {
    position: absolute;
    top: 5px;
    right: 0px;
    background: url("~@/assets/m6app/ic_withdraw_balance.png") 50% 0 no-repeat;
    width: 36px;
    height: 36px;
  }
}
.app-wrapper {
  @include clearfix;
  position: relative;
  height: 100%;
  width: 100%;
  &.mobile.openSidebar {
    position: fixed;
    top: 0;
  }
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
.drawer-bg {
  background: #000;
  opacity: 0.3;
  width: 100%;
  top: 0;
  height: 100%;
  position: absolute;
  z-index: 999;
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

//   @import "~@/styles/mixin.scss";
//   @import "~@/styles/variables.scss";

//   .app-wrapper {
//     @include clearfix;
//     position: relative;
//     height: 100%;
//     width: 100%;
//     &.mobile.openSidebar{
//       position: fixed;
//       top: 0;
//     }
//   }
//   .drawer-bg {
//     background: #000;
//     opacity: 0.3;
//     width: 100%;
//     top: 0;
//     height: 100%;
//     position: absolute;
//     z-index: 999;
//   }

//   .fixed-header {
//     position: fixed;
//     top: 0;
//     right: 0;
//     z-index: 9;
//     width: calc(100% - #{$sideBarWidth});
//     transition: width 0.28s;
//   }

//   .hideSidebar .fixed-header {
//     width: calc(100% - 54px)
//   }

//   .mobile .fixed-header {
//     width: 100%;
//   }
//   .grey-modal {
//       position: fixed;
//       top: 0;
//       right: 0;
//       left: 0;
//       bottom: 0;
//       background: #000;
//       width: calc(100% - 242px);
//       opacity: .77;
//   }
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
