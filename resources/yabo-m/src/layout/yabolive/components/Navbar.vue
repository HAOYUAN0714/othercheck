<template>
  <div class="navbar">
    <div class="navbar-head">
      <div class="logo" />
      <div class="rightNav">
        <span class="nav-mark" @click="showSetting">
          <img src="@/assets/img/btn_menu2.png">
        </span>
      </div>
    </div>
    <div class="navbar-Second">
      <hamburger
        class="hamburger-container"
      />

      <div>
        <span class="home-mark" @click="NavRouter('/focus')">
          <img src="@/assets/img/tool_home.png">
        </span>
        <ul class="navbar-area">
          <li
            v-if="Object.keys(curGameCount.live || {}).length > 1"
            class="navbar-item live"
            :class="{'NAVactive': $route.name === 'live'}"
            @click="NavRouter(`/live/${Object.keys(curGameCount.live || {})[0]}`)"
          >
            {{ $t('S_RUN_BALL') }}
          </li>
          <li class="navbar-item" :class="{'NAVactive': navPath === '/coming'}" @click="NavRouter('/coming')">{{ $t('S_COMMING_MATCH') }}</li>
          <li class="navbar-item" :class="{'NAVactive': navPath === '/pass'}" @click="NavRouter('/pass')">{{ $t('S_MULTIPLES') }}</li>
          <li class="navbar-item" @click="openBetRecord">{{ $t('S_BET_RECORD') }}</li>
        </ul>
      </div>
    </div>
    <!--設定-->
    <transition name="fade">
      <Setting v-if="isShowSetting" @closeModal="closeModal" />
    </transition>
    <!--投注記錄-->
    <transition name="fade">
      <BetHistory v-if="showBetRecord" />
    </transition>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Hamburger from '@/components/Hamburger'
import Setting from './setting'
import BetHistory from './BetHistory'

export default {
  components: {
    Hamburger,
    Setting,
    BetHistory
  },
  data() {
    return {
      isShowSetting: false,
      ItemClass: '',
      sportDataCount: {}
    }
  },
  computed: {
    ...mapGetters([
      'avatar',
      'showBetRecord',
      'curGameCount'
    ]),
    navPath() {
      return this.$route.path
    },
    status() {
      return process.env.VUE_APP_PROJECT_FLAG + '_' + process.env.NODE_ENV
    }
  },
  methods: {
    testEnv() {
      console.log(this.status)
    },
    NavRouter(page) {
      this.$router.push(page)
      this.ItemClass = page
    },

    async logout() {
      await this.$store.dispatch('user/logout')
      this.$router.push(`/login?redirect=${this.$route.fullPath}`)
    },
    showSetting() {
      this.isShowSetting = true
    },
    closeModal(val) {
      this.isShowSetting = val
    },
    openBetRecord() {
      this.$store.dispatch('ball/toggleBetRecord', true)
    }
  }
}
</script>

<style lang="scss" scoped>

.navbar {
  width: 100%;
  height: 50px;
  overflow: hidden;
  position: relative;
  display: table;
  border-bottom: 1px solid #222;
  & .logo {
    .pic-logo {
      margin-left: 15px;
      float:left;
    }
    height: 50px;
    width: 100%;
    text-align: center;
  }
  & .logo2 {
    display: inline-block;
    color: blue;
    border: blue 1px solid;
    margin: 5px 0;
    width: 55px;
    padding: 7px;
    text-align: center;
  }

  &-Second {
    position: relative;
    // display: inline-flex;
    width: 100%;
    background-color: var(--nav_bg);
  }

  &-head {
    position: relative;
    // display: inline-flex;
    width: 100%;
    line-height: 40px;
    background-color: var(--top_bg);
    & .rightNav {
      top: 0;
      right: 10px;
      position: absolute;
      // border: 1px solid rgb(1, 77, 128);
      border-radius: 5px;
      float: right;
      padding: 9px 7px;
      width: 40px;
      text-align: center;
      color: #fff;
    }
  }
  &-area {
    -webkit-padding-start: 0px;
    padding-inline-start: 0px;
    // line-height: 12px;
    display: inline-flex;
    padding: 0 10px;
    max-width: 70vw;
    white-space: nowrap;
    overflow-x: auto;
    font-size: 14px;
  }

  & ul {
    padding-inline-start: 0px;
  }
  &-item {
    // flex: 1;
    margin: 0 5px;
    -webkit-padding-start: 0px;
    list-style: none;
    color: #fff;
    width: 62px;
    padding: 0 1px;
    text-align: center;
    align-self: center;
    display: table;

    & .icon {
      font-size: 2em;
    }
    & li {
      margin: 0 7px;
      text-align: center;
    }
  }
    & .live {
    width: 52px;
    display: table;
    }
    &-ICON {
    width: 41px;
    font-size: .6em;
    display: table;
    }
  .hamburger-container {
    color: #fff;
    line-height: 43px;
    height: 100%;
    float: left;
    cursor: pointer;
    transition: 0.3s;
    -webkit-tap-highlight-color: transparent;

    &:hover {
      background: rgba(0, 0, 0, 0.025);
    }
  }

  .breadcrumb-container {
    float: left;
  }

  .right-menu {
    float: right;
    height: 100%;
    line-height: 50px;

    &:focus {
      outline: none;
    }

    .right-menu-item {
      display: inline-block;
      padding: 0 8px;
      height: 100%;
      font-size: 18px;
      color: #5a5e66;
      vertical-align: text-bottom;

      &.hover-effect {
        cursor: pointer;
        transition: 0.3s;

        &:hover {
          background: rgba(0, 0, 0, 0.025);
        }
      }
    }

    .avatar-container {
      margin-right: 30px;

      .avatar-wrapper {
        margin-top: 5px;
        position: relative;

        .user-avatar {
          cursor: pointer;
          width: 40px;
          height: 40px;
          border-radius: 10px;
        }

        .el-icon-caret-bottom {
          cursor: pointer;
          position: absolute;
          right: -20px;
          top: 25px;
          font-size: 12px;
        }
      }
    }
  }
}
.home-mark {
  //display: inline-block;
  padding-top: 8px;
  float: left;
  margin-right: 15px;
  // background: url('~@/assets/img/tool_home.png') no-repeat;
  // padding: 0 15px;
}

.NAVactive {
  color: var(--sport_active_text);
  // text-shadow: 0 0 0.7em #ffe5a5, 0 0 0.4em #f1dfb1, 0 0 0.2em #f1dfb1;
  // color: #ffe5a5;
  // text-shadow: #525252 0.2em 0.2em 0.3em;
  font-weight: 800;
}
.NAVactiveSVG {
  color: var(--sport_active_text);
  // filter:drop-shadow(0 0 6px #f1dfb1);
}
.ch-nav {
 font-size: 1rem;
}

.fade-enter-active {
    animation: slideFromRight .3s;
}
.fade-leave-to {
    animation: slideFromLeft .3s;
}

@keyframes slideFromRight {
    0% {
        transform: translate(100%, 0);
        -webkit-transform: translate(100%, 0);
    }
    100% {
        transform: translate(0, 0);
        -webkit-transform: translate(0, 0);
    }
}
@keyframes slideFromLeft {
    0% {
        transform: translate(0, 0);
        -webkit-transform: translate(0, 0);
    }
    100% {
        transform: translate(100%, 0);
        -webkit-transform: translate(100%, 0);
    }
}
</style>
