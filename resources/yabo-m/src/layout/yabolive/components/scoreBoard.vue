<template>
  <div class="container">
    <div class="match-info">
      <div v-if="path === 'moreSportToday'" class="boxing">
        <!-- {{ getLiveUrl }} -->
        <div v-if="getLiveUrl===''" class="tvTips">此场赛事无直播</div>
        <div v-show="getLiveUrl!==''" id="dplayer" />
        <div v-show="getLiveUrl===''" class="noVideo" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import 'dplayer/dist/DPlayer.min.css'
import DPlayer from 'dplayer'

export default {
  components: {},
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
      vedioUrl: '',
      path: ''
      // test: '1234'
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
    this.$nextTick(function() {
      const dp = new DPlayer({
        container: document.getElementById('dplayer'),
        autoplay: true,
        video: {
          // url: 'https://d2zihajmogu5jn.cloudfront.net/bipbop-advanced/bipbop_16x9_variant.m3u8'
          url: this.getLiveUrl
        }
      })
      console.log(dp, 'dp')
    })
  },
  methods: {}
}

</script>

<style lang="scss" scoped>
#dplayer {
    width: 100%;
    height: 90%;
    >>>.dplayer-full-in-icon {
      display: none !important;
    }
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
    min-height: 215px;
    .match-info {
      // background: url('~@/assets/m6app/events_detals_bg.png') 0 0 no-repeat;
      // background-size: cover;
      // background-color: #000;
      min-height: 215px;
      font-size: 13px;
      position: fixed;
      top: 45px;
      width: 100%;
      z-index: 1;
      .boxing {
        height: 250px;
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
  }
</style>
