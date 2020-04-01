<template>
  <div class="container">
    <div v-if="!isOpenWiget" class="match-info">
      <div v-if="path === 'moreSportToday'">
        <div v-if="getLiveUrl===''" class="tvTips">此场赛事无直播</div>
        <miniVideo
          v-if="getLiveUrl!==''"
          :key="getLiveUrl"
          :flv-url="getLiveUrl"
        />
        <div v-else class="noVideo" />
      </div>

    </div>
    <div v-else class="wiget-wrap">
      <wiget v-if="matchInfo.HasVisualization" :match-id="matchInfo.BREventId" />
      <div v-else class="no-data">无可用数据</div>
    </div>
    <div v-if="isOpenWiget" class="close" @click="isOpenWiget = false">
      <i class="fas fa-times" />
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import wiget from '@/components/common/widget'
import moment from 'moment'
// import realTime from '@/layout/components/realTimeClock'
import miniVideo from '@/layout/bblivem/components/miniVideo'
import defaultImg from '@/assets/m6app/ic_team_logo_default.png'

export default {
  components: {
    wiget,
    miniVideo
    // realTime
  },
  props: {
    matchInfo: {
      type: Object,
      default: () => ({})
    },
    sportId: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      isOpenWiget: false,
      vedioUrl: '',
      path: ''
    }
  },
  computed: {
    ...mapGetters({
      getLiveUrl: 'm6app/getLiveUrl'
    }),
    scoreFilter() {
      if (this.matchInfo.HomeScore && this.matchInfo.AwayScore) {
        return true
      }
      if (this.matchInfo.HomeScore === 0 || this.matchInfo.AwayScore === 0) {
        return true
      }
      return false
    }
  },
  mounted() {
    this.path = this.$route.params.routername
  },
  methods: {
    formatMatchDate(val) {
      return moment(val).utcOffset(-4).format('MM-DD  HH:mm')
    },
    setDefaultLogo(e) {
      e.target.src = defaultImg
    }
  }
}

</script>

<style lang="scss" scoped>
#videoElement {
    width: 100%;
    height: 250px;
    position: fixed;
    top: 30px;
}
.noVideo {
  background-image: url('~@/assets/img/liveball.png');
  background-size: 100% 100%;
  width: 100%;
  height: 230px;
  background-color: #000;
  position: fixed;
  top: 45px;
}
.container {
    position: relative;
    min-height: 230px;
    .match-info {
      background: url('~@/assets/m6app/events_detals_bg.png') 0 0 no-repeat;
      background-size: cover;
      min-height: 230px;
      // min-height: 230px;
      // padding-top: 45px;
      font-size: 13px;
      // position: relative;
      position: fixed;
      top: 45px;
      width: 100%;
      z-index: 1;
      .scorecamp {
        margin-top: 40px;
      }
      .tvTips {
        position: absolute;
        background-color: #e4e4e4bf;
        right: 35%;
        top: 40%;
        color: #606266;;
        z-index: 1;
        padding: 10px;
        border-radius: 5px;
      }
    }
  }
</style>
