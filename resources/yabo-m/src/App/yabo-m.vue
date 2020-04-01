<template>
  <div id="app">
    <router-view />
    <!-- <betslip2 v-if="betSlip === true" /> -->
  </div>
</template>

<script>
// import betslip2 from '@/layout/components/betSlip'
// import { mapGetters } from 'vuex'
import { getExtendSession } from '@/utils/getBallData'

export default {
  name: 'App',
  data() {
    return {
      timeout: ''
    }
  },
  created() {
    this.timeout = setInterval(() => {
      getExtendSession()
    }, 180000)
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  }
}
</script>

<style lang="scss">
.icon-1 {
  background: url('~@/assets/img/ball_soccer.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_soccer_active.png') 50% 100% no-repeat;
  }
}
.icon-2 {
  background: url('~@/assets/img/ball_basketball.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_basketball_active.png') 50% 100% no-repeat;
  }
}
.icon-3 {
  background: url('~@/assets/img/ball_tennis.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_tennis_active.png') 50% 100% no-repeat;
  }
}
.icon-6 {
  background: url('~@/assets/img/ball_athletics.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_athletics_active.png') 50% 100% no-repeat;
  }
}
.icon-7 {
  background: url('~@/assets/img/ball_badminton.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_badminton_active.png') 50% 100% no-repeat;
  }
}
.icon-8 {
  background: url('~@/assets/img/ball_baseball.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_baseball_active.png') 50% 100% no-repeat;
  }
}
.icon-11 {
  background: url('~@/assets/img/ball_boxing.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_boxing_active.png') 50% 100% no-repeat;
  }
}
.icon-13 {
  background: url('~@/assets/img/ball_cricket.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_cricket_active.png') 50% 100% no-repeat;
  }
}
.icon-15 {
  background: url('~@/assets/img/ball_darts.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_darts_active.png') 50% 100% no-repeat;
  }
}
.icon-18 {
  background: url('~@/assets/img/ball_grassland.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_grassland_active.png') 50% 100% no-repeat;
  }
}
.icon-19 {
  background: url('~@/assets/img/ball_football.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_football_active.png') 50% 100% no-repeat;
  }
}
.icon-21 {
  background: url('~@/assets/img/ball_golf.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_golf_active.png') 50% 100% no-repeat;
  }
}
.icon-23 {
  background: url('~@/assets/img/ball_handball.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_handball_active.png') 50% 100% no-repeat;
  }
}
.icon-25 {
  background: url('~@/assets/img/ball_icehockey.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_icehockey_active.png') 50% 100% no-repeat;
  }
}
.icon-29 {
  background: url('~@/assets/img/ball_carsports.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_carsports_active.png') 50% 100% no-repeat;
  }
}
.icon-31 {
  background: url('~@/assets/img/ball_rugby.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_rugby_active.png') 50% 100% no-repeat;
  }
}
.icon-32 {
  background: url('~@/assets/img/ball_sailboat.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_sailboat_active.png') 50% 100% no-repeat;
  }
}
.icon-34 {
  background: url('~@/assets/img/ball_snooker.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_snooker_active.png') 50% 100% no-repeat;
  }
}
.icon-39 {
  background: url('~@/assets/img/ball_virtualsoccer.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_virtualsoccer_active.png') 50% 100% no-repeat;
  }
}
.icon-40 {
  background: url('~@/assets/img/ball_volleyball.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_volleyball_active.png') 50% 100% no-repeat;
  }
}
.icon-41 {
  background: url('~@/assets/img/ball_waterpolo.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_waterpolo_active.png') 50% 100% no-repeat;
  }
}
.icon-43 {
  background: url('~@/assets/img/ball_virtualbasketball.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_virtualbasketball_active.png') 50% 100% no-repeat;
  }
}
.icon-44 {
  background: url('~@/assets/img/ball_virtualworld.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_virtualworld_active.png') 50% 100% no-repeat;
  }
}
.icon-45 {
  background: url('~@/assets/img/ball_entertainment.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_entertainment_active.png') 50% 100% no-repeat;
  }
}
.icon-46 {
  background: url('~@/assets/img/ball_virtualnations.png') 50% 100% no-repeat;
  background-size: contain !important;
  &.active {
    background: url('~@/assets/img/ball_virtualnations_active.png') 50% 100% no-repeat;
  }
}
</style>
