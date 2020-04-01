<template>
  <div v-show="false && showPopUp" class="popUp">
    <transition name="popUp">
      <div v-if="showPopUp" class="inside">
        <div class="hintText">
          <span>点击【前往投注】进行投注</span>
        </div>
        <div class="hintText"><span>或</span></div>
        <div class="hintText">
          <span>点击【回到直播】观看直播</span>
        </div>
        <div class="bottomWrap">
          <div class="button" @click="toBettingPage()"><span>前往投注</span></div>
          <div class="button" @click="showPopUp=false"><span>回到直播</span></div>
        </div>
      </div>
    </transition>
    <div class="dummyBackground" @click="showPopUp = false" />
  </div>
</template>
<script>

import appModule from '@/utils/appTalking'
import VueCookie from 'vue-cookie'
import { functionBus } from '@/yabolive'

export default {
  data() {
    return {
      showPopUp: false,
      device: 'undetected'
    }
  },
  computed: {
    AppTalking() {
      return appModule.AppTalking
    }
  },
  mounted() {
    functionBus.$on('showPopUp', () => {
      this.showPopUp = true
    })
    this.device = VueCookie.get('abs-platform')
  },
  beforeDestroy() {
    functionBus.$off('showPopUp')
  },
  methods: {
    toBettingPage() {
      // const { AppTalking, isWebview } = this
      const { AppTalking } = this
      if (top.sportEvent) {
        // For H5, PWA
        top.sportEvent('GO_IM_SPORT')
      } else if (this.device === 'ios' || this.device === 'android') {
        // For Natvie APP
        AppTalking.launch_game()
      } else {
        alert('装置不支援')
      }
    }
  }
}
</script>
<style lang="scss">
.popUp {
  width: 100%;
  height: 100%;
  position: fixed;
  background: rgba(0, 0, 0, 0.4);
  z-index: 99;
  display: flex;
  align-items: center;
  justify-content: center;
  .dummyBackground {
    position: fixed;
    width: 100%;
    z-index: 10;
    height: 100%;
  }
  .inside {
    z-index: 11;
    position: absolute;
    border-radius: 8px;
    width: 250px;
    height: auto;
    top: 50vh;
    background: white;
    .hintText {
      padding:0 10px;
      width: 100%;
      text-align: center;
      height: 30px;
      line-height: 30px;
    }
    .bottomWrap{
        margin-top: 10px;
        border-top:solid 1px rgb(187, 187, 187);
        height: 40px;
        line-height: 40px;
        display:flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        .button{
            width: 50%;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            &:nth-child(n+2){
                border-left: solid 1px rgb(187, 187, 187);
            }
        }
    }
  }
}

.popUp-enter-active,
.popUp-leave-active,
.popUp-move {
  transition: 200ms;
  transition-property: opacity, transform;
}

.popUp-enter {
  opacity: 0;
  transform: translateY(-50px) scaleX(1);
}

.popUp-enter-to {
  opacity: 1;
  transform: translateY(0) scaleX(1);
}

.popUp-leave-active {
  position: absolute;
}

.popUp-leave-to {
  opacity: 0;
  transform: translateY(-50px);
  transform-origin: center top;
}
</style>
