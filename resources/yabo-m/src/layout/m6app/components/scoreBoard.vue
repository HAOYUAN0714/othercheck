<template>
  <div class="container">
    <div v-if="!isOpenWiget" class="match-info">
      <div class="match-status">{{ matchInfo.Market === 3 ? '滾球中' : '未开赛' }}</div>
      <div class="team-info clearfix">
        <div class="team home-team">
          <img
            :src="`/exsport/img/TeamImageFile/${matchInfo.HomeTeamId}.png`"
            class="team-logo"
            @error="setDefaultLogo"
          >
          <div class="team-name">{{ matchInfo.HomeTeam }}</div>
        </div>
        <div class="score-info">
          <div v-if="scoreFilter" class="score">{{ matchInfo.HomeScore }} - {{ matchInfo.AwayScore }}</div>
          <div v-else>VS</div>
        </div>
        <div class="team away-team">
          <img
            :src="`/exsport/img/TeamImageFile/${matchInfo.AwayTeamId}.png`"
            class="team-logo"
            @error="setDefaultLogo"
          >
          <div class="team-name">{{ matchInfo.AwayTeam }}</div>
        </div>
      </div>
      <div class="match-time">
        <realTime
          v-if="matchInfo.Market === 3"
          :time="matchInfo.RBTime"
          :sport-id="+(sportId)"
          :time-status="matchInfo.RBTimeStatus"
        />
        <span v-else>{{ formatMatchDate(matchInfo.EventDate) }}</span>
      </div>
      <div class="control-bar clearfix">
        <div>視頻</div>
        <div @click="isOpenWiget = true">動畫</div>
        <div>說明</div>
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
import wiget from '@/components/common/widget'
import moment from 'moment'
import realTime from '@/layout/components/realTimeClock'
import defaultImg from '@/assets/m6app/ic_team_logo_default.png'

export default {
  components: {
    wiget,
    realTime
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
      isOpenWiget: false
    }
  },
  computed: {
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
.container {
    position: relative;
    min-height: 230px;
    .match-info {
      background: url('~@/assets/m6app/events_detals_bg.png') 0 0 no-repeat;
      background-size: cover;
      min-height: 190px;
      padding-top: 75px;
      font-size: 13px;
      .match-status {
        text-align: center;
      }
      .team-info {
        padding: 0 20px;
        .team,
        .score-info {
          float: left;
        }
        .team {
          width: 30%;
          height: 65px;
          background-size: cover;
          .team-logo {
            position: absolute;
            width: 45px;
            height: 45px;
          }
        }
        .home-team {
          background: url('~@/assets/m6app/eventdetails_content_score_bg3.png') 100% -2px no-repeat;
          background-size: cover;
          .team-logo {
            top: 75px;
            left: 14%;
          }
          .team-name {
            padding: 26px 0 0 21px;
            display: -webkit-box;
            text-align: center;
            // overflow-x: scroll;
            width: 100%;
            // white-space: nowrap;
            // &::-webkit-scrollbar {
            // width: 5px;
            // }
            // &::-webkit-scrollbar:horizontal {
            //   height: 5px;
            // }
            // &::-webkit-scrollbar-track {
            //   background-color: transparentize(#ccc, 0.7);
            // }
            // &::-webkit-scrollbar-thumb {
            //   border-radius: 5px;
            //   background: transparentize(#ccc, 0.5);
            //   box-shadow: inset 0 0 6px #c0c4cce0;
            // }
          }
        }
        .away-team {
          background: url('~@/assets/m6app/eventdetails_content_score_bg4.png') 0 -2px no-repeat;
          background-size: cover;
          .team-logo {
            top: 75px;
            right: 14%;
          }
          .team-name {
            padding-top: 26px;
          }
        }
        .score-info {
          background: url('~@/assets/m6app/eventdetails_content_score_bg1.png') 50% 0 no-repeat;
          width: 40%;
          height: 65px;
          background-size: cover;
          padding-top: 21px;
          font-weight: bold;
          font-size: 24px;
          color: #FFF;
          text-align: center;
        }
      }
      .match-time {
        background: url('~@/assets/m6app/eventdetails_content_score_bg2.png') 50% 0 no-repeat;
        background-size: cover;
        width: 43%;
        height: 19px;
        line-height: 19px;
        margin: 0 auto;
        text-align: center;
      }
      .control-bar {
        background-color: #f4f4f5;
        position: absolute;
        bottom: 0;
        width: 100%;
        div {
          float: left;
          width: 33%;
          padding: 10px 0;
          border-right: 1px solid #ebeef5;
          font-size: 12px;
          text-align: center;
        }
      }
    }
    .wiget-wrap {
      background-color: #000;
      min-height: 190px;
      padding-top: 57px;
      color: #FFF;
      .no-data {
        margin-top: 10px;
        text-align: center;
      }
    }
    .close {
      position: absolute;
      top: 59px;
      right: 10px;
      width: 20px;
      height: 20px;
      color: #FFF;
    }
  }
</style>
