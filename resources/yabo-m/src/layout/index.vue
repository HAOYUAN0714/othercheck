<template>
  <div :class="classObj" class="app-wrapper">
    <div v-if="device==='mobile'&&sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
    <sidebar class="sidebar-container" />
    <div class="main-container">
      <div :class="{'fixed-header':fixedHeader}">
        <navbar />
      </div>
      <app-main />
    </div>
    <div v-show="showBetRecord" class="grey-modal" @click="closeBetRecord" />
    <avoid v-show="showAvoid" />
    <!--bet slip-->
    <div
      v-if="getBetSlip.length > 0"
      class="bet-slip-btn"
      @click="isOpenBetSlip = true"
    >
      <span class="bet-slip">{{ $t('S_BET_SLIP') }} -</span>
      <span class="selection"> {{ $t('S_SELECTION') }}</span>
      <span class="count">{{ getBetSlip.length }}</span>
    </div>
    <betSlip v-if="isOpenBetSlip === true" :open-bet-slip="isOpenBetSlip" :passbetting="betting" @getbetting="betting = $event" @closeBetSlip="closeBetSlip" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { Navbar, Sidebar, AppMain } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import betSlip from './components/betSlip'
import avoid from './components/Avoid'
import { getHomeInfo } from '@/utils/getBallData'

export default {
  name: 'Layout',
  components: {
    Navbar,
    Sidebar,
    AppMain,
    betSlip,
    avoid
  },
  mixins: [ResizeMixin],
  data() {
    return {
      isOpenBetSlip: false,
      betting: false
    }
  },
  computed: {
    ...mapGetters([
      'showBetRecord',
      'getBetSlip',
      'showAvoid'
    ]),
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
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: true
      }
    }
  },
  mounted() {
    this.getGameCount()
  },
  created() {
    if (!this.$cookie.get('odds_type')) {
      this.$cookie.set('odds_type', 2)
    }
  },
  methods: {
    ...mapActions({
      actionGetGameCount: 'ball/actionGetGameCount'
    }),
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    },
    closeBetRecord() {
      this.$store.dispatch('ball/toggleBetRecord', false)
    },
    closeBetSlip(val) {
      this.isOpenBetSlip = val
    },
    getGameCount() {
      getHomeInfo().then((res) => {
        this.actionGetGameCount(res)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "~@/styles/mixin.scss";
  @import "~@/styles/variables.scss";

  .app-wrapper {
    @include clearfix;
    position: relative;
    height: 100%;
    width: 100%;
    &.mobile.openSidebar{
      position: fixed;
      top: 0;
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
  width: calc(100% - 54px)
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
    opacity: .77;
}

.bet-slip-btn {
  background-color: var(--betslip_outer_bg);
  position: fixed;
  width: 100%;
  line-height: 35px;
  padding: 8px 8px 7px;
  bottom: 0;
  opacity: .95;
  text-align: center;
  color: var(--betslip_outer_text);
  font-size: 14px;
  .count {
    background-color:  var(--betslip_outer_text);
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    border-radius: 50%;
    font-size: 13px;
    font-weight: bold;
    color: var(--betslip_outer_bg);
  }
}
</style>
